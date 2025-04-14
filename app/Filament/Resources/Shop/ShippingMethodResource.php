<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\ShippingMethodResource\Pages;
use App\Filament\Resources\Shop\ShippingMethodResource\RelationManagers;
use App\Models\Shop\ShippingMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ShippingMethodResource extends Resource
{
    use Translatable;

    protected static ?string $model = ShippingMethod::class;

    protected static ?string $navigationGroup = 'Shipping';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $code = str_replace('-', '_', Str::slug($state));
                                $set('code', $code);
                            }),
                        Forms\Components\RichEditor::make('description'),
                        //Forms\Components\KeyValue::make('conditions'),
                        Forms\Components\Fieldset::make('Allow calculation by distance')
                        ->schema([
                            Forms\Components\Toggle::make('by_distance'),
                            Forms\Components\TextInput::make('per_km')
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->default(0),
                            ])
                            ->columns(2),
                        Forms\Components\Repeater::make('conditions')
                            ->schema([
                                Forms\Components\Select::make('zone')
                                    ->options(function(): \Illuminate\Support\Collection {
                                        $options = ['ANY' => 'Any'];
                                        $zones = \App\Models\Shop\ShippingZone::query()->pluck('name', 'code')->toArray();
                                        if(!empty($zones)){
                                            return collect(array_merge($options, $zones));
                                        }
                                        return collect($options);
                                    })
                                    ->live()
                                    ->preload()
                                    ->label('Shipping Zone')
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(['lg' => 3]),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->default(0)
                                    ->required()
                                    ->columnSpan(['lg' => 1]),
                                Forms\Components\TimePicker::make('availability[start]')
                                    ->seconds(false)
                                    ->default('00:00')
                                    ->columnSpan(2)
                                    ->label('From')
                                    ->helperText('Make available from time'),
                                Forms\Components\TimePicker::make('availability[end]')
                                    ->seconds(false)
                                    ->default('23:59')
                                    ->columnSpan(2)
                                    ->label('To')
                                    ->helperText('Make available till time'),
                                Forms\Components\TextInput::make('availability[greater]')
                                    ->default(0)
                                    ->columnSpan(2)
                                    ->label('Greater')
                                    ->helperText('When total is greater than'),
                                Forms\Components\TextInput::make('availability[less]')
                                    ->default(999999)
                                    ->columnSpan(2)
                                    ->label('Less')
                                    ->helperText('While total is less than'),
                            ])
                            ->columns(4),
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->unique(ShippingMethod::class, 'code', ignoreRecord: true)
                            ->required(),
                        Forms\Components\Toggle::make('is_default'),
                        Forms\Components\Toggle::make('is_active')
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns([
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingMethods::route('/'),
            'create' => Pages\CreateShippingMethod::route('/create'),
            'edit' => Pages\EditShippingMethod::route('/{record}/edit'),
        ];
    }    
}

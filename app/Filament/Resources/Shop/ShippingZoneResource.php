<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\ShippingZoneResource\Pages;
use App\Filament\Resources\Shop\ShippingZoneResource\RelationManagers;
use App\Models\Shop\ShippingZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;

    protected static ?string $navigationGroup = 'Shipping';

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

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
                        //Forms\Components\Fieldset::make('Location')
                        Forms\Components\Repeater::make('localities')
                            ->schema([
                                Forms\Components\Select::make('country')
                                    ->options(function(): \Illuminate\Support\Collection {
                                        $options = ['ANY' => 'Any'];
                                        $countries = \App\Models\Country::query()->pluck('name', 'iso3')->toArray();
                                        if(!empty($countries)){
                                            return collect(array_merge($options, $countries));
                                        }
                                        return collect($options);
                                    })
                                    ->live()
                                    ->preload()
                                    ->label('Country')
                                    ->searchable(),
                                Forms\Components\Select::make('locality')
                                    ->options(function (Forms\Get $get): \Illuminate\Support\Collection { 
                                        $options = ['ANY' => 'Any'];
                                        if(null !== $get('country')){
                                            $country = \App\Models\Country::query()->where('iso3', $get('country'))->first();
                                            $cities = \App\Models\City::query()->where('country_id', $country->id)->pluck('name', 'code')->toArray();
                                            return collect(array_merge($options, $cities));
                                        }
                                        return collect($options);
                                        
                                    })
                                    ->preload()
                                    ->multiple()
                                    ->label('Locality')
                                    ->searchable(), 
                            ])
                            ->defaultItems(1)
                            ->deletable(false)
                            ->addable(false)
                            ->hidden(fn (Forms\Get $get): bool => $get('on_map') === true),
                        Forms\Components\Fieldset::make('Area')
                            ->schema([
                                \App\Forms\Components\OsmPolygon::make('area')
                                    ->label(false)
                                    ->initialLat(47.02473)
                                    ->initialLng(28.8326)
                                    ->columnSpan('full'),
                            ])
                            ->hidden(fn (Forms\Get $get): bool => $get('on_map') === false),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->unique(ShippingZone::class, 'code', ignoreRecord: true)
                            ->required(),
                        Forms\Components\Toggle::make('on_map')
                            ->default(true)
                            ->required(),
                        Forms\Components\ColorPicker::make('color')
                            ->default('#0000ff')
                            ->helperText('This color will be used to show zone on the map')
                            ->hidden(fn (Forms\Get $get): bool => $get('on_map') === false),
                        
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns([
                'lg' => 3
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
            'index' => Pages\ListShippingZones::route('/'),
            'create' => Pages\CreateShippingZone::route('/create'),
            'edit' => Pages\EditShippingZone::route('/{record}/edit'),
        ];
    }    
}

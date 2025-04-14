<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\AttributeValueResource\Pages;
use App\Filament\Resources\Shop\AttributeValueResource\RelationManagers;
use App\Models\Shop\Attribute;
use App\Models\Shop\AttributeValue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AttributeValueResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = AttributeValue::class;

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('shop_attribute_id')
                            ->relationship('attribute', 'name')
                            // specify how to get the relationship translated values
                            ->getOptionLabelFromRecordUsing(fn (Attribute $record) => $record->name)
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('attr_value')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('attr_key', Str::slug($state)) : null),
                        Forms\Components\TextInput::make('attr_key')
                            //->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(AttributeValue::class, 'attr_key', ignoreRecord: true),
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->required(),
                        
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attribute.name')
                    ->label('Attribute')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attr_value')
                    ->label('Value')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated Date')
                    ->date()
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
            'index' => Pages\ListAttributeValues::route('/'),
            'create' => Pages\CreateAttributeValue::route('/create'),
            'edit' => Pages\EditAttributeValue::route('/{record}/edit'),
        ];
    }    
}

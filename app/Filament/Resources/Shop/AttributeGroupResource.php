<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\AttributeGroupResource\Pages;
use App\Filament\Resources\Shop\AttributeGroupResource\RelationManagers;
use App\Models\Shop\AttributeGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;

class AttributeGroupResource extends Resource
{
    use Translatable;
    protected static ?string $model = AttributeGroup::class;

    protected static ?string $slug = 'shop/attribute-groups';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Attribute Groups';

    protected static ?string $modelLabel = 'Attribute Group';

    protected static ?string $pluralModelLabel = 'Attribute Groups';

    public static function getTranslatableLocales(): array
    {
        return ['en', 'ro', 'ru'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(256),
                            
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Created At')
                            ->default(now())
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columnSpan(['lg' => 3]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('date')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('attributes_count')
                    ->label('Attributes')
                    ->counts('attributes')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->icon('heroicon-o-squares-2x2')
                    ->color(fn ($state): string => match (true) {
                        $state === 0 => 'gray',
                        $state < 5 => 'warning',
                        default => 'success',
                    })
                    ->tooltip(fn ($state): string => match (true) {
                        $state === 0 => 'No attributes in this group',
                        $state === 1 => '1 attribute in this group',
                        default => "{$state} attributes in this group",
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attributes_count')
                    ->label('Attributes Count')
                    ->options([
                        '0' => 'No attributes (0)',
                        '1-4' => 'Few attributes (1-4)', 
                        '5+' => 'Many attributes (5+)',
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['value']) {
                            return $query;
                        }

                        return match ($data['value']) {
                            '0' => $query->withCount('attributes')->having('attributes_count', '=', 0),
                            '1-4' => $query->withCount('attributes')->having('attributes_count', '>=', 1)->having('attributes_count', '<=', 4),
                            '5+' => $query->withCount('attributes')->having('attributes_count', '>=', 5),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttributesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttributeGroups::route('/'),
            'create' => Pages\CreateAttributeGroup::route('/create'),
            'edit' => Pages\EditAttributeGroup::route('/{record}/edit'),
        ];
    }
} 
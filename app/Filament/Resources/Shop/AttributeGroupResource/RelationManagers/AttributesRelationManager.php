<?php

namespace App\Filament\Resources\Shop\AttributeGroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Tabs::make('Translations')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Russian')
                                    ->schema([
                                        Forms\Components\TextInput::make('name.ru')
                                            ->label('Name (Russian)')
                                            ->maxLength(256),
                                    ]),
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('name.en')
                                            ->label('Name (English)')
                                            ->maxLength(256)
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Romanian')
                                    ->schema([
                                        Forms\Components\TextInput::make('name.ro')
                                            ->label('Name (Romanian)')
                                            ->maxLength(256),
                                    ]),
                            ]),
                            
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Created At')
                            ->default(now())
                            ->disabled()
                            ->dehydrated(),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('name_ru')
                    ->label('RU')
                    ->searchable(false)
                    ->sortable(false)
                    ->toggleable()
                    ->placeholder('—')
                    ->limit(30)
                    ->state(function ($record): ?string {
                        $translation = $record->getTranslation('name', 'ru', false);
                        return $translation ?: null;
                    })
                    ->tooltip(function ($record): ?string {
                        $translation = $record->getTranslation('name', 'ru', false);
                        return $translation ?: null;
                    })
                    ->color(function ($record) {
                        $translation = $record->getTranslation('name', 'ru', false);
                        return empty($translation) ? 'gray' : null;
                    }),
                    
                Tables\Columns\TextColumn::make('name_en')
                    ->label('EN')
                    ->searchable(false)
                    ->sortable(false)
                    ->toggleable()
                    ->placeholder('—')
                    ->limit(30)
                    ->state(function ($record): ?string {
                        $translation = $record->getTranslation('name', 'en', false);
                        return $translation ?: null;
                    })
                    ->tooltip(function ($record): ?string {
                        $translation = $record->getTranslation('name', 'en', false);
                        return $translation ?: null;
                    })
                    ->color(function ($record) {
                        $translation = $record->getTranslation('name', 'en', false);
                        return empty($translation) ? 'gray' : null;
                    }),
                    
                Tables\Columns\TextColumn::make('name_ro')
                    ->label('RO')
                    ->searchable(false)
                    ->sortable(false)
                    ->toggleable()
                    ->placeholder('—')
                    ->limit(30)
                    ->state(function ($record): ?string {
                        $translation = $record->getTranslation('name', 'ro', false);
                        return $translation ?: null;
                    })
                    ->tooltip(function ($record): ?string {
                        $translation = $record->getTranslation('name', 'ro', false);
                        return $translation ?: null;
                    })
                    ->color(function ($record) {
                        $translation = $record->getTranslation('name', 'ro', false);
                        return empty($translation) ? 'gray' : null;
                    }),
                    
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('attributeValues_count')
                    ->label('Values count')
                    ->counts('attributeValues')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->slideOver()
                    ->modalHeading('Create Attribute')
                    ->modalSubheading('Add a new attribute to this group'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalHeading('Edit Attribute'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
} 
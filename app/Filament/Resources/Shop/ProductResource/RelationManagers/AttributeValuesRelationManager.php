<?php

namespace App\Filament\Resources\Shop\ProductResource\RelationManagers;

use App\Models\Shop\Attribute;
use App\Models\Shop\AttributeGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Actions\Action;

class AttributeValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributeValues';

    protected static ?string $recordTitleAttribute = 'value';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('attribute_id')
                                    ->label('Attribute')
                                    ->relationship('attribute', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $groupName = $record->attributeGroup->getTranslation('name', app()->getLocale()) ?? 
                                                   $record->attributeGroup->getTranslation('name', 'ru') ?? 
                                                   $record->attributeGroup->name;
                                        $attrName = $record->getTranslation('name', app()->getLocale()) ?? 
                                                  $record->getTranslation('name', 'ru') ?? 
                                                  $record->name;
                                        return "{$groupName} → {$attrName}";
                                    })
                                    ->columnSpanFull(),
                                    
                                Forms\Components\Tabs::make('Value Translations')
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make('Russian')
                                            ->schema([
                                                Forms\Components\TextInput::make('value.ru')
                                                    ->label('Value (Russian)')
                                                    ->maxLength(255)
                                                    ->required(),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('English')
                                            ->schema([
                                                Forms\Components\TextInput::make('value.en')
                                                    ->label('Value (English)')
                                                    ->maxLength(255),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Romanian')
                                            ->schema([
                                                Forms\Components\TextInput::make('value.ro')
                                                    ->label('Value (Romanian)')
                                                    ->maxLength(255),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                                    
                                Forms\Components\DateTimePicker::make('date')
                                    ->label('Created At')
                                    ->default(now())
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),
                            ]),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('value')
            ->modifyQueryUsing(fn ($query) => $query->with(['attribute.attributeGroup']))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('group_name')
                    ->label('Group')
                    ->searchable(false)
                    ->sortable(false)
                    ->state(function ($record): ?string {
                        if ($record->attribute && $record->attribute->attributeGroup) {
                            return $record->attribute->attributeGroup->getTranslation('name', app()->getLocale()) ?? 
                                   $record->attribute->attributeGroup->getTranslation('name', 'ru') ?? 
                                   $record->attribute->attributeGroup->name;
                        }
                        return '—';
                    }),
                    
                Tables\Columns\TextColumn::make('attribute_name')
                    ->label('Attribute')
                    ->searchable(false)
                    ->sortable(false)
                    ->state(function ($record): ?string {
                        if ($record->attribute) {
                            return $record->attribute->getTranslation('name', app()->getLocale()) ?? 
                                   $record->attribute->getTranslation('name', 'ru') ?? 
                                   $record->attribute->name;
                        }
                        return '—';
                    }),
                    
                Tables\Columns\TextColumn::make('value_ru')
                    ->label('RU')
                    ->searchable(false)
                    ->sortable(false)
                    ->placeholder('—')
                    ->limit(30)
                    ->state(function ($record): ?string {
                        $translation = $record->getTranslation('value', 'ru', false);
                        return $translation ?: null;
                    })
                    ->tooltip(function ($record): ?string {
                        $translation = $record->getTranslation('value', 'ru', false);
                        return $translation ?: null;
                    })
                    ->color(function ($record) {
                        $translation = $record->getTranslation('value', 'ru', false);
                        return empty($translation) ? 'gray' : null;
                    }),
                    
                Tables\Columns\TextColumn::make('value_en')
                    ->label('EN')
                    ->searchable(true)
                    ->sortable(false)
                    ->toggleable()
                    ->placeholder('—')
                    ->limit(30)
                    ->state(function ($record): ?string {
                        $translation = $record->getTranslation('value', 'en', false);
                        return $translation ?: null;
                    })
                    ->tooltip(function ($record): ?string {
                        $translation = $record->getTranslation('value', 'en', false);
                        return $translation ?: null;
                    })
                    ->color(function ($record) {
                        $translation = $record->getTranslation('value', 'en', false);
                        return empty($translation) ? 'gray' : null;
                    }),
                    
                Tables\Columns\TextColumn::make('value_ro')
                    ->label('RO')
                    ->searchable(false)
                    ->sortable(false)
                    ->toggleable()
                    ->placeholder('—')
                    ->limit(30)
                    ->state(function ($record): ?string {
                        $translation = $record->getTranslation('value', 'ro', false);
                        return $translation ?: null;
                    })
                    ->tooltip(function ($record): ?string {
                        $translation = $record->getTranslation('value', 'ro', false);
                        return $translation ?: null;
                    })
                    ->color(function ($record) {
                        $translation = $record->getTranslation('value', 'ro', false);
                        return empty($translation) ? 'gray' : null;
                    }),
                    
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attribute_group')
                    ->label('Attribute Group')
                    ->options(function () {
                        return AttributeGroup::all()->pluck('name', 'id')->map(function ($name, $id) {
                            $group = AttributeGroup::find($id);
                            return $group->getTranslation('name', app()->getLocale()) ?? 
                                   $group->getTranslation('name', 'ru') ?? 
                                   $group->name;
                        });
                    })
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            return $query->whereHas('attribute', function ($q) use ($data) {
                                $q->where('group_id', $data['value']);
                            });
                        }
                        return $query;
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->slideOver()
                    ->modalHeading('Add Attribute Value')
                    ->modalSubheading('Add an attribute value to this product'),
                    
                Tables\Actions\Action::make('addFromGroup')
                    ->label('Add from Group')
                    ->icon('heroicon-o-squares-plus')
                    ->color('success')
                    ->slideOver()
                    ->modalHeading('Add Attributes from Group')
                    ->modalSubheading('Select an attribute group to add all its attributes')
                    ->form([
                        Forms\Components\Select::make('attribute_group_id')
                            ->label('Attribute Group')
                            ->options(function () {
                                return AttributeGroup::all()->pluck('name', 'id')->map(function ($name, $id) {
                                    $group = AttributeGroup::find($id);
                                    return $group->getTranslation('name', app()->getLocale()) ?? 
                                           $group->getTranslation('name', 'ru') ?? 
                                           $group->name;
                                });
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $group = AttributeGroup::find($state);
                                    if ($group) {
                                        $attributes = $group->attributes()->get();
                                        $attributesList = $attributes->map(function ($attr) {
                                            return [
                                                'id' => $attr->id,
                                                'name' => $attr->getTranslation('name', app()->getLocale()) ?? 
                                                         $attr->getTranslation('name', 'ru') ?? 
                                                         $attr->name,
                                            ];
                                        })->toArray();
                                        $set('attributes_preview', $attributesList);
                                    }
                                }
                            }),
                            
                        Forms\Components\Placeholder::make('attributes_preview')
                            ->label('Attributes in this group')
                            ->content(function ($get) {
                                $attributes = $get('attributes_preview') ?? [];
                                if (empty($attributes)) {
                                    return 'Select a group to see its attributes';
                                }
                                return collect($attributes)->pluck('name')->join(', ');
                            }),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        if (!$data['attribute_group_id']) {
                            return;
                        }
                        
                        $group = AttributeGroup::find($data['attribute_group_id']);
                        $product = $livewire->ownerRecord;
                        
                        foreach ($group->attributes as $attribute) {
                            // Check if attribute value doesn't already exist for this product
                            $existingValue = $product->attributeValues()
                                ->where('attribute_id', $attribute->id)
                                ->first();
                                
                            if (!$existingValue) {
                                $product->attributeValues()->create([
                                    'attribute_id' => $attribute->id,
                                    'value' => [
                                        'ru' => '',
                                        'en' => '',
                                        'ro' => '',
                                    ], // Empty values for all languages, user can fill later
                                    'date' => now(),
                                ]);
                            }
                        }
                        
                        // Refresh the table
                        $livewire->dispatch('refresh');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalHeading('Edit Attribute Value'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }
} 
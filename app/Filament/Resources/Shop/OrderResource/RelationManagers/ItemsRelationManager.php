<?php

namespace App\Filament\Resources\Shop\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Log;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'reference';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('product.name')->label('Product Name'),
                TextEntry::make('product.compositionList.product.name')
                    ->label('Composition')
                    ->getStateUsing(function ($record){
                        $names = [];
                        foreach($record->product->compositionList as $key => $composition){
                            $names[] = $composition->variation['name'] . ': ' . ($record['changed_composition'] !== null ? $composition['qty'] + (int)$record['changed_composition'][$key]['quantity'] : $composition['qty']) . "\n";
                        }
                        return $names;
                    }),
                TextEntry::make('qty')->label('Quantity'),
                TextEntry::make('unit_price')->label('Unit Price'),
                TextEntry::make('subtotal')->label('Subtotal'),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('variation.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
                ->form([
                    Forms\Components\TextInput::make('qty')
                        ->default(fn ($record) => Log::info('Current Record:', ['record' => $record]))
                        ->required()
                        ->maxLength(255),
                ]),
            ])
            ->groupedBulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

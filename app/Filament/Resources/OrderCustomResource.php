<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderCustomResource\Pages;
use App\Filament\Resources\OrderCustomResource\RelationManagers;
use App\Filament\Resources\OrderCustomResource\Widgets\OrderCustomStats;
use App\Models\OrderCustom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderCustomResource extends Resource
{
    protected static ?string $model = OrderCustom::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Client::where('name', 'like', "%{$search}%")
                            ->limit(20)
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return \App\Models\Client::find($value)?->name ?? $value;
                    })
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'New',
                        'error' => 'Error',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('cart_id')->label('Cart ID')->numeric(),
                Forms\Components\Textarea::make('comments')->label('Comments'),
                Forms\Components\TextInput::make('total')->label('Total')->required()->numeric(),
                Forms\Components\TextInput::make('formatted_total')
                    ->label(fn ($record) => 'Total')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) => $record ? \App\Models\Shop\Currency::formatCustom($record->total, $record->currency) : null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state, $record) => \App\Models\Shop\Currency::formatCustom($state, $record->currency))
                    ->sortable(),
                Tables\Columns\TextColumn::make('cart.total_items')
                    ->label('Items Count')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'error' => 'danger',
                        'processing' => 'warning',
                        'completed' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d.m.Y H:i')
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('status', 'processing')->count();
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderCustoms::route('/'),
            'create' => Pages\CreateOrderCustom::route('/create'),
            'edit' => Pages\EditOrderCustom::route('/{record}/edit'),
            'view' => Pages\ViewOrderCustom::route('/{record}'),
        ];
    }    

    public static function getWidgets(): array
    {
        return [
            OrderCustomResource\Widgets\OrderCustomStats::class,
        ];
    }
}

<?php

namespace App\Filament\Resources\Navigation;

use App\Filament\Resources\Navigation\ItemResource\Pages;
use App\Filament\Resources\Navigation\ItemResource\RelationManagers;
use App\Models\Navigation\Menu;
use App\Models\Navigation\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    use Translatable;

    protected static ?string $model = Item::class;

    protected static ?string $navigationGroup = 'Navigation';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('menu_id')
                                    ->relationship('menu', 'name')
                                    ->getOptionLabelFromRecordUsing(fn (Menu $record) => $record->name)
                                    ->preload()
                                    ->searchable(),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('entity')
                    ->options([
                        '\App\Models\Page\Page' => 'Page',
                        '\App\Models\Blog\Category' => 'Blog Category',
                        '\App\Models\Blog\Post' => 'Blog Post',
                        '\App\Models\Shop\Category' => 'Shop Category',
                        '\App\Models\Shop\Product' => 'Shop Product',
                        '\App\Models\Page\Service' => 'Service',
                    ])
                    ->native(false)
                    ->reactive(),
                Forms\Components\Select::make('entity_id')
                    ->label('Target')
                    ->options(function (callable $get, callable $set) {
                        $options = [];
                        /**
                         * Get options according to selected entity
                         */
                        if(null !== $get('entity')){
                            $class = $get('entity');
                            $model = new $class();
                            switch ($class) {
                                case '\App\Models\Blog\Post':
                                    $options = $model->all()->pluck('title', 'id');
                                    break;
                                
                                case '\App\Models\Page\Service':
                                    $options = $model->all()->pluck('title', 'id');
                                    break;

                                // \App\Models\Page\Page
                                // \App\Models\Blog\Category
                                // \App\Models\Shop\Category
                                // \App\Models\Shop\Product
                                default:
                                    $options = $model->all()->pluck('name', 'id');
                                    break;
                            }
                        }
                        return $options;
                    })
                    ->native(false)
                    ->searchable(),
                Forms\Components\TextInput::make('link')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menu.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }    
}

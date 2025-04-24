<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\BrandResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Shop\ProductResource\Pages;
use App\Filament\Resources\Shop\ProductResource\RelationManagers;
use App\Filament\Resources\Shop\ProductResource\Widgets\ProductStats;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    use Translatable;
    
    // Temporary disabled, remove or comment line below to enable
    // protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $model = Product::class;

    protected static ?string $slug = 'shop/products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Products';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_ru')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_full')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('articul')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('brand')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('brand_code')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('category')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('category_code')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('additional_cat')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('additional_cat_code')
                            ->maxLength(50),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Stock')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('currency')
                            ->maxLength(10)
                            ->default('USD'),
                        Forms\Components\TextInput::make('default_price')
                            ->numeric(),
                        Forms\Components\TextInput::make('default_currency')
                            ->maxLength(10)
                            ->default('MDL'),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('reserved')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('unit_type')
                            ->maxLength(50)
                            ->default('buc.'),
                    ])->columns(2),

                Forms\Components\MarkdownEditor::make('description')
                    ->columnSpan('full'),

                Forms\Components\Section::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            null,
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->collection('product-images')
                        ->multiple()
                        ->maxFiles(5)
                        ->disableLabel(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\VariationsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'brand.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Product $record */

        return [
            'Brand' => optional($record->brand)->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }

    /* public static function getNavigationBadge(): ?string
    {
        return static::$model::whereColumn('qty', '<', 'security_stock')->count();
    } */
}

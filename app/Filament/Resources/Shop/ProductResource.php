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

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Products';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
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

                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    //->disabled()
                                    ->dehydrated()
                                    ->required()
                                    //->unique(Product::class, 'slug', ignoreRecord: true)
                                    ->rules([
                                        fn (Product | null $record, Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($record, $get) {
                                            // Check if there are records with provided slug
                                            $existing = Product::select('id')->whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $value])->first();
                                            // If does not exists, continue
                                            if(empty($existing)){
                                                return;
                                            }
                                            // If found same record only, continue
                                            // Rule will be applied for update operation
                                            // Prevent update restriction
                                            if(null !== $record && $existing->id === $record->id){
                                                return;
                                            }
                                            // Show fail helper text
                                            $fail("The {$attribute} is already taken. Please modify to make it unique!");
                                        },
                                    ]),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpan('full'),
                            ])
                            ->columns(1),

                        Forms\Components\Section::make('Variations')
                            ->schema([
                                Forms\Components\Repeater::make('options')
                                    ->schema([
                                        Forms\Components\Select::make('attribute')
                                                ->options(\App\Models\Shop\Attribute::query()->pluck('name', 'key')) // TODO: Exclude previously used attributes
                                                ->label('Attribute')
                                                ->searchable()
                                                ->preload()
                                                ->live(),
                                                    /*->createOptionForm($attForm)
                                                    ->createOptionUsing(function (array $data) {
                                                        $model = \App\Models\Shop\Attribute::create($data);
                                                        return $model->id;
                                                    }),*/

                                        Forms\Components\Select::make('values')
                                                ->multiple()
                                                ->label('Values')
                                                    /*->createOptionForm($attValForm)
                                                    ->createOptionUsing(function (array $data) {
                                                        $model = \App\Models\Shop\AttributeValue::create($data);
                                                        return $model->id;
                                                    })*/
                                                ->options(
                                                    /*fn (Forms\Get $get): \Illuminate\Support\Collection => \App\Models\Shop\AttributeValue::query()
                                                        ->where('shop_attribute_id', $get('attribute'))
                                                        ->pluck('attr_value', 'id')*/
                                                    function (Forms\Get $get): \Illuminate\Support\Collection {
                                                        if(null !== $get('attribute')){
                                                            $attr = \App\Models\Shop\Attribute::query()
                                                                ->where('key', $get('attribute'))->first();
                                                            return \App\Models\Shop\AttributeValue::query()
                                                                ->where('shop_attribute_id', $attr->id)
                                                                ->pluck('attr_value', 'attr_key');
                                                        }
                                                        /**
                                                         * Return empty collection
                                                         */
                                                        return collect([]);
                                                    } 
                                                )
                                                ->live()
                                                ->afterStateUpdated(fn (Forms\Components\Select $component) => $component
                                                    ->getContainer()
                                                    ->getComponent('presets')
                                                    ->getChildComponentContainer()
                                                    //->fill()
                                                ),
                                            
                                        Forms\Components\Section::make('Presets')
                                                ->description('Set price and images for selected values')
                                                ->collapsible()
                                                ->collapsed(true)
                                                ->schema(function (Forms\Get $get): array {
                                                        $components = [];
                                                        if(null !== $get('values')){
                                                            $attributeValues = \App\Models\Shop\AttributeValue::whereIn('attr_key', $get('values'))->get();
                                                            foreach($attributeValues as $value){
                                                                $components[] = Forms\Components\Fieldset::make($value->attr_value)
                                                                    ->schema([
                                                                        Forms\Components\TextInput::make($value->attr_key . '_price')
                                                                            ->label('Price')
                                                                            ->numeric()
                                                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                                            ->columnSpanFull(),

                                                                        Forms\Components\FileUpload::make($value->attr_key . '_images')
                                                                            ->image()
                                                                            ->multiple()
                                                                            ->maxFiles(5)
                                                                            ->disableLabel()
                                                                            ->columnSpanFull(),
                                                                    ])
                                                                    ->columnSpan(1);
                                                            }
                                                        }
                                                        
                                                        return $components;
                                                })
                                                ->columns(2)
                                                ->key('presets'),
                                    ])

                                    ->label(false)
                                    ->addActionLabel('Add attribute')
                                    ->defaultItems(false)
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->columnSpanFull()
                            ->hidden(fn (Forms\Get $get): bool => $get('type') !== 'variable'),

                        Forms\Components\Section::make('Composition')
                            ->schema([
                                Forms\Components\Repeater::make('composition')
                                    ->schema([
                                        Forms\Components\Select::make('product')
                                                ->options(\App\Models\Shop\Product::query()->pluck('name', 'id'))
                                                ->live()
                                                ->label('Product')
                                                ->searchable()
                                                ->columnSpan(2),

                                        Forms\Components\Select::make('variation')
                                                ->options(fn (Forms\Get $get): \Illuminate\Support\Collection => \App\Models\Shop\ProductVariation::query()
                                                    ->where('shop_product_id', $get('product'))
                                                    ->pluck('name', 'id'))
                                                ->label('Variation')
                                                ->searchable()
                                                ->hidden(function (Forms\Get $get): bool {
                                                    if(null === $get('product')){
                                                        return true;
                                                    } else {
                                                        $product = \App\Models\Shop\Product::find($get('product'));
                                                        if($product){
                                                            return $product->variations()->count() == 0;
                                                        }
                                                    }
                                                    return false;
                                                })
                                                ->columnSpan(2),
                                                
                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->rules(['regex:/^\d{1,3}?$/'])
                                            ->required()
                                            ->columnSpan(1),
                                    ])
                                    ->label(false)
                                    ->addActionLabel('Add product')
                                    ->defaultItems(false)
                                    ->columns(3),
                            ])
                            ->collapsible()
                            ->columnSpanFull()
                            ->hidden(fn (Forms\Get $get): bool => $get('type') !== 'complex'),

                        Forms\Components\Section::make('Inventory')
                            ->schema([
                                Forms\Components\Toggle::make('manage_stock')
                                    ->label('Stock management')
                                    ->helperText('Limit the available quantity of products')
                                    ->default(false),

                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU (Stock Keeping Unit)')
                                    ->unique(Product::class, 'sku', ignoreRecord: true),

                                Forms\Components\TextInput::make('qty')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->rules(['integer', 'min:0'])
                                    ->default(0),

                                Forms\Components\TextInput::make('security_stock')
                                    ->helperText('The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.')
                                    ->numeric()
                                    ->rules(['integer', 'min:0'])
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
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
                            ]),

                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'simple' => 'Simple',
                                        'variable' => 'Variable',
                                        'complex' => 'Complex',
                                    ])
                                    ->default('simple')
                                    ->live()
                                    ->native(false),

                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visible')
                                    ->helperText('This product will be hidden from all sales channels.')
                                    ->default(true),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Availability')
                                    ->default(now())
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('base_price')
                                    ->helperText('Base price of the product')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->default(0)
                                    ->required(),

                                Forms\Components\TextInput::make('additional_costs')
                                    ->helperText('Additional costs, as processing, packing etc. Will be added to the base price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->default(0),
                            ]),

                        Forms\Components\Section::make('Associations')
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->name)
                                    ->preload()
                                    ->multiple()
                                    ->required(),

                                Forms\Components\Select::make('shop_brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->hiddenOn(ProductsRelationManager::class),
                            ]),

                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-image')
                    ->label('Image')
                    ->collection('product-images'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                /*Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),*/

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Base Price')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('additional_costs')
                    ->label('Additional Cost')
                    ->searchable()
                    ->sortable(),   

                /*Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),*/

                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('security_stock')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->preload()
                    ->multiple()
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->trueLabel('Only visible')
                    ->falseLabel('Only hidden')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
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

    public static function getNavigationBadge(): ?string
    {
        return static::$model::whereColumn('qty', '<', 'security_stock')->count();
    }
}

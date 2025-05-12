<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Address;
use App\Models\Comment;
use Illuminate\View\View;
use App\Models\Shop\Order;
use App\Models\viewdItems;
use App\Models\Shop\Follow;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Models\Shop\CartItem;
use App\Models\Shop\Category;
use App\Models\Shop\Currency;
use App\Models\Shop\Attribute;
use App\Models\Shop\FollowItems;
use Illuminate\Support\Facades\Log;
use App\Models\Shop\ProductVariation;
use Illuminate\Support\Facades\Cookie;
use App\Models\Shop\ProductComposition;
use Illuminate\Support\Facades\View as ViewFacade;

class ShopController extends Controller
{

    public function viwedItems(Request $request){
        $data = $request->post('viewed') ?? null;
        $products = collect($data)->filter(fn($el) => $el['type'] === 'product')->all() ?? null;


        $products = null;
        $blogs = null;

        $maxCountProducts = 7;
        $maxCountBlogs = 7;
        
        if(auth()->check()){
            $viewedItemsQuery = ViewdItems::where('user_id', auth()->id())->where('type', 'product');
            $Newest = $viewedItemsQuery->get()->sortByDesc('updated_at');

            $oldest = $viewedItemsQuery->orderBy('updated_at')->get();
            $countViwedItems = $viewedItemsQuery->get()->count();

            $createItems = function ($values, $has_id = false){
                ViewdItems::create([
                    'user_id' => auth()->id(),
                    'shop_product_id' => $has_id ? (int) $values['id'] : (int) $values,
                    'type' => 'product',
                ]);
            };

            if($data !== null){
                foreach($data as $el){
                    if($el['type'] === 'product'){
                        $products[] = $el;
                    }
                }
                if($products !== null){
                    if( $viewedItemsQuery->get()->isNotEmpty() ){
                        // Exists id in front and db
                        $existsProductsId = collect($products)->pluck('id')->intersect($viewedItemsQuery->pluck('shop_product_id')->toArray());
    
                        // Not exists ids an db
                        $ProductsIdNews = collect($products)->pluck('id')->diff($viewedItemsQuery->pluck('shop_product_id')->toArray());
    
                        //Update updated_at items whitch exists
                        foreach($existsProductsId as $existsId){
                            ViewdItems::updateOrCreate([
                                'user_id' => auth()->id(),
                                'shop_product_id' => (int) $existsId,
                                'type' => 'product',
                            ],[
                                'updated_at' => Carbon::now()
                            ]);
                    
                        }
    
                        //Deleted Oldest Items
                        if(($countViwedItems + count($ProductsIdNews)) > $maxCountProducts || $countViwedItems === $maxCountProducts ){
                            for($i = 0; $i < count($ProductsIdNews); $i++ ){
                                $oldest[$i]->delete();
                            }

                            foreach($ProductsIdNews as $newProduct){
                                $createItems($newProduct);
                            }
                        }
    
                        //add new items
                        if(($countViwedItems + count($ProductsIdNews)) < $maxCountProducts || $countViwedItems < $maxCountProducts){
                            foreach($ProductsIdNews as $newProduct){
                                $createItems($newProduct);
                            }
                        }
                    } else {
                        foreach($products as $product){
                            $createItems($product, true);
                        }
                    }
                }
            }

            $productsFormated = [];
            $rightLayoutFormatedProducts = [];
            $proudctsHasUser = auth()->user()->hasViewed;

            $productsNewestIds = Product::whereIn('id', $Newest->pluck("shop_product_id")->toArray())->get();
            
            foreach($Newest as $new){
                $productsFormated[] = [
                    'html' => '<div class="w-80 xl:mr-6 mx-3">' . view('includes.products.item.default', ['product' => collect($productsNewestIds)->firstWhere('id', $new['shop_product_id'])])->render() . '</div>',
                    'date' => $new['updated_at']->format('Y-m-d H:i:s')
                ];
            }

            foreach($proudctsHasUser as $viewed){
                $product = Product::where('id', (int) $viewed['shop_product_id'])->first();

                $rightLayoutFormatedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price_default' => $product->getExchangedPrice(true),
                    'price_on_sale' => $product->getExchangedPrice(false),
                    'image' => $product->getImage(),
                    'on_sale' => $product->onSale(),
                    'link' => route('shop.card', ['slug' => $product->slug, 'id' => $product->id]),
                    'date' => $viewed['updated_at']->format('Y-m-d H:i:s'),
                ];
                
            }

            return [
                'status' => 200,
                'data_storage' => $data ?? ($viewedItemsQuery->get() ?? null),
                'formated_data' =>   $productsFormated,
                'is_auth' => true,
                'right_layout_formated_viwed_products' => $rightLayoutFormatedProducts,
                'products' => $products,
                'blogs' => $blogs ,
            ];
        } else {
            $productsFormated = [];
            $rightLayoutFormatedProducts = [];

            foreach($data as $viewed){
                $product = Product::where('id', (int) $viewed['id'])->first();
                $productsFormated[] = [
                    'html' => '<div class="w-80 xl:mr-6 mx-3">' . view('includes.products.item.default', ['product' => $product])->render() . '</div>',
                    'date' => $viewed['date'],
                ];

                $rightLayoutFormatedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price_default' => $product->getExchangedPrice(true),
                    'price_on_sale' => $product->getExchangedPrice(false),
                    'image' => $product->getImage(),
                    'on_sale' => $product->onSale(),
                    'link' => route('shop.card', ['slug' => $product->slug, 'id' => $product->id]),
                    'date' => $viewed['date'],
                ];


            }

            return [
                'status' => 200,
                'data_storage' => $data,
                'formated_data' =>   $productsFormated,
                'right_layout_formated_viwed_products' =>  $rightLayoutFormatedProducts,
                'is_auth' => false,
                'products' => $products,
                'blogs' => $blogs ,
            ];
        }
    } 

    public function calcTotal(Request $request,$id){
        try {
            $defaultComposition = $request->post('defaultCompositions');
            $changeComposition = $request->post('changesCompositions');

            $finalyCompositionFormated = null;
            $finalyComposition = null;

            $defaultCompositionTotal = 0;
            $changedCompositionTotal = 0;
            $total = 0;

            foreach($changeComposition as $value){
                $composition = ProductVariation::where('id', $value['id'])->first();
                $calcTotal = (float)$composition['price'] * (int)$value['Quantity'];
                $finalyCompositionFormated[] = [
                    'id' => (int)$value['id'],
                    'quantity' => (int) $value['Quantity'],
                    'total' => number_format($calcTotal,2),
                    'total_formated' => Currency::format(Currency::exchange($calcTotal)),
                ];
                $finalyComposition[] = [
                    'id' => (int)$value['id'],
                    'quantity' => (int) $value['Quantity'],
                    'total' => number_format($calcTotal,2),
                    'total_formated' => Currency::format(Currency::exchange($calcTotal)),
                ];
                $changedCompositionTotal += $calcTotal;
            }

            foreach($defaultComposition as $value){
                $composition = ProductVariation::where('id', $value['id'])->first();

                $defaultCompositionTotal += (int)$composition['price'] * (int)$value['Quantity'];
            }

            $total = Currency::format(Currency::exchange($changedCompositionTotal + $defaultCompositionTotal)); 

            return [
                'status' => 200,
                'id' => $id,
                'total_products_formated' => $finalyCompositionFormated,
                'total_product' => $finalyComposition,
                'total' => str_replace([',', '.00'], [' ',''], $total),
                'total_mdl' => str_replace(',', '' ,number_format($changedCompositionTotal + $defaultCompositionTotal, 2)),
            ];
        } catch (\Exception $e) {
            Log::error( $e->getTraceAsString(). ' => Error in calcTotal: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error'
            ]);
        }
    }

    public function searchProduct(Request $request){
        $data = $request->post('search');

        $results = Product::whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"".app()->getLocale()."\"'))) LIKE ?", ["%{$data}%"])->get() ?? null;

        $products = null;

        foreach($results->take(2) as $product){
            $products[] = [
                'id' => $product->id,
                'price_default' => $product->getExchangedPrice(true),
                'price_on_sale' => $product->getExchangedPrice(false),
                'on_sale' => $product->onSale(),
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'image'=> $product->getImage(),
                'view_link' => route('shop.card', ['slug' => $product->slug, 'id' => $product->id]),
            ];

        }
        
        return [
            'status' => 200,
            'data' => $results,
            'products' => $products,
            'results' => count($results) ?? 0,
        ];
    }

    public function showProductCard($slug, $id){
        $product = Product::where('id', $id)->first();
        // $user = 

        // $recentlyViewedProducts = viewdItems::where('user_id', $user->id)
        //     ->orderBy('created_at', 'desc')
        //     ->limit(6)
        //     ->get();
    
        // // Если продуктов меньше 6, просто добавляем новый
        // if ($recentlyViewedProducts->count() < 6) {
        //     viewdItems::create([
        //         'user_id' => $user->id,
        //         'product_id' => $product->id
        //     ]);
        // } else {
        //     // Если продуктов уже 6, заменяем самый старый
        //     $oldestProduct = $recentlyViewedProducts->last();
        //     $oldestProduct->update([
        //         'product_id' => $product->id,
        //         'created_at' => now() // Обновляем время, чтобы оно стало актуальным
        //     ]);
        // }

        $rating = Comment::where('product_id', $id)
            ->whereNotNull('rating')
            ->avg('rating');
        $ratingQntyUsers = Comment::where('product_id', $id)
            ->distinct('user_id') // Отбираем только уникальные user_id
            ->count('user_id'); // Считаем количество уникальных пользователей
        

        if(request()->has('page')){
            $page = request()->get('page');
            $type = request()->get('type');
            
            $comments = $this->getCommentsFormated($product->id, $page , $type);

            return [
                'status' => 200,
                'rating' => $rating ?? 0,
                'ratingQntyUsers' =>  $ratingQntyUsers ?? 0,
                'comments' => $comments,
            ];
        }
        $comments = Comment::where('product_id', $product->id)->orderByDesc('created_at')->where('reply_id', null)->where('reply_user_id', null)->paginate(4) ?? null;


        return view('shop.show', [
            'product' => $product,
            'slug' => $slug,
            'rating' => $rating ?? 0,
            'comments' =>  $comments,
            'ratingQntyUsers' =>  $ratingQntyUsers ?? 0,
        ]);
    }

    public function showProduct($id, $itemId = null){
        try {
            $product = Product::findOrFail($id);

            if($itemId !== null){
                $cartItem = CartItem::where('id', (int)$itemId)->first();
            }

            $rating = Comment::where('product_id', $id)
                ->whereNotNull('rating')
                ->avg('rating');
            $ratingQntyUsers = Comment::where('product_id', $id)
                ->distinct('user_id') // Отбираем только уникальные user_id
                ->count('user_id'); // Считаем количество уникальных пользователей
        

            $imagesThumb = null;
            $imagesThumbSm = null;
            $imagesMain = null;
            $images = $product->getMedia("product-images");
            $showMessage = false;

            if($images !== null){
                foreach ($images as $image){
                    $imagesThumb[] = $image->getUrl('thumb-md');
                }
                foreach ($images as $image){
                    $imagesThumbSm[] = $image->getUrl('thumb-sm');
                }
                foreach ($images as $image){
                    $imagesMain[] = $image->getUrl('main');
                }
            }

            $followCode = Cookie::get('follow');
            $follow = Follow::where('code', $followCode)->first();
            if(Follow::where('code', $followCode)->first()){
                $isFollow =  FollowItems::where('follow_id', $follow['id'])->where('shop_product_id', $product['id'])->first() ?? null;
            } else{
                $isFollow = null;
            }
            return [
                'status' => 200,
                'product' => $product ?? null,
                'product_on_sale' => $product->onSale() ? $product->getSaleBadge() : null,
                'product_on_sale_price' => $product->onSale() ? $product->getExchangedPrice(false) : $product->getExchangedPrice() ?? null,
                'product_is_new' => $product->isNew() ?  __('template.new') : null,
                'product_sku' => $product->sku,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'product_images' => $images,
                'product_images_thumb' => $imagesThumb,
                'product_images_thumb_sm' => $imagesThumbSm,
                'product_images_main' => $imagesMain,
                'product_is_follow' => $isFollow,
                'rating' => $rating ?? 0,
                'ratingQntyUsers' =>  $ratingQntyUsers ?? 0,
                'price_default' => $product->getExchangedPrice(true),
                'price_on_sale' => $product->getExchangedPrice(false),
                'on_sale' => $product->onSale(),
            ];
        } catch (\Exception $e) {
            Log::error( $e->getTraceAsString(). ' => Error in showProduct: ' . $e->getMessage() . ' line:' . $e->getLine());
            return [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
        }
    }

    public function index(Request $request): View
    {  
        $params = $request->route()->parameters();

        $segments = explode('/', $params['dynamic']);
        
        /**
         * Get the last segment of the route
         */
        $segment = end($segments);

        /**
         * Check if segment is a category slug
         */
        $category = Category::whereRaw('JSON_SEARCH(slug, "all", ?) IS NOT NULL', [$segment])->first();
        if(null !== $category){
            $query = Product::query()
                ->where('stock_quantity', '>', 0)
                ->whereRaw('stock_quantity > reserved')
                ->where('price', '>', 0)
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('id', $category->id);
                });

            $filters = $this->getFilters($request, $query);
    
            $products = $query->paginate(12);

            $routes = Category::getRouteTranslations($category);
            $request->session()->put('localized_routes', $routes);

            return view('shop.list',[
                "category" => $category,
                "products" => $products,
                'sorting' => $filters['sorting'],
                "attributeColor" => $filters['attributeColor'],
                "attributeSize" => $filters['attributeSize'],
                "sizes" => $filters['sizes'],
                "colors" => $filters['colors'],
                "flowersVariations" => $filters['flowersVariations'],
                "filterFlowers" => $filters['filterFlowers'],
                "minPrice" => $filters['minPrice'],
                "maxPrice" => $filters['maxPrice'],
                "minPriceChanged" => $filters['minPriceChanged'],
                'maxPriceChanged' => $filters['maxPriceChanged'],
                ]
            );
        }

        /**
         * Check if segmant is a product slug
         */
        $product = Product::whereRaw('JSON_SEARCH(slug, "all", ?) IS NOT NULL', [$segment])->first();
        if(null !== $product){
            /**
             * Prepare variations key => data array
             * and array of attributes keys
             * to update data on page after options change
             */
            $attrKeys = [];
            $variations = [];
            if($product->type === Product::VARIABLE){
                $attrKeys = $product->getAttrKeysArray();
                foreach($product->variations as $variation){
                    $variations[$variation->key] = [
                        'id' => $variation->id,
                        'name' => $variation->name,
                        'price' => $variation->price, // TODO: make a get price function like for product
                        'gallery' => [
                            'nav' => ViewFacade::make('gallery.nav', [
                                'media' => $variation->getMedia("variation-images"), 
                                'alt' => $variation->name
                            ])
                            ->render(),
                            'viewbox' => ViewFacade::make('gallery.viewbox', [
                                'media' => $variation->getMedia("variation-images"), 
                                'alt' => $variation->name
                            ])
                            ->render()
                        ]
                    ];
                }
            }
            $attrKeys = json_encode($attrKeys);
            $variations = json_encode($variations);

            /**
             * Get products from same category
             */
            $related = $product->mainCategory->products()
            ->where('is_visible', true)
            ->where('id', '!=', $product->id)
            ->take(16)
            ->get();

            $routes = Product::getRouteTranslations($product);
            $request->session()->put('localized_routes', $routes);

            return view('shop.show',
                compact(
                    'product',
                    'attrKeys',
                    'variations',
                    'related'
                )
            );
        }

        abort(404);
    }

    public function home(Request $request): View
    {

        /**
         * Get shop products
         */
        $query = Product::query()->where('stock_quantity', '>', 0)
                                ->whereRaw('stock_quantity > reserved')
                                ->where('price', '>', 0);

        $filters = $this->getFilters($request, $query);


        $products = $query->paginate(12);
        return view('shop.home',
            [
                "products" => $products,
                'sorting' => $filters['sorting'],
                "attributeColor" => $filters['attributeColor'],
                "attributeSize" => $filters['attributeSize'],
                "sizes" => $filters['sizes'],
                "colors" => $filters['colors'],
                "flowersVariations" => $filters['flowersVariations'],
                "filterFlowers" => $filters['filterFlowers'],
                "minPrice" => $filters['minPrice'],
                "maxPrice" => $filters['maxPrice'],
                "minPriceChanged" => $filters['minPriceChanged'],
                'maxPriceChanged' => $filters['maxPriceChanged'],
            ]
        );
    }

    public function sale(Request $request): View
    {
        $sorting = $request->get('sorting', 'latest');

        $query = Product::query()
                        ->where('is_visible', true)
                        ->whereHas('discounts');

        /**
         * Apply sorting to the query
         */
        switch ($sorting) {
            case 'latest':
                $query->orderBy('published_at', 'desc');
                break;
            case 'low_to_high':
                $query->orderBy('base_price', 'asc');
                break;
            case 'high_to_low':
                $query->orderBy('base_price', 'desc');
                break;
        }

        $products = $query->paginate(12);

        return view('shop.sale',
            compact(
                'products',
                'sorting'
            )
        );
    }

    public function toggleFilter(Request $request){
        $filter = $request->input('open');
        
        session()->put('filter', $filter);

        return [
            'status' => 200,
            'open' => $filter
        ];
    }

    private function getFilters($request, $query){
        $sorting = $request->get('sorting', 'latest');

        /**
         * Get attribute color sort
         */
            $attributeColor = Attribute::where('key', 'color')->first();
            $colors = $request->get('color') ? explode(',', $request->get('color')) : [];
            $this->getFilterAttr($colors, $attributeColor, $query);
        /**
         * Get attribute Size sort
         */
            $attributeSize = Attribute::where('key', 'size')->first();
            $sizes = $request->get('size') ? explode(',', $request->get('size')) : [];
    
            $this->getFilterAttr($sizes, $attributeSize,$query);

        /**
        * Get name flowers sort
        */
            $flowersVariations = Product::whereHas('variations')->get();
            $flowers = $request->get('flower') ? explode(',', $request->get('flower')) : [];
            
            $filterFlowers = $flowersVariations->whereIn('slug', $flowers)->pluck('id')->toArray();
            $flowerComposition = ProductComposition::whereIn('shop_product_id', $filterFlowers)->pluck('shop_product_variation_id')->toArray();

            if(!empty($flowers)){
                $query->whereHas('compositionList', function($query) use ($flowerComposition) {
                    $query->whereIn('shop_product_variation_id', $flowerComposition);
                });
            }
            
        /**
         * Get price sort
         */
        if (\Auth::guard('client')->check()){
            $maxPrice =  Currency::exchange($query->max('price'));
            $minPrice =  Currency::exchange($query->min('price'));  
        } else{
            $maxPrice =  Currency::exchange($query->max('default_price'));
            $minPrice =  Currency::exchange($query->min('default_price'));
        }
            
            $minPriceChanged =  round($request->get('min', $minPrice));
            $maxPriceChanged =   round($request->get('max', $maxPrice));
        /**
         * Apply sorting to the query
         */
        switch ($sorting) {
            case 'latest':
                $query->orderBy('id', 'desc');
                break;
            case 'low_to_high':
                $query->orderBy('price', 'asc');
                break;
            case 'high_to_low':
                $query->orderBy('price', 'desc');
                break;
        }

        // Apply sort to price
        if($minPriceChanged !== null || $maxPriceChanged !== null){
            if(\Auth::guard('client')->check()){
                $query->whereBetween('price', [Currency::exchange($minPriceChanged), Currency::exchange($maxPriceChanged)])->get();
            } else {
                $query->whereBetween('default_price', [Currency::exchange($minPriceChanged), Currency::exchange($maxPriceChanged)])->get();
            }
        }

        return [
            'query' => $query,
            'sorting' => $sorting,
            'maxPriceChanged' => $maxPriceChanged,
            'minPriceChanged' => $minPriceChanged,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'attributeColor' => $attributeColor,
            'attributeSize' => $attributeSize,
            'colors' => $colors,
            'sizes' => $sizes,
            'flowersVariations' => $flowersVariations,
            'filterFlowers' =>$filterFlowers,
        ];
    }

    private function getFilterAttr($names, $attr, $query){ 

        $filter = $attr->managedAttributeValues->filter(function ($value) use($names){
            return in_array($value['attr_key'], $names);
        });

        $variationsAttr = $filter->map(function($value){
            return $value->productsVariations;
        })->filter(function($value){
            return $value->isNotEmpty();
        })->collapse();
        
        $variations = array_map(function($item) {
            return $item['shop_variation_id']; 
        }, $variationsAttr->toArray());
        
        if(!empty($sizes)){
            $query->whereHas('compositionList', function($query) use($variations){
                $query->whereIn('shop_product_variation_id', $variations);
            });
        }

    }

    private function formatedDataProducts($productsGet): array {
        $products = [];
        $productsFormated = [];

        foreach($productsGet as $productGet){
            $productsFormated[] = Product::where('id', $productGet['shop_product_id'])->first();
        }

        $followCode = Cookie::get('follow');
        $follow = Follow::where('code', $followCode)->first();
        
        foreach($productsFormated as $product){
            if(Follow::where('code', $followCode)->first()){
                $isFollow = optional(FollowItems::where('follow_id', $follow['id'])->where('shop_product_id', $product['id'])->first())->exists ?? false;
            } else{
                $isFollow = false;
            }
    
            $products[] = [
                'id' => $product->id,
                'price_default' => $product->getExchangedPrice(true),
                'price_on_sale' => $product->getExchangedPrice(false),
                'on_sale' => $product->onSale() ?? false,
                'is_follow' => $isFollow,
                'is_new' =>  $product->isNew() ?  __('template.new') : false,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'image'=> $product->getImage(),
                'view_link' => route('shop.card', ['slug' => $product->slug, 'id' => $product->id]),
            ];
        }

        return $products;
    }

}

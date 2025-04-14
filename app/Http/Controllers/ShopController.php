<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\viewdItems;
use Illuminate\View\View;
use App\Models\Shop\Order;
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

        return view('shop.show', [
            'product' => $product,
        ]);
    }

    public function showProduct($id, $itemId = null){
        try {
            $product = Product::findOrFail($id);

            if($itemId !== null){
                $cartItem = CartItem::where('id', (int)$itemId)->first();
            }

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
                ->where('is_visible', true)
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
        $query = Product::query()->where('is_visible', true);

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
            $flowersVariations = Product::whereHas('variations')->where('is_visible', true)->get();
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
            $maxPrice =  Currency::exchange($query->max('base_price'));
            $minPrice =  Currency::exchange($query->min('base_price'));

            $minPriceChanged =  round($request->get('min', $minPrice));
            $maxPriceChanged =   round($request->get('max', $maxPrice));
            
            
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

        // Apply sort to price
        if($minPriceChanged !== null || $maxPriceChanged !== null){
            $query->whereBetween('base_price', [  Currency::exchangeToMdl($minPriceChanged),  Currency::exchangeToMdl($maxPriceChanged)])->get();
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


}

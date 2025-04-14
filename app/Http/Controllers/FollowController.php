<?php

namespace App\Http\Controllers;

use Livewire\Livewire;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Models\Shop\Category;
use App\Models\Shop\Follow;
use App\Models\Shop\FollowItems;
use App\Models\Shop\ProductVariation;
use Illuminate\Support\Facades\Cookie;

class FollowController extends Controller
{
    public function view(): View
    {
        $follow = null;

        if(session()->has('follow')){
            $followData = session()->get('follow');
            $follow = Follow::where('code', $followData['code'])->first();
        }
        return view('follow.index',
            compact(
                'follow',
            )
        );
    }

    public function add(Request $request)
    {
        $post = $request->post();

        if(!isset($post['product'])){
            return [
                'status' => 400,
                'message' => trans('template.required_data_missing')
            ];
        }

        $product = Product::find($post['product']);
        if(!$product){
            return [
                'status' => 400,
                'message' => trans('template.non_existing_product')
            ];
        }
        $variable = $product->type === Product::VARIABLE;
        if($variable){
            $variation = ProductVariation::find($post['variation']);
            if(!$variation){
                return [
                    'status' => 400,
                    'message' => trans('template.non_existing_variation')
                ];
            }
        }

        /**
         * If there is no follow code in session - create new follow
         * GetFollow middleware will check cookies and will add follow to session in case it exists
         */
        if(!session()->has('follow')){
            $follow = $this->newFollow();
        } else {
            $followData = session()->get('follow');
            $follow = Follow::where('code', $followData['code'])->first();
            /**
             * In case the follow was not found by code from session
             * create a new one
             */
            if(!$follow){
                $follow = $this->newFollow();
            }
        }
        
        $item = FollowItems::where([
            'follow_id' => $follow->id,
            'shop_product_id' => $product->id,
                'shop_product_variation_id' => $variable ? $post['variation'] : null,
        ])->first();

        if(!$item){
            $item = FollowItems::create([
                'follow_id' => $follow->id,
                'shop_product_id' => $product->id,
                'shop_product_variation_id' => $variable ? $post['variation'] : null,
                'qty' => $post['quantity'],
            ]);
        } else {
            // Just add new amount to existing quantity
            $item->delete();
        }

        $followData = $this->recalcFollow($follow);
        return [
            'status' => 200,
            'message' => trans('template.successfully_added'),
            'follow' => $followData,
        ];
    } 

    public function update(Request $request)
    {
        $post = $request->post();

        if(!isset($post['item'])){
            return [
                'status' => 400,
                'message' => trans('template.required_data_missing')
            ];
        }

        $item = FollowItems::find($post['item']);
        if(!$item){
            return [
                'status' => 400,
                'message' => trans('template.non_existing_item')
            ];
        }

        $followData = $this->recalcFollow($item->follow);

        $itemData = [
            'id' => $item->id,
        ];

        return [
            'status' => 200,
            'message' => trans('template.successfully_updated'),
            'follow' => $followData,
            'item' => $itemData
        ];
    }

    public function remove(Request $request)
    {
        $post = $request->post();
        if(!isset($post['item'])){
            return [
                'status' => 400,
                'message' => trans('template.required_data_missing')
            ];
        }

        $item = FollowItems::find($post['item']);
        if(!$item){
            return [
                'status' => 400,
                'message' => trans('template.non_existing_item')
            ];
        }
        $follow = $item->follow;

        $itemData = [
            'id' => $item->id
        ];

        $item->delete();

        $followData = $this->recalcFollow($follow);

        return [
            'status' => 200,
            'message' => trans('template.successfully_deleted'),
            'follow' => $followData,
            'item' => $itemData,
            'empty' => $followData['totalItems'] === 0 ? view('includes.empty-cart')->render() : ''
        ];
    }

    protected function newFollow()
    {
        // TODO: Verify if code isn`t allready exists
        $code = Str::random(32);

        $follow = Follow::create([
            'code' => $code
        ]);

        if($follow){
            Cookie::queue('follow', $code); 
            session()->put('follow', [
                'code' => $follow->code,
                'totalItems' => $follow->total_items,
            ]);
        }

        return $follow;
    }

    /**
     * Update Follow data in session with refreshed data
     */
    protected function recalcFollow($follow)
    {
        $follow = $follow->refresh();

        $totalItems = 0;

        foreach($follow->items as $item){
            $totalItems += $item->qty;
        }

        $follow->update([
            'total_items' => $totalItems
        ]);

        $followData = [
            'code' => $follow->code,
            'totalItems' => $follow->total_items,
        ];
        session()->put('follow', $followData);

        return $followData;
    }
}
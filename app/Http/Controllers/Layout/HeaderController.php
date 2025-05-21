<?php

namespace App\Http\Controllers\Layout;

use App\Http\Controllers\Controller;
use App\Models\CategoryCustom;
use App\Models\Navigation\Menu;
use App\Models\Shop\Cart;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HeaderController extends Controller
{
    /**
     * Get all data needed for the header
     *
     * @return array
     */
    public function getHeaderData()
    {
        return [
            'menu' => $this->getHeaderMenu(),
            'cart' => $this->getCart(),
            'categories' => $this->getCategoriesTree()
        ];
    }

    /**
     * Get optimized category tree structure with caching
     *
     * @return array
     */
    protected function getCategoriesTree()
    {
        return Cache::remember('categories_tree', 3600, function () {
            // Получаем родительские категории одним запросом
            $parentCategories = CategoryCustom::whereNull('parent_code')
                ->where('name', '!=', null)
                ->orderBy('some_order')
                ->get();

            // Получаем все дочерние категории одним запросом с индексацией по parent_code
            $childrenByParent = CategoryCustom::whereNotNull('parent_code')
                ->orderBy('some_order')
                ->get()
                ->groupBy('parent_code');

            // Подготавливаем структуру без лишних преобразований
            $tree = [];
            foreach ($parentCategories as $parent) {
                $firstLevelChildren = $childrenByParent->get($parent->code, collect([]));
                
                $children = $firstLevelChildren->map(function ($child) use ($childrenByParent) {
                    return [
                        'category' => $child,
                        'children' => $childrenByParent->get($child->code, collect([]))->all()
                    ];
                })->all();

                $tree[$parent->code] = [
                    'category' => $parent,
                    'children' => $children
                ];
            }

            return [
                'tree' => $tree,
                'parentCategories' => $parentCategories,
                'childCategories' => $childrenByParent->flatten(1)
            ];
        });
    }

    /**
     * Get header menu with caching
     *
     * @return \App\Models\Navigation\Menu|null
     */
    protected function getHeaderMenu()
    {
        return Cache::remember('header_menu', 3600, function () {
            return Menu::where('key', 'header-nav')
                ->with('items.page')  // Предполагая, что есть связь с page
                ->first();
        });
    }

    /**
     * Get cart data
     *
     * @return \App\Models\Shop\Cart|null
     */
    protected function getCart()
    {
        if (!session()->has('cart')) {
            return null;
        }

        $cartData = session()->get('cart');
        return Cart::with('order')  // Предполагая, что есть связь с order
            ->where('code', $cartData['code'])
            ->first();
    }
} 
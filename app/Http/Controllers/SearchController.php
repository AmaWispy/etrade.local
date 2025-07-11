<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use App\Models\Page\Service;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function posts(Request $request): View
    {
        $query = $this->prepareSQ($request->input('sq'));

        $result = Post::whereRaw('JSON_SEARCH(LOWER(title), "all", :value) IS NOT NULL', ['value' => '%' . $query . '%'])
                        ->paginate(10);
        /**
         * Get list of categories
         */
        $categories = Category::latest()->take(5)->get();

        return view('search.posts',
            compact(
                'query',
                'result',
                'categories'
            )
        );
    }

    public function services(Request $request): View
    {
        $query = $this->prepareSQ($request->input('sq'));

        $result = Service::whereRaw('JSON_SEARCH(LOWER(title), "all", :value) IS NOT NULL', ['value' => '%' . $query . '%'])
                        ->paginate(10);
        /**
         * Get list of services
         */
        $services = Service::latest()->take(5)->get();

        return view('search.services',
            compact(
                'query',
                'result',
                'services'
            )
        );
    }

    public function products(Request $request): View
    {
        $sq = $this->prepareSQ($request->input('sq'));

        $sorting = $request->get('sorting', 'latest');

        $query = Product::query()
                        ->where('is_visible', true)
                        ->where(function($q) use ($sq) {
                            $q->whereRaw('JSON_SEARCH(LOWER(name), "all", ?) IS NOT NULL', ['%' . $sq . '%'])
                              ->orWhereRaw('LOWER(sku) LIKE ?', ['%' . strtolower($sq) . '%']);
                        });

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

        $result = $query->paginate(12);
        
        return view('search.products', compact(
            'sq',
            'result',
            'sorting'
        ));
    }

    /**
     * Remove trailing spaces and convert to lowercase
     * It is not necessary additional sanitization
     * Laravel will take care about it while escaping params
     */
    protected function prepareSQ($sq)
    {
        return strtolower(trim($sq));
    }
}

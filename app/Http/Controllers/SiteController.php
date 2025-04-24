<?php

namespace App\Http\Controllers;

//use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\Shop\Brand;
//use App\Models\Blog\Post;
//use App\Models\Widgets\WidgetGroup;
//use App\Models\Widgets\TextWidget;
use App\Models\Carousel\Carousel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
// use App\Services\EmailService;

class SiteController extends Controller
{
    // protected $emailService;

    // public function __construct(EmailService $emailService)
    // {
    //     $this->emailService = $emailService;
    // }

    public function home(Request $request): View
    {
        /**
         * Get carousels
         */
        $carousels = Carousel::where('is_active', true)->get();
        $carousels = $carousels->mapWithKeys(function ($model) {
            return [$model->key => $model];
        });

        /**
         * Promo products sale

         */
        // $promo = Product::query()
        //     ->where('is_visible', true)
        //     // ->inRandomOrder()
        //     ->take(20)
        //     ->get()
        //     ->filter(fn($product)=> $product->onSale());

        /**
         * New arrivals
         * Just a random collection for now
         */
        // $newArrivals = Product::query()
        //     ->where('is_visible', true)
        //     // ->inRandomOrder()
        //     ->take(9)
        //     ->get();


        /**
         * Products
         */
        $products = Product::query()
            ->where('stock_quantity', '>', 0)
            ->whereRaw('stock_quantity > reserved')
            // ->inRandomOrder()
            ->take(16)
            ->get();

        /**
         * Get brands list
         */
        $brands = Brand::all();

        /**
         * Prepare localized routes for current page
         * to use for redirect after language was switched
         */
        $localizedRoutes = [];
        foreach(config('app.locales') as $locale){
            if($locale === config('app.default_locale')){
                // Do not add locale prefix for default language
                $localizedRoutes[$locale] = '/';
            } else {
                $localizedRoutes[$locale] = $locale;
            }
        }
        $request->session()->put('localized_routes', $localizedRoutes);
        
        return view('home',
            compact(
                'carousels',
                'products',
            )
        );
    }

    public function test(){
        return \App\Services\ApiService::fetchAndStoreClients();
    }

    // public function testSmtp(){
    //     $to = 'vlad@garm.email';
    //     $subject = 'Test Email';
    //     $content = 'This is a test email message.';

    //     $result = $this->emailService->sendEmail($to, $subject, $content);

    //     if ($result) {
    //         return "Email sent successfully!";
    //     } else {
    //         return "Failed to send email!";
    //     }
    // }
}

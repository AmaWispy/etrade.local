<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\UesfulPagesController;
use App\Http\Controllers\Payments\PaynetController;

//Route::get('form', Form::class);
Route::get('/test-smtp', [SiteController::class, 'testSmtp'])
        ->name('home.test-smtp');

// Default route for home page
Route::get('/', [SiteController::class, 'home'])
        ->name('home.default');

// Run some artiasan commands from browser
Route::group(['prefix' => 'artisan'], function () {
    Route::get('optimize-clear', function() {
        Artisan::call('optimize:clear');
        return 'Application cache has been cleared';
    });
    Route::get('storage-link', function() {
        Artisan::call('storage:link');
        return 'Symlink to storage created';
    });
});

// Switchers
Route::post('/set-locale', [LocaleController::class, 'update'])
        ->name('locale.set');
Route::post('/set-currency', [CurrencyController::class, 'update'])
        ->name('currency.set');

// Search
Route::group(['prefix' => 'search'], function () {
    Route::get('posts', [SearchController::class, 'posts'])
            ->name('search.posts');
    Route::get('services', [SearchController::class, 'services'])
            ->name('search.services');
    Route::get('products', [SearchController::class, 'products'])
            ->name('search.products');
});

// Localized route for home page Ex.: /ru, /ro etc.
Route::get('/{locale}', [SiteController::class, 'home'])
        ->where('locale', '[a-z]{2}')
        ->name('home.localized');

// Localized routes
Route::prefix('{locale}')->group(function () {
    // Blog, categories, posts
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', [PageController::class, 'blog'])
            ->name('blog.home.localized');
        Route::get('{category}', [PageController::class, 'list'])
            ->name('blog.list.localized');
        Route::get('{category}/{post}', [PageController::class, 'show'])
            ->name('blog.show.localized');
    });
    
    // Generate set of routes for each locale with translated prefix
    foreach(config('app.locales') as $locale){
        // Do not register prefixed route for default locale
        if($locale !== config('app.default_locale')){
            Route::group(['prefix' => trans('routes.shop', [], $locale)], function () {
                Route::get('/', [ShopController::class, 'home'])
                    ->name('shop.home.localized');
                Route::get('/sale', [ShopController::class, 'sale'])
                    ->name('shop.sale.localized');
                Route::get('{dynamic?}', [ShopController::class, 'index'])->where('dynamic', '.*');
            });
        }
    }
    
})->where('locale', '[a-z]{2}');

// Blog, categories posts (for default locale)
Route::prefix('blog')->group(function () {
    Route::get('/', [PageController::class, 'blog'])
        ->name('blog.home');
    // Route::get('{category}', [PageController::class, 'list'])
    //     ->name('blog.list');
    Route::get('/filter-comment', [PageController::class, 'filtersBlog'])
    ->name('blog.filter-comment');
    Route::get('/{slug}', [PageController::class, 'show'])
        ->name('blog.show');
});

// Shop
Route::group(['prefix' => trans('routes.shop', [], config('app.default_locale'))], function () {
    Route::get('/', [ShopController::class, 'home'])
        ->name('shop.home');
    Route::get('/sale', [ShopController::class, 'sale'])
        ->name('shop.sale');
        Route::get('/{dynamic?}', [ShopController::class, 'index'])->where('dynamic', '.*');
        
});

//ShopController
Route::post('/filter', [ShopController::class, 'toggleFilter'])
    ->name('shop.filter');
Route::get('/product/{id}', [ShopController::class, 'showProduct'])
    ->name('shop.show');
Route::get('/product/{slug}/{id}', [ShopController::class, 'showProductCard'])
    ->name('shop.card');
// Route::get('/product/{id}/{item}', [ShopController::class, 'showProduct']);
Route::post('/product-composition/{id}', [ShopController::class, 'calcTotal'])
    ->name('shop.calcTotal');
Route::post('/product/search', [ShopController::class, 'searchProduct'])
    ->name('shop.search.product');


// Cart
Route::group(['prefix' => 'cart', 'middleware' => ['auth']], function () {
    Route::get('/show', [CartController::class, 'show'])
        ->name('cart.show');
    Route::get('/view', [CartController::class, 'view'])
        ->name('cart.view');
    Route::post('/add', [CartController::class, 'add'])
        ->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])
        ->name('cart.update');
    Route::post('/card', [CartController::class, 'card'])
        ->name('cart.card');
    Route::post('/remove', [CartController::class, 'remove'])
        ->name('cart.remove');
});

//Viewed Products/Blogs & Comments
Route::post('/viewed-items', [ShopController::class, 'viwedItems'])
    ->name('show.viewed.items');
Route::post('/send-comment', [PageController::class, 'comment'])
->name('comment.store');

// Follow
Route::group(['prefix' => 'follow', 'middleware' => ['auth']], function () {
    Route::get('/view', [FollowController::class, 'view'])
        ->name('follow.view');
    Route::post('/add', [FollowController::class, 'add'])
        ->name('follow.add');
    Route::post('/update', [FollowController::class, 'update'])
        ->name('follow.update');
    Route::post('/remove', [FollowController::class, 'remove'])
        ->name('follow.remove');
});

//Auth
Route::group(['prefix' => 'auth'], function () {
    Route::get('/view', [AuthController::class, 'view'])
        ->name('auth.index');     
});
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Account 
Route::group(['prefix' => __('template.account'), 'middleware' => ['auth']], function(){
    Route::get('/view', [AccountController::class, 'view'])
        ->name('account.index');
    Route::post('/update-password', [AccountController::class, 'updatePassword'])
    ->name('account.update.password'); 
});

// Checkout
Route::group(['prefix' => 'checkout'], function () {
    Route::get('/', [CheckoutController::class, 'index'])
        ->name('checkout.index');
    Route::post('/save', [CheckoutController::class, 'save'])
        ->name('checkout.save');
    Route::post('/detect-shipping-zone', [CheckoutController::class, 'detectShippingZone'])
        ->name('checkout.detect-shipping-zone');
    Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])
        ->name('checkout.calculate-shipping');
    Route::post('/calculate-shipping-delivery', [CheckoutController::class, 'calculateShippingDelivery'])
        ->name('checkout.calculate-shipping-delivery');
    Route::post('/calculate-fixed-time', [CheckoutController::class, 'calculateFixedTime'])
        ->name('checkout.calculate-fixed-time');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('checkout.place-order');
    Route::get('/result/{cart_code}', [CheckoutController::class, 'result'])
        ->name('checkout.result');
});

// Payments
Route::prefix('payments')->group(function () {
    Route::post('/paynet/callback', [PaynetController::class, 'callback'])
        ->name('paynet.callback');
});

// Localized pages
Route::get('{locale}/{page}/{subs?}', [PageController::class, 'index'])
    ->where([
        'locale' => '[a-z]{2}',
        'page' => '^(((?=(?!admin))(?=(?!\/)).))*$', 
        'subs' => '.*'
    ])
    ->name('page.localized');


// Pages (for default locale)
Route::get('{page}/{subs?}', [PageController::class, 'index'])
    ->where([
        'page' => '^(((?=(?!admin))(?=(?!\/)).))*$', 
        'subs' => '.*'
    ])
    ->name('page.default');

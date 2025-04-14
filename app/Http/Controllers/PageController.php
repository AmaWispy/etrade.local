<?php

namespace App\Http\Controllers;

use LengthException;
use App\Models\Comment;
use App\Models\Blog\Post;
use App\Models\Page\Page;
use Illuminate\View\View;
use App\Models\CommentsType;
use App\Models\Page\Project;
use App\Models\Page\Service;
use Illuminate\Http\Request;
use App\Models\Blog\Category;
use Illuminate\Support\Carbon;
use App\Models\Carousel\Carousel;
use Carbon\Traits\ToStringFormat;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function index(Request $request): View
    {   
        $slug = $request->route('page');
        /**
         * Get page by slug
         */
        $page = Page::whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $slug])
                        ->firstOrFail();
        /**
         * Prepare localized routes for current page
         * to use for redirect after language was switched
         */
        $localizedRoutes = [];
        $slugs = $page->getTranslations('slug'); 
        foreach($slugs as $locale => $slug){
            if($locale === config('app.default_locale')){
                // Do not add locale prefix for default language
                $localizedRoutes[$locale] = $slug;
            } else {
                $localizedRoutes[$locale] = $locale . DIRECTORY_SEPARATOR . $slug;
            }
        }
        $request->session()->put('localized_routes', $localizedRoutes);
            
        /**
         * If is set default template, or there is no special action set for page
         * render template
         */
        if($page->template === 'default' || !method_exists($this, $page->template)){
            return view('pages.'.$page->template,
                compact(
                    'page'
                )
            );
        }

        /**
         * In case, when template name can be called as action
         * call the action
         * Special for pages with custom templates, which require additional data
         * Ex. News, Services, Shop Page, etc.
         */
        return $this->callAction($page->template, [$request, $page]);
    }

    /**
     * List blog posts
     */
    public function list(Request $request) : View
    {
        $slug = $request->route('category');
        $category = Category::whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $slug])
                        ->firstOrFail();

        /**
         * Prepare localized routes for current category
         * to use for redirect after language was switched
         */
        $localizedRoutes = [];
        $slugs = $category->getTranslations('slug'); 
        foreach($slugs as $locale => $slug){
            if($locale === config('app.default_locale')){
                // Do not add locale prefix for default language
                $localizedRoutes[$locale] = 'blog' . DIRECTORY_SEPARATOR . $slug;
            } else {
                $localizedRoutes[$locale] = $locale . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR . $slug;
            }
        }
        $request->session()->put('localized_routes', $localizedRoutes);

        /**
         * Get Blog Posts
         */
        $posts = $category
            ->posts()
            ->orderBy("published_at", "DESC")
            ->paginate(10);

        /**
         * Get list of categories
         */
        $categories = Category::latest()->take(10)->get();

        return view('pages.blog-list',
            compact(
                'category',
                'posts',
                'categories'
            )
        );
    }

    /**
     * Coment on blog post / product 
     */
    public function comment(Request $request) {
        $comment = $request->post();
        
        if($comment['type'] === 'blog' || $comment['type'] === 'product'){
            if($comment['type'] === 'product' && $comment['comment_id'] === null && $comment['comment_reply_user_id'] === null){
                Comment::updateOrCreate([
                    'user_id' => auth()->user()->id,
                    'product_id' => $comment['product_id'] ?? null,
                    'type_id' => CommentsType::where('name', $comment['type'])->first()->id,
                ],[
                    'rating' => $comment['rating'],
                    'content' => strip_tags($comment['message']),
                ]);
            } else {
                Comment::create([
                    'user_id' => auth()->user()->id,
                    'type_id' => CommentsType::where('name', $comment['type'])->first()->id,
                    'blog_id' => $comment['blog_id'] ?? null,
                    'product_id' => $comment['product_id'] ?? null,
                    'reply_id' => $comment['comment_id'] ?? null,
                    'reply_user_id' => $comment['comment_reply_user_id'] ?? null,
                    'content' => strip_tags($comment['message']),
                ]);
            }

            return [
                'status' => 200,
                'comment' => $comment,
                'comments' => $this->getCommentsFormated($comment['blog_id'] ?? $comment['product_id'], null, (isset($comment['blog_id']) ? 'blog' : 'product' )),
            ];
        } else{
            return [
                'status' => 500,
                'message' => 'Comment type not found',
            ];
        }
    }


    /**
     * Blog main page
     */
    public function blog() : View
    {
        /**
         * Get Blog Posts
         */
        $posts = Post::query()
            ->orderBy("published_at", "DESC")
            ->paginate(6);
        
        $tags = Post::pluck('tags')->flatMap(fn($tag) => $tag)->unique()->toArray();

        /**
         * Get list of categories
         */
        $categories = Category::latest()->take(10)->get();
        $latestPostsBlog = Post::orderBy('created_at', 'desc')->take(3)->get();
        $archives = null;
        foreach ($posts as $post ) {
            $archives = $posts->map(function ($post) {
                return Carbon::parse($post['published_at'])->format('Y M');
            })->countBy();;
        }
        return view('pages.blog',
            compact(
                'posts',
                'categories',
                'archives',
                'tags',
                'latestPostsBlog'
            )
        );
    }

    /**
     * Filter blog post
     */
    public function filtersBlog(Request $request): array {
        $tags = array_filter(explode(',', $request->get('tags', ''))); 
        $posts = Post::query()->orderBy("published_at", "DESC");
        $locale = app()->getLocale();
    
        if ($request->has('search')){
            $posts->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.\"".app()->getLocale()."\"'))) LIKE ?", ["%{$request->get('search')}%"]);
        }

        if ($request->has('archives') && $request->get('archives') !== 'NONE') {
            $archives = Carbon::createFromFormat('Y M', $request->get('archives'));
            $posts->whereYear('published_at', $archives->year)
                ->whereMonth('published_at', $archives->month);
        }
    
        if (!empty($tags)) {
            $posts->where(function ($query) use ($tags, $locale) {
                foreach ($tags as $tag) {
                    $query->orWhereJsonContains("tags->$locale", $tag);
                }
            });
        }
    
        $result = $posts->get();

        $html = view('includes.layout.blog.blogs', [
            'posts' => $result,
        ])->render();
    
        return [
            'status' => 200,
            'tags' => $tags,
            'archives' => $archives->month ?? null,
            'posts' => $result,
            'html' => $html,
        ];
    }
    

    /**
     * Show blog post
     */
    public function show(Request $request) : View|array
    {
        $slug = $request->route('slug');

        $post = Post::whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $slug])
            ->firstOrFail();
        
        // If has comments to load 
        if(request()->has('page')){
            $page = $request->query('page');
            $type = $request->query('type');
            
            $comments = $this->getCommentsFormated($post->id, $page , $type);

            return [
                'status' => 200,
                'comments' => $comments,
                'page' => $page,
            ];
        }
        
        //Update Countity Views
        $post->update([
            'viewed' => $post->viewed + 1,
        ]);

        /**
         * Prepare localized routes for current category
         * to use for redirect after language was switched
         */
        $localizedRoutes = [];
        $postSlugs = $post->getTranslations('slug'); 
        foreach($postSlugs as $locale => $key){
            /**
             * TEMP FIX: Replace unexisting locales with default
             * Should be handled cases when some translation were not added
             */

            if(!isset($postSlugs[$locale])){
                $postSlugs[$locale] = $postSlugs[config('app.default_locale')];
            }
            // END FIX
            
            if($locale === config('app.default_locale')){
                // Do not add locale prefix for default language
                $localizedRoutes[$locale] = 'blog' . DIRECTORY_SEPARATOR . $postSlugs[$locale];
            } else {
                $localizedRoutes[$locale] = $locale . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR . $postSlugs[$locale];
            }
        }

        $request->session()->put('localized_routes', $localizedRoutes);
        
        /**
         * Get list of latest posts blog post
         */
        $relatedPosts = null;
        $tags = $post->tags ;
        $tagsAll = $post->getTranslations('tags', ['en', 'ru', 'ro']);
        $comments = Comment::where('blog_id', $post->id)->orderByDesc('created_at')->where('reply_id', null)->where('reply_user_id', null)->paginate(4) ?? null;

        if ($post->tags !== null) {
            $locale = app()->getLocale();

            $relatedPosts = Post::where(function ($query) use ($locale, $tagsAll) {
                foreach(['en','ru','ro'] as $lang){
                    foreach ($tagsAll[$lang] as $tag) {
                        $query->orWhereJsonContains("tags->$lang", $tag);
                    }
                }
            })->distinct()->get();

        }

        $archives = null;

        $latestPostsBlog = Post::orderBy('created_at', 'desc')->take(3)->get();
        return view('pages.blog-show',
            compact(
                'post',
                'comments',
                'tags',
                'relatedPosts',
                'latestPostsBlog',
                'slug'
            )
        );
    }



    public function about(Request $request, $page) : View
    {
        $team = Carousel::getByKey('team');

        return view('pages.about',
            compact(
                'page',
                'team'
            )
        );
    }

    public function news(Request $request, $page) : View
    {
        /**
         * Get Blog Posts
         */
        $posts = Post::query()
            ->where("blog_category_id", 1) // 1 - news
            ->orderBy("published_at", "DESC")
            ->paginate(10);
        
        /**
         * Get list of categories
         */
        $categories = Category::latest()->take(5)->get();

        return view('pages.blog',
            compact(
                'page',
                'posts',
                'categories'
            )
        );
    }

    public function services(Request $request, $page) : View
    {
        $subs = $request->route('subs');
        
        if(null !== $subs){
            $service = Service::whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $subs])
                        ->firstOrFail();
            
            /**
             * Prepare localized routes for current page
             * to use for redirect after language was switched
             */
            $localizedRoutes = [];
            $pageSlugs = $page->getTranslations('slug'); 
            $serviceSlugs = $service->getTranslations('slug'); 
            foreach($pageSlugs as $locale => $pageSlug){
                if($locale === config('app.default_locale')){
                    // Do not add locale prefix for default language
                    $localizedRoutes[$locale] = $pageSlug . DIRECTORY_SEPARATOR . $serviceSlugs[$locale];
                } else {
                    $localizedRoutes[$locale] = $locale . DIRECTORY_SEPARATOR . $pageSlug . DIRECTORY_SEPARATOR . $serviceSlugs[$locale];
                }
            }
            $request->session()->put('localized_routes', $localizedRoutes);

            /**
             * Get list of other services
             */
            $services = Service::latest()->take(5)->get()->except($service->id);

            return view('pages.service-show',
                compact(
                    'page',
                    'service',
                    'services'
                )
            );
        }

        /**
         * Get Services
         */
        $services = Service::query()
            ->where("is_active", 1)
            ->orderBy("updated_at", "DESC")
            ->paginate(12);

        return view('pages.services',
            compact(
                'page',
                'services'
            )
        );
    }

    public function portfolio(Request $request, $page) : View
    {
        $subs = $request->route('subs');
        
        if(null !== $subs){
            $project = Project::whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $subs])
                        ->firstOrFail();
            
            /**
             * Prepare localized routes for current page
             * to use for redirect after language was switched
             */
            $localizedRoutes = [];
            $pageSlugs = $page->getTranslations('slug'); 
            $projectSlugs = $project->getTranslations('slug'); 
            foreach($pageSlugs as $locale => $pageSlug){
                if($locale === config('app.default_locale')){
                    // Do not add locale prefix for default language
                    $localizedRoutes[$locale] = $pageSlug . DIRECTORY_SEPARATOR . $projectSlugs[$locale];
                } else {
                    $localizedRoutes[$locale] = $locale . DIRECTORY_SEPARATOR . $pageSlug . DIRECTORY_SEPARATOR . $projectSlugs[$locale];
                }
            }
            $request->session()->put('localized_routes', $localizedRoutes);

            /**
             * Get list of other projects
             */
            $projects = Project::latest()->take(5)->get()->except($project->id);

            return view('pages.portfolio-show',
                compact(
                    'page',
                    'project',
                    'projects'
                )
            );
        }

        /**
         * Get Projects
         */
        $projects = Project::query()
            ->where("is_active", 1)
            ->orderBy("updated_at", "DESC")
            ->paginate(12);

        return view('pages.portfolio',
            compact(
                'page',
                'projects'
            )
        );
    }
}

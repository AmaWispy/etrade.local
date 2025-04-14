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
     * Cooment on blog post
     */

    public function comment(Request $request) {
        $comment = $request->post();
        
        if($comment['type'] === 'blog'){

            Comment::create([
                'user_id' => auth()->user()->id,
                'type_id' => CommentsType::where('name', $comment['type'])->first()->id,
                'blog_id' => $comment['blog_id'],
                'reply_id' => $comment['comment_id'] ?? null,
                'reply_user_id' => $comment['comment_reply_user_id'] ?? null,
                'content' => strip_tags($comment['message']),
            ]);

            $comments = null;
            foreach(Comment::where('blog_id', $comment['blog_id'])->orderByDesc('created_at')->get() as $el){
                $commentData = [
                    'id' => $el->id,
                    'user_id' => $el->user_id,
                    'customer_id' => $el->customer_id,
                    'type_id' => $el->type_id,
                    'blog_id' => $el->blog_id,
                    'reply_user_id' => $el->reply_user_id,
                    'reply_id' => $el->reply_id,
                    'rating' => $el->rating,
                    'content' => $el->content,
                    'created_at' => $el->formattedDate(),

                    'comment_user' => $el->user ?? null,
                    'comment_replies' => [],
                ];
            
                foreach ($el->replies as $reply) {
                    $commentData['comment_replies'][] = [
                        'reply' => [
                            'blog_id' => $reply->blog_id,
                            'content' => $reply->content,
                            'created_at' => $reply->formattedDate(),
                            'customer_id' => $reply->customer_id,
                            'id' => $reply->id,
                            'reply_user_id' => $reply->reply_user_id,
                            'reply_id' => $reply->reply_id,
                            'reply_user' => $reply->reply_user,
                            'type_id' => $reply->type_id,
                        ],
                        'comment_reply_user' => $reply->user ?? null, 
                        'reply_user' => $reply->replyUser ?? null, 
                    ];
                }

                $comments[] = $commentData;
            }


            return [
                'status' => 200,
                'comment' => $comment,
                'comments' => $comments,
                'dasdas' => CommentsType::where('name', $comment['type'])->first()->id,
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
     * Show blog post
     */
    public function filtersBlog(){

    }

    /**
     * Show blog post
     */
    public function show(Request $request) : View
    {


        $slug = $request->route('slug');

        $post = Post::whereRaw('JSON_SEARCH(slug, "all", :value) IS NOT NULL', ['value' => $slug])
            ->firstOrFail();
        
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
        $comments = Comment::where('blog_id', $post->id)->orderByDesc('created_at')->get() ?? null;

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

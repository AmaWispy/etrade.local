<?php

namespace App\Models\Blog;

use App\Models\Comment;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Image\Manipulations; 
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends UnicodeModel implements HasMedia 
{
    use HasFactory;
    // use HasTags;
    use HasTranslations;
    use InteractsWithMedia;

    /**
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'date',
        'preview' => 'array',
        'tags' => 'array', 
    ];

    public $translatable = [
        'title',
        'slug', 
        'content',
        'tags',
        'preview',
        'seo_title',
        'seo_description',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(425)
            ->height(325);

        $this->addMediaConversion('main')
            ->width(850)
            ->height(650);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'blog_author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'blog_category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'commentable');
    }

    /**
     * Make excerpt by limiting content length
     */
    public function getExcerptAttribute()
    {
        return $this->makeExcerpt($this->content);
    }

    /**
     * Get formatted date
     */
    public function getPublishedDateAttribute()
    {
            return \Carbon\Carbon::parse($this->published_at)->format('j F, Y');
    }

    /**
     * Get prev post
     */
    public function getPrevious()
    {
        $post = Post::query()
            ->where('blog_category_id', $this->blog_category_id)
            ->where('id', "<", $this->id)
            ->orderBy('id', 'desc')
            ->first();
        return $post;
    }

    /**
     * Get post url
     */
    public function getLinkAttribute()
    {
        $prefix = $this->prefixRouteWithLocale();
        // $category = $this->category->slug;
        $post = $this->slug;
        $link = DIRECTORY_SEPARATOR;
        $link .= $prefix ? ($this->getCurrentLocale() . DIRECTORY_SEPARATOR) : '';
        $link .= 'blog' . DIRECTORY_SEPARATOR;
        // $link .= $category . DIRECTORY_SEPARATOR;
        $link .= $post . DIRECTORY_SEPARATOR;
        return $link;
    }

    /**
     * Get next post
     */
    public function getNext()
    {
        $post = Post::query()
            ->where('blog_category_id', $this->blog_category_id)
            ->where('id', '>', $this->id)
            ->orderBy('id', 'asc')
            ->first();
        return $post;
    }

    public function makeExcerpt($text, $length = 150, $end = '...') {
        // Remove any HTML tags and convert entities to their applicable characters
        $text = strip_tags($text);
    
        // Trim the text to the desired length and append the specified ending
        $excerpt = Str::limit($text, $length, $end);
    
        return "$excerpt";
    }

    /*public function getImage()
    {
        if(null !== $this->image){
            return url('storage/'.$this->image);
        }

        return url('storage/no-image.png');
    }*/

    public function getThumb()
    {
        $media = $this->getFirstMedia("post-images"); // Get from post-image collection
        if(null !== $media){
            return $media->getUrl('thumb');
        }
        return url('storage/no-image_425x325.png');
    }

    public function getImage($type = 'first')
    {
        if ($type === 'first') {
            $media = $this->getFirstMedia("post-images"); // Get from post-image collection
            if(null !== $media){
                return $media->getUrl('');
            }
        } else{
            $media = $this->getMedia("post-images"); // Get from post-image collection
            if(null !== $media){
                return $media;
            }
        }

        // return url('storage/no-image_850x650.png');
        return null;
    }

}

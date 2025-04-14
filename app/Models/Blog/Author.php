<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Author extends Model implements HasMedia 
{
    use HasFactory;
    use InteractsWithMedia;
    

    /**
     * @var string
     */
    protected $table = 'blog_authors';

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'blog_author_id');
    }

    public function getImage()
    {
        $media = $this->getFirstMedia("author-images"); // Get from post-image collection
        if(null !== $media){
            return $media->getUrl('');
        }
        return url('storage/no-image_850x650.png');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Blog\Post;
use App\Models\Shop\Product;
use App\Models\Shop\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'customer_id',
        'reply_id',
        'reply_user_id',
        'type_id',
        'blog_id',
        'product_id',
        'content',
        'rating',
    ];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CommentsType::class, 'type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function users(): HasMany
{
    return $this->hasMany(User::class, 'user_id', 'id');
}

    public function replyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reply_user_id');
    }
    
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'blog_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'reply_id');
    }

    public function formattedDate()
    {
        return Carbon::make($this->created_at)?->translatedFormat('j F, Y H:i');
    }
}

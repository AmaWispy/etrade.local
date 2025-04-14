<?php

namespace App\Models\Shop;

use App\Models\Shop\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follow extends Model
{
    use HasFactory;

    /**
     * @var string
     */

    protected $table = 'follows';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'total_price'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(FollowItems::class, 'follow_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'follow_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Shop\Cart;
use App\Models\Shop\CartItem;

class OrderCustom extends Model
{
    use HasFactory, SoftDeletes;

    const NEW = 'new'; // New order, simply saved in db
    const PENDING = 'pending'; // The client was redirected to payment gateway, waiting for some response from the gateway
    const VERIFICATION = 'verification'; // Notification obtained, seems to be ok, but one more verification required
    const PROCESSING = 'processing'; // Will be updated to processing after successful payment (or in case cash on delivery was selected)

    /**
     * Имя таблицы
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Разрешённые для массового заполнения поля
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'cart_id',
        'status',
        'comments',
        'total',
    ];

    /**
     * Касты для полей
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the cart associated with the order.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    /**
     * Get the cart items associated with the order.
     */
    public function items()
    {
        return $this->hasManyThrough(
            CartItem::class,
            Cart::class,
            'id', // Foreign key on carts table
            'shop_cart_id', // Foreign key on cart_items table
            'cart_id', // Local key on orders table
            'id' // Local key on carts table
        );
    }
}

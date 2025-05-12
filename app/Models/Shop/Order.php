<?php

namespace App\Models\Shop;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Order statuses
     */
    const NEW = 'new'; // New order, simply saved in db
    const PENDING = 'pending'; // The client was redirected to payment gateway, waiting for some response from the gateway
    const VERIFICATION = 'verification'; // Notification obtained, seems to be ok, but one more verification required
    const PROCESSING = 'processing'; // Will be updated to processing after successful payment (or in case cash on delivery was selected)

    /**
     * @var string
     */
    protected $table = 'shop_orders';

    public function cart(): BelongsTo
    {
        return $this->BelongsTo(Cart::class, 'shop_cart_id');
    }

    /**
     * Relation to get items in order edit form in admin panel
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'shop_cart_id', 'shop_cart_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function address(): BelongsTo
    {
        // return $this->BelongsTo(OrderAddress::class, 'shop_customer_address_id');
        return $this->BelongsTo(Address::class, 'shop_customer_address_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shop_shipping_method_id');
    }

    public function shippingDetails(): BelongsTo
    {
        return $this->belongsTo(ShippingDetails::class, 'shipping_details_id');
    }

    public function getShippingPrice()
    {
        $price = Currency::exchange($this->shipping);
        return Currency::format($price);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'shop_payment_method_id');
    }
}

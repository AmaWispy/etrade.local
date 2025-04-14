<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * Providers
     */
    const CASHONDELIVERY        = 'cash_on_delivery';    // cash on delivery
    const CARDONDELIVERY        = 'card_on_delivery';    // card on delivery
    const PAYNET                = 'paynet';              // paynet

    /**
     * Payment status
     */
    const REGISTERED = 'registered'; // obtained notification, verification required
    const SUCCEED    = 'succeed';    // verified, succeed
    const DECLINED   = 'declined';   // something gone wrong, explore response (info) to find what exactly
    const FAILED     = 'failed';     // payment failed

    protected $table = 'shop_payments';

    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

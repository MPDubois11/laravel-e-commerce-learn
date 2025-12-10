<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'phone',
        'first_name',
        'last_name',
        'address',
        'address_2',
        'city',
        'state',
        'postal_code',
        'country',
        'subtotal',
        'tax',
        'total',
        'status',
        // Stripe fields
        'stripe_session_id',
        'stripe_payment_intent_id',
        'stripe_customer_id',
        'payment_status',
        'payment_method',
        'card_brand',
        'card_last_four',
        'currency',
        'stripe_charge_id',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

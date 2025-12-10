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

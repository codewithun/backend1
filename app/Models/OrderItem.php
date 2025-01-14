<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'ticket_id',
        'name',
        'quantity',
        'price',
        'total_item_price',
        'type'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Addproduct::class, 'product_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Tiket::class, 'ticket_id');
    }
}

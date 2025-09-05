<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "order_items";
    protected $primaryKey = 'OrderItem_ID'; 
    protected $fillable = [
        'BakeryOrder_ID',
        'Bakery_ID',
        'Sum_quantity',
        'Sum_price'
    ];

    public function bakeryOrder()
    {
        return $this->belongsTo(BakeryOrder::class, 'BakeryOrder_ID', 'BakeryOrder_ID');
    }
    public function bakery()
    {
        return $this->belongsTo(Bakery::class, 'Bakery_ID', 'Bakery_ID');
    }
}

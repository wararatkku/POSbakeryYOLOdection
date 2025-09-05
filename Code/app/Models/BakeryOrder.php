<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BakeryOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "bakery_orders";
    protected $primaryKey = 'BakeryOrder_ID'; 
    protected $fillable = [
        'Total_price',
        'Payment_ID'
    ];

    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class, 'BakeryOrder_ID', 'BakeryOrder_ID');
    }
    public function payment() {
        return $this->belongsTo(Payment::class, 'Payment_ID');
    }
}

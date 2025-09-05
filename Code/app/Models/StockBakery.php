<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockBakery extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "stock_bakeries";
    protected $primaryKey = 'StockBakery_ID'; 
    protected $fillable = [
        'Bakery_quantity',
        'Sell_quantity',
        'Bakery_exp',
        'Bakery_ID'
    ];
    public function bakery() {
        return $this->belongsTo(Bakery::class, 'Bakery_ID', 'Bakery_ID');
    }

}
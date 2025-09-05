<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bakery extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "bakeries";
    protected $primaryKey = 'Bakery_ID'; 
    protected $fillable = [
        'Bakery_name',
        'Bakery_name_en',
        'Bakery_image',
        'Bakery_price',
        'IP_Status'
    ];
    public function bakeryorders()
    {
        return $this->hasMany(BakeryOrder::class, 'Bakery_ID');
    }
    public function stock() {
        return $this->hasMany(StockBakery::class, 'Bakery_ID'); 
    }
}
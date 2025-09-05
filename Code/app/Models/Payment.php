<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "payments";
    protected $primaryKey = 'Payment_ID'; 
    protected $fillable = [
        'Payment_Type',
        'Payment_Total'
    ];

    public function bakeryorders()
    {
        return $this->hasMany(BakeryOrder::class, 'Payment_ID');
    }
}

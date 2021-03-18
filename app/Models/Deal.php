<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = ['number_of_products', 'price'];

    protected static function booted()
    {
        static::saving(function ($deal) {
            if (is_numeric($deal->number_of_products) and $deal->number_of_products > 0) {
            	$deal->unit_price = round($deal->price/$deal->number_of_products, 1);
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);    	
    }
}

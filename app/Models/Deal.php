<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Deal
 * @package App\Models
 */
class Deal extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['number_of_products', 'price'];

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::saving(function ($deal) {
            if (is_numeric($deal->number_of_products) and $deal->number_of_products > 0) {
                $deal->unit_price = round($deal->price/$deal->number_of_products, 1);
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Exception;

use Illuminate\Support\MessageBag;

class Currency extends Model
{
    use \Israeldavidvm\EloquentTraits\AttributesTrait;
	
    protected $table = 'currencies';

	protected $casts = [
		'exchange_rate' => 'float'
	];

	protected $fillable = [
		'name',
		'symbol',
		'exchange_rate'
	];

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function product_prices()
	{
		return $this->hasMany(ProductPrice::class);
	}

	/**
     * Initialize the attributes for the Currency model.
     *
     * @param array $attributes
     */
    public function initAttributes($attributes)
    {
        $this->name = $attributes['name'] ?? null;
        $this->symbol = $attributes['symbol'] ?? null;
        $this->exchange_rate = $attributes['exchange_rate'] ?? null;
    }

    public static function validateAttributes(array $arrayAttributes) :  ?MessageBag {
        $validator = Validator::make($arrayAttributes, [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:currencies,name',
            ],
            'symbol' => [
                'required',
                'string',
                'max:10', 
            ],
            'exchange_rate' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return null;
    }
}

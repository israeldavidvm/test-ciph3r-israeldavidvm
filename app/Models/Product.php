<?php

namespace App\Models;

use Illuminate\Support\MessageBag;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Exception;

use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable,\Israeldavidvm\EloquentTraits\AttributesTrait;

	protected $table = 'products';

	protected $casts = [
		'price' => 'float',
		'tax_cost' => 'float',
		'manufacturing_cost' => 'float',
		'currency_id' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'price',
		'tax_cost',
		'manufacturing_cost',
		'currency_id'
	];

    public function toSearchableArray()
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at ? $this->created_at->timestamp : null,
            'updated_at' => $this->updated_at ? $this->updated_at->timestamp : null,
            'currency_id' => (int) $this->currency_id,
            'tax_cost' => (float) $this->tax_cost,
            'manufacturing_cost' => (float) $this->manufacturing_cost,
            'price' => (float) $this->price,
        ];
    }

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function product_prices()
	{
		return $this->hasMany(ProductPrice::class);
	}

	    /**
     * Initialize the attributes for the Product model.
     *
     * @param array $attributes
     */
    public function initAttributes($attributes)
    {
        $this->name = $attributes['name'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->price = $attributes['price'] ?? null;
        $this->tax_cost = $attributes['tax_cost'] ?? null;
        $this->manufacturing_cost = $attributes['manufacturing_cost'] ?? null;
        $this->currency_id = $attributes['currency_id'] ?? null;
    }

    public static function validateAttributes(array $arrayAttributes) :  ?MessageBag
    {
        $validator = Validator::make($arrayAttributes, [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'currency_id' => [
                'required',
                'integer',
                'exists:currencies,id', // Asegúrate de que el ID de la moneda exista
            ],
            'tax_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'manufacturing_cost' => [
                'nullable',
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

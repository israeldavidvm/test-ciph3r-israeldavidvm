<?php


namespace App\Models;

use Illuminate\Support\MessageBag;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductPrice extends Model
{
    use \Israeldavidvm\EloquentTraits\AttributesTrait;

	protected $table = 'product_prices';

	protected $casts = [
		'product_id' => 'int',
		'currency_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'product_id',
		'currency_id',
		'price'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	    /**
     * Initialize the attributes for the ProductPrice model.
     *
     * @param array $attributes
     */
    public function initAttributes($attributes)
    {
        $this->product_id = $attributes['product_id'] ?? null;
        $this->currency_id = $attributes['currency_id'] ?? null;
        $this->price = $attributes['price'] ?? null;
    }

    /**
     * Validate the attributes for the ProductPrice model.
     *
     * @param array $attributes
     * @throws Exception
     */
    public static function validateAttributes(array $arrayattributes): ?MessageBag
    {
        $validator = Validator::make($arrayattributes, [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id', // Asegúrate de que el ID del producto exista
            ],
            'currency_id' => [
                'required',
                'integer',
                'exists:currencies,id', // Asegúrate de que el ID de la moneda exista
            ],
            'price' => [
                'required',
                'numeric',
                'min:0', // El precio no puede ser negativo
            ],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return null;
    }
}

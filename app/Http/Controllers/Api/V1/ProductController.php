<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Exception;
use OpenApi\Attributes as OA; // Make sure this line is present!


class ProductController extends Controller
{
    #[OA\Get(
        path: '/api/v1/products',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'List all products',
        description: 'Retrieves a paginated list of all products in the system.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'A paginated list of products',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Product')), // Assuming you have a Product schema
                        new OA\Property(property: 'first_page_url', type: 'string', example: 'http://localhost/api/v1/products?page=1'),
                        new OA\Property(property: 'from', type: 'integer', example: 1),
                        new OA\Property(property: 'last_page', type: 'integer', example: 1),
                        new OA\Property(property: 'last_page_url', type: 'string', example: 'http://localhost/api/v1/products?page=1'),
                        new OA\Property(property: 'next_page_url', type: 'string', example: 'null'),
                        new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/v1/products'),
                        new OA\Property(property: 'per_page', type: 'integer', example: 10),
                        new OA\Property(property: 'prev_page_url', type: 'string', example: 'null'),
                        new OA\Property(property: 'to', type: 'integer', example: 1),
                        new OA\Property(property: 'total', type: 'integer', example: 5),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(Product::paginate(10), 200);
    }

    #[OA\Post(
        path: '/api/v1/products',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'Create a new product',
        description: 'Adds a new product to the database with its details.',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Product data to create',
            content: new OA\JsonContent(
                ref: '#/components/schemas/Product' 
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product created successfully',
                content: new OA\JsonContent(
                    properties:[
                        new OA\Property(property: 'message', type: 'string', example: "Product created successfully")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'errors', type: 'object', 
                            example: [
                                'name' => ['The name field is required.'],
                                'price' => ['The price field is required.']
                        ])
                    ]
                )
            )
        ]
    )]
    public function store(Request $request)
    {        
    
        $errors=Product::validateAttributes($request->all());
        
        if($errors!=null){
            return response()->json(
                    [
                        'message' => 'Invalid input',
                        'errors' => $errors
                    ],
                    422
                );
        }

        $product = Product::create(
            $request->all()
        );

        return response()->json(["message"=> "Product created successfully"], 201);
    }

    #[OA\Get(
        path: '/api/v1/products/{id}',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'Get a product by ID',
        description: 'Retrieves a single product by its unique identifier.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the product to retrieve',
                schema: new OA\Schema(type: 'integer', format: 'int64', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Product'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Not Found'),
                    ]
                )
            )
        ]
    )]
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json($product, 200);
    }

    #[OA\Put(
        path: '/api/v1/products/{id}',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'Update a product by ID',
        description: 'Updates an existing product\'s details using its unique identifier.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the product to update',
                schema: new OA\Schema(type: 'integer', format: 'int64', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Product data to update',
            content: new OA\JsonContent(
                ref: '#/components/schemas/Product' // Reusing the schema for consistency
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product updated successfully',
                content: new OA\JsonContent(
                    properties:[
                        new OA\Property(property: 'message', type: 'string', example: "Product updated successfully"),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Product'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Not Found'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Error'),
                        new OA\Property(property: 'errors', type: 'object', 
                        example: [
                            'name' => ['The name field is required.'],
                            'price' => ['The price field is required.']
                        ])
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $errors=Product::validateAttributes($request->all());
        
        if($errors!=null){
            return response()->json(
                    [
                        'message' => "Product update failed",
                        'errors' => $errors
                    ],
                    422
                );
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        $product->update($request->all());
        return response()->json(
            ["message"=> "Product updated successfully", 'data' =>$product], 
            200);
    }

    #[OA\Delete(
        path: '/api/v1/products/{id}',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'Delete a product by ID',
        description: 'Removes a product from the database using its unique identifier.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the product to delete',
                schema: new OA\Schema(type: 'integer', format: 'int64', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Product deleted'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Not Found'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            )
        ]
    )]
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }

    #[OA\Get(
        path: '/api/v1/products/{id}/prices',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'Get the list of prices for a product',
        description: 'Retrieves all associated prices for a specific product by its ID.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the product to get prices for',
                schema: new OA\Schema(type: 'integer', format: 'int64', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of prices for the product',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/ProductPrice') // Assuming you have a ProductPrice schema
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Not Found'),
                    ]
                )
            )
        ]
    )]
    public function getPrices($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json($product->product_prices()->paginate(10), 200);
    }

    #[OA\Post(
        path: '/api/v1/products/{id}/prices',
        tags: ['Product'],
        security: [['sanctum' => []]],
        summary: 'Create a new price for a product',
        description: 'Adds a new price entry for a specific product.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the product to associate the price with',
                schema: new OA\Schema(type: 'integer', format: 'int64', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Price data to create',
            content: new OA\JsonContent(
                ref: '#/components/schemas/ProductPrice' // Assuming you have a ProductPriceRequest schema
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Price created successfully',
                content: new OA\JsonContent(
                    properties:[
                        new OA\Property(property: 'message', type: 'string', example: 'Price created successfully.'),
                        new OA\Property(property:'data' , ref: '#/components/schemas/ProductPrice'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Not Found'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid input'),
                        new OA\Property(property: 'errors', type: 'object', 
                        example: [
                            'name' => ['The name field is required.'],
                            'price' => ['The price field is required.']
                        ])                    ]
                )
            )
        ]
    )]
    public function storePrice(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $request['product_id'] = $id;

        $errors=ProductPrice::validateAttributes($request->all());
        
        if($errors!=null){
            return response()->json(
                    [
                        'message' => "Invalid input",
                        'errors' => $errors
                    ],
                    422
                );
        }

        $price = new ProductPrice();
        $price->initAttributes($request);
        $price->save();

        return response()->json(
            [
                'message' => 'Price created successfully',
                'data' => $price
            ],
            201);
    }
}
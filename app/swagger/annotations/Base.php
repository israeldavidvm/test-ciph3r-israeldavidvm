<?php

namespace App\Swagger\Annotations;

use OpenApi\Attributes as OA;


#[OA\Info(
    version: '1.0.0',
    title: 'API del sitio',
    description: 'API para el manejo de datos del sitio',
    contact: new OA\Contact(
        email: 'israeldavidvm@gmail.com',
        name: 'Israel David Villaroel Moreno'
    ),
    license: new OA\License(
        name: 'Licencia abierta',
        url: 'https://opensource.org/licenses/MIT'
    )
)]

#[OA\Components(
    schemas:[
        new OA\Schema(
            schema: 'User',
            type: 'object',
            description: 'User Schema',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                new OA\Property(
                    property: 'email_verified_at',
                    type: 'string',
                    format: 'date-time',
                    description: 'The email verification timestamp'
                ),
                new OA\Property(
                    property: 'remember_token',
                    type: 'string',
                    description: 'The remember token'
                ),
                new OA\Property(
                    property: 'created_at',
                    type: 'string',
                    format: 'date-time',
                    description: 'Creation date of the user' // Descripción actualizada
                ),
                new OA\Property(
                    property: 'updated_at',
                    type: 'string',
                    format: 'date-time',
                    description: "Last Update date of the user"
                )
            ]
        ),
        new OA\Schema(
            schema: 'Currency',
            type: 'object',
            description: 'Currency Schema',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'US Dollar'),
                new OA\Property(property: 'symbol', type:'string',example:'$'),
                new OA\Property(property: 'exchange_rate', type: 'number', format: 'float', example: 1.0),
                new OA\Property(
                        property:"created_at",
                        type:"string",
                        format:"date-time",
                        description:"The creation date of the currency"
                    ),
                new OA\Property(
                        property:"updated_at",
                        type:"string",
                        format:"date-time",
                        description:"The last update date of the currency"
                    ),
                
            ]
        ),
        new OA\Schema(
            schema: 'Product',
            type: 'object',
            description: 'Product Schema',
            properties: [
                new OA\Property(nullable:true, property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'Pc Gamer'),
                new OA\Property(property: 'description', type: 'string', example: 'The best pc Gamer'),
                new OA\Property(property: 'price', type: 'integer', example: 1000),
                new OA\Property(property: 'currency_id', type: 'integer', example: 1),
                new OA\Property(nullable:true, property: 'tax_cost', type: 'integer', example: 100),
                new OA\Property(nullable:true, property: 'manufacturing_cost', type: 'integer', example: 500),


                new OA\Property(nullable:true, property: 'created_at', type: 'string', format: 'date-time', example: '2025-06-14T12:34:56Z'),
                new OA\Property(nullable:true, property: 'updated_at', type: 'string', format: 'date-time', example: '2025-06-14T12:34:56Z')
 
            ]
        ),
        new OA\Schema(
            schema: 'ProductPrice',
            type: 'object',
            description: 'ProductPrice Schema',
            properties: [
                new OA\Property(nullable:true, property: 'id', type: 'integer', example: 1),
               
                new OA\Property(property: 'product_id', type: 'integer', example: 1),
                new OA\Property(property: 'currency_id', type: 'integer', example: 1),
                new OA\Property(property: 'price', type: 'integer', example: 1000),

                new OA\Property(nullable:true, property: 'created_at', type: 'string', format: 'date-time', example: '2025-06-14T12:34:56Z'),
                new OA\Property(nullable:true, property: 'updated_at', type: 'string', format: 'date-time', example: '2025-06-14T12:34:56Z')
 
            ]
        ),

    ],
    securitySchemes:[
        new OA\SecurityScheme(
            securityScheme: 'sanctum',
            type: 'http',
            scheme: 'bearer',
            description: 'Enter token in format (Bearer <token>) (Ejemplo: Bearer 2|Cfz3yDjKqUh55AUI6I9nQQv6MEHsEqQvJToMDnJ7e7c8478a)',
            in: 'header',
            name: 'Authorization',
        ),
    ]

)]
class Base
{
    // Esta clase sigue siendo un "gancho" para que Swagger-PHP
    // pueda procesar los atributos de nivel de documento asociados a ella.
}
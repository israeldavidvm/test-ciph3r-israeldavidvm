<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Currency;
use App\Models\Product;
use App\Models\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

use Laravel\Sanctum\Sanctum;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ProductTest extends TestCase
{


    use DatabaseMigrations;


    protected function setUp(): void{

        parent::setUp();
        $this->seed();

    }

    public static function indexProvider(): array
    {
        return [
            'with api token' => 
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                200

            ],
            'without api token' => 
            [
                null,
                401

            ]

        ];
    }

     /**
     * Test the index method.
     *
     * @return void
     */
    #[DataProvider('indexProvider')]
    public function test_index($request,$httpStatusCode): void
    {
        if($request){

            $user=User::firstWhere('email',$request['email']);

            Sanctum::actingAs($user,['*']);

        }

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus($httpStatusCode);

        if($httpStatusCode==200){
            $response->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'currency_id',
                        'tax_cost',
                        'manufacturing_cost',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
        }
    }


    public static function storeProvider(): array
    {
        return [
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 99.99,
                    'currency_id' => 1,
                    'tax_cost' => 5.00,
                    'manufacturing_cost' => 50.00,
                ],
                201
            ],
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 99.99,
                    'currency_id' => 1233211,
                    'tax_cost' => 5.00,
                    'manufacturing_cost' => 50.00,
                ],
                422
            ],
        ];
    }


    /**
     * Test the store method.
     *
     * @return void
     */
    #[DataProvider('storeProvider')]
    public function test_store($loginData,$data,$statusCode)
    {
        if($loginData){

            $user=User::firstWhere('email',$loginData['email']);

            Sanctum::actingAs($user,['*']);

        }

        $response = $this->postJson('/api/v1/products', $data);


        $response->assertStatus($statusCode);

        if($statusCode==201){
            $this->assertDatabaseHas('products', $data);
        }

    }

    /**
     * Test the show method.
     *
     * @return void
     */
    public function test_show()
    {

        
        $user=User::firstWhere('email','israeldavidvm@gmail.com');

        Sanctum::actingAs($user,['*']);

        $response = $this->getJson('/api/v1/products/' . 1);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => 1]);
    }

    public static function updateProvider(): array
    {
        return [
            [ 
                [
                'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id' => 1,
                    'name' => 'Test Product Updated',
                    'description' => 'Test Description',
                    'price' => 99.99,
                    'currency_id' => 1,
                    'tax_cost' => 5.00,
                    'manufacturing_cost' => 50.00,
                ],
                200
            ],
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id' => 100000,
                    'name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 99.99,
                    'currency_id' => 1,
                    'tax_cost' => 5.00,
                    'manufacturing_cost' => 50.00,
                ],
                404
            ],
        ];
    }


    /**
     * Test the update method.
     *
     * @return void
     */
    #[DataProvider('updateProvider')]
    public function test_update($loginData,$data,$statusCode)
    {
     
        if($loginData){

            $user=User::firstWhere('email',$loginData['email']);

            Sanctum::actingAs($user,['*']);

        }


        $response = $this->putJson('/api/v1/products/' . $data['id'], $data);

        $response->assertStatus($statusCode);

        if($statusCode==201){
            $this->assertDatabaseHas('products', $data);
        }
    }

    public static function destroyProvider(): array
    {
        return [
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id' => 1,
                ],
                200
            ],
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id' => 100000,
                ],
                404
            ],
        ];
    }

    /**
     * Test the destroy method.
     *
     * @return void
     */
    #[DataProvider('destroyProvider')]
    public function test_destroy($loginData,$data,$statusCode)
    {
      
        if($loginData){

            $user=User::firstWhere('email',$loginData['email']);

            Sanctum::actingAs($user,['*']);

        }

        $response = $this->deleteJson('/api/v1/products/' . $data['id']);

        $response->assertStatus($statusCode);

        if($statusCode==201){
            $this->assertDatabaseMissing('products',$data);
        }
    }

    public static function getPricesProvider(): array
    {
        return [
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id' => 1,
                ],
                200
            ],
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id' => 100000,
                ],
                404
            ],
        ];
    }

    // /**
    //  * Test the getPrices method.
    //  *
    //  * @return void
    //  */
    #[DataProvider('getPricesProvider')]
    public function test_getPrices($loginData,$data,$statusCode)
    {

        if($loginData){

            $user=User::firstWhere('email',$loginData['email']);

            Sanctum::actingAs($user,['*']);

        }

        $response = $this->getJson('/api/v1/products/' .$data['id']. '/prices');

        $response->assertStatus($statusCode);

        // if($statusCode==201){
        //     $this->assertDatabaseMissing('products',$data);
        // }
    }

    public static function storagePriceProvider(): array
    {
        return [
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id'=>1,
                    'price' => 100.00,
                    'currency_name' => 'US Dollar',
                ],
                201
            ],
            [
                [
                    'email' => 'israeldavidvm@gmail.com',
                ],
                [
                    'id'=>10000,
                    'price' => 100.00,
                    'currency_name' => 'US Dollar',
                ],
                404
            ],
        ];
    }

    /**
     * Test the storePrice method.
     *
     * @return void
     */
    #[DataProvider('storagePriceProvider')]
    public function test_storePrice($loginData,$data,$statusCode)
    {
        if($loginData){

            $user=User::firstWhere('email',$loginData['email']);

            Sanctum::actingAs($user,['*']);

        }

        $currency=Currency::where('name', $data['currency_name'])->first();

        $data['currency_id'] =$currency->id; 

        $response = $this->postJson('/api/v1/products/' . $data['id'] . '/prices', $data);

        $response->assertStatus($statusCode);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductDealControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_product_deals()
    {
        $this->seed();
        $product = Product::first();
        $response = $this->get("/api/products/$product->id/deals");
        $response->assertStatus(200);
        self::assertEquals($response[0]['product_id'], $product->id);
    }

    public function test_delete_product_deals()
    {
        $this->seed();
        $product = Product::first();
        $response = $this->delete("/api/products/$product->id/deals");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('deals', ['product_id' => $product->id]);
    }

    public function test_set_product_deals_success()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);
        $response = $this->postJson("/api/products/$product->id/deals", ['deals' => [
            [
                'number_of_products' => 3,
                'price' => 550,
            ],
            [
                'number_of_products' => 5,
                'price' => 900,
            ],
            [
                'number_of_products' => 7,
                'price' => 1200,
            ]
        ]]);
        $response->assertStatus(201)->assertJson(function (AssertableJson $json) {
            $json->has(2);
        });
        self::assertEquals(180, $response[1]['unit_price']);
    }

    public function test_set_product_deals_validation_error()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);

        // price is required and number_of_products should be greater than 1
        $response = $this->postJson("/api/products/$product->id/deals", ['deals' => [
            [
                'number_of_products' => 1
            ]
        ]]);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('deals.0.number_of_products')->has('deals.0.price');
        });
    }

    public function test_set_product_deals_duplicate_number_error()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);

        // there can't be two deals for same amount of products
        $response = $this->postJson("/api/products/$product->id/deals", ['deals' => [
            [
                'number_of_products' => 5,
                'price' => 550,
            ],
            [
                'number_of_products' => 5,
                'price' => 900,
            ],
        ]]);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('deals');
        });
    }
}

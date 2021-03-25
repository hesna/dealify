<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product_success()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'shiny new product',
            'price' => 200,
        ]);
        $response->assertStatus(201)->assertJson(function (AssertableJson $json) {
            $json->has('data.id');
            $json->where('data.name', 'shiny new product');
            $json->where('data.price', 200);
        });
    }

    public function test_create_product_name_required_error()
    {
        $response = $this->postJson('/api/products', ['price' => 200]);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.name');
        });
    }

    public function test_create_product_price_toobig_error()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'shiny new product',
            'price' => 20000,
        ]);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.price');
        });
    }

    public function test_update_product_success()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);
        $response = $this->putJson("/api/products/$product->id", [
            'name' => 'shiny new name',
            'price' => 300,
        ]);

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('data.id');
            $json->where('data.name', 'shiny new name');
            $json->where('data.price', 300);
        });
    }

    public function test_update_product_price_required_error()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);
        $response = $this->putJson("/api/products/$product->id", ['name' => 'shiny new name']);

        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.price');
        });
    }

    public function test_update_product_price_toobig_error()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);
        $response = $this->putJson("/api/products/$product->id", [
            'name' => 'shiny new name',
            'price' => 20000,
        ]);

        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.price');
        });
    }

    public function test_get_product()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);
        $response = $this->getJson("/api/products/$product->id");

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('data.id');
            $json->where('data.name', 'new product');
            $json->where('data.price', 200);
        });
    }
}

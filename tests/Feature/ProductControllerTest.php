<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product_success()
    {
        $response = $this->post('/api/products', [
            'name' => 'shiny new product',
            'price' => 200,
        ]);

        $response->assertStatus(201)->assertJson([
            'name' => 'shiny new product',
            'price' => 200
        ]);
    }

    public function test_create_product_validation_error()
    {
        $response = $this->post('/api/products', ['price' => 200]);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('name');
        });

        $response = $this->post('/api/products', ['name' => 'shiny new product']);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('price');
        });       

        $response = $this->post('/api/products', [
            'name' => 'shiny new product',
            'price' => 20000,
        ]);         
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('price');
        });               
    }    

    public function test_update_product_success()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);

        $response = $this->put("/api/products/$product->id", [
            'name' => 'shiny new name',
            'price' => 300,
        ]);

        $response->assertStatus(200)->assertJson([
            'name' => 'shiny new name',
            'price' => 300
        ]);
    }

    public function test_update_product_validation_error()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);

        $response = $this->put("/api/products/$product->id", ['price' => 200]);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('name');
        });

        $response = $this->put("/api/products/$product->id", ['name' => 'shiny new name']);
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('price');
        });       

        $response = $this->put("/api/products/$product->id", [
            'name' => 'shiny new name',
            'price' => 20000,
        ]);         
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('price');
        });               
    }

    public function test_get_product()
    {
        $product = Product::create(['name' => 'new product', 'price' => 200]);
        $response = $this->get("/api/products/$product->id");

        $response->assertStatus(200)->assertJson([
            'id' => $product->id,
            'name' => 'new product',
            'price' => 200,
        ]);
    }    
}

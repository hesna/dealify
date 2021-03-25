<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Deal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_without_deal()
    {
        $this->createSampleProducts();
        $response = $this->postJson("/api/checkout", ['products' => [
            ['id' => 2001],
            ['id' => 2002],
            ['id' => 2003],
            ['id' => 2001],
            ['id' => 2003],
            ['id' => 2003],
        ]]);

        $response->assertStatus(200)
            ->assertJsonPath('products.0.count', 2)
            ->assertJson([
                'total_raw_price' => 1300,
                'total_price' => 1300,
                'total_discount' => 0,
            ]);
        self::assertEmpty($response['applied_deals']);
    }

    public function test_checkout_with_single_deal()
    {
        $this->createSampleProducts();
        $product = Product::create(['name' => 'product4', 'price' => 50]);
        $product->deals()->save(Deal::make(['number_of_products' => 3, 'price' => 130]));

        $response = $this->postJson("/api/checkout", ['products' => [
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => 2001],
            ['id' => 2003],
            ['id' => 2003],
            ['id' => $product->id],
        ]]);
        $response->assertStatus(200)
            ->assertJsonPath('products.2.count', 4)
            ->assertJsonPath('applied_deals.0.product_name', 'product4')
            ->assertJsonPath('applied_deals.0.match_times', 1)
            ->assertJson([
                'total_raw_price' => 900,
                'total_price' => 880,
                'total_discount' => 20,
            ]);
        self::assertCount(1, $response['applied_deals']);
    }

    public function test_checkout_with_multiple_deals()
    {
        $this->createSampleProducts();
        $product4 = Product::create(['name' => 'product4', 'price' => 50]);
        $product4->deals()->save(Deal::make(['number_of_products' => 3, 'price' => 130]));
        $product5 = Product::create(['name' => 'product5', 'price' => 80]);
        $product5->deals()->save(Deal::make(['number_of_products' => 2, 'price' => 150]));

        $response = $this->postJson("/api/checkout", ['products' => [
            ['id' => $product4->id],
            ['id' => $product4->id],
            ['id' => 2001],
            ['id' => $product4->id],
            ['id' => $product5->id],
            ['id' => 2003],
            ['id' => $product5->id],
            ['id' => 2003],
            ['id' => $product4->id],
        ]]);

        $response->assertStatus(200)
            ->assertJsonPath('products.2.count', 4)
            ->assertJsonPath('products.3.count', 2)
            ->assertJsonPath('applied_deals.0.product_name', 'product4')
            ->assertJsonPath('applied_deals.1.product_name', 'product5')
            ->assertJson([
                'total_raw_price' => 1060,
                'total_price' => 1030,
                'total_discount' => 30,
            ]);
        self::assertCount(2, $response['applied_deals']);
    }

    public function test_checkout_with_samename_deals()
    {
        $this->createSampleProducts();
        $product = Product::create(['name' => 'product4', 'price' => 50]);
        $product->deals()->saveMany([
            Deal::make(['number_of_products' => 2, 'price' => 95]),
            Deal::make(['number_of_products' => 3, 'price' => 130]),
            Deal::make(['number_of_products' => 5, 'price' => 210]),
            Deal::make(['number_of_products' => 10, 'price' => 350]),
        ]);

        $response = $this->postJson("/api/checkout", ['products' => [
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => 2002],
            ['id' => $product->id],
            ['id' => 2001],
            ['id' => 2003],
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => 2003],
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => 2001],
            ['id' => $product->id],
            ['id' => $product->id],
        ]]);

        $response->assertStatus(200)
            ->assertJsonPath('products.3.count', 9)
            ->assertJsonPath('applied_deals.0.number_of_products', 5)
            ->assertJsonPath('applied_deals.1.number_of_products', 3)
            ->assertJson([
                'total_raw_price' => 1450,
                'total_price' => 1390,
                'total_discount' => 60,
            ]);
        self::assertCount(2, $response['applied_deals']);
    }

    protected function createSampleProducts()
    {
        Product::insert([
            ['id' => 2001, 'name' => 'product1', 'price' => 100],
            ['id' => 2002, 'name' => 'product2', 'price' => 200],
            ['id' => 2003, 'name' => 'product3', 'price' => 300],
        ]);
    }
}

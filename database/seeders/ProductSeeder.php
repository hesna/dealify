<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Deal;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$dicsounts = [0.1, 0.15, 0.2, 0.25, 0.3];
    	for ($i=0; $i < 20; $i++) { 
	        $product = Product::factory()->create();
	        $deal = Deal::factory()->for($product)->make();
	        $selectedDiscount = $dicsounts[array_rand($dicsounts)];
	        $deal->price = $deal->number_of_products * $product->price * (1-$selectedDiscount);
	        $deal->save();
    	}
    }
}

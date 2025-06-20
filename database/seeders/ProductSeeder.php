<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Currency;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear divisas
        $usd = new Currency;
        $usd->name = "US Dollar";
        $usd->symbol = "$";
        $usd->exchange_rate = 1.00;
        $usd->save();

        $eur = new Currency;
        $eur->name = "Euro";
        $eur->symbol = "€";
        $eur->exchange_rate = 0.93;
        $eur->save();

        // Crear productos
        $product1 = new Product;
        $product1->name = "Laptop Pro";
        $product1->description = "High-performance laptop";
        $product1->price = 1200.00;
        $product1->currency_id = $usd->id;
        $product1->tax_cost = 120.00;
        $product1->manufacturing_cost = 600.00;
        $product1->save();

        $product2 = new Product;
        $product2->name = "Smartphone X";
        $product2->description = "Latest smartphone model";
        $product2->price = 800.00;
        $product2->currency_id = $eur->id;
        $product2->tax_cost = 80.00;
        $product2->manufacturing_cost = 400.00;
        $product2->save();

        // Crear precios de productos en diferentes divisas
        $productPrice1 = new ProductPrice;
        $productPrice1->product_id = $product1->id;
        $productPrice1->currency_id = $eur->id;
        $productPrice1->price = 1116.00; // 1200 * 0.93
        $productPrice1->save();

        $productPrice2 = new ProductPrice;
        $productPrice2->product_id = $product2->id;
        $productPrice2->currency_id = $usd->id;
        $productPrice2->price = 860.22; // 800 * 1.075 (1/0.93)
        $productPrice2->save();
    }
}
<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'price' => 100000,
                'sale_price' => 80000,
                'description' => 'Product 1 description',
                'image_url' => 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg',
                'rating' => 5,
                'is_sale' => true,
                'is_popular' => true,
                'is_special' => false,
            ],
            [
                'name' => 'Product 2',
                'price' => 200000,
                'sale_price' => 150000,
                'description' => 'Product 2 description',
                'image_url' => 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg',
                'rating' => 4,
                'is_sale' => true,
                'is_popular' => false,
                'is_special' => true,
            ],
            [
                'name' => 'Product 3',
                'price' => 150000,
                'sale_price' => null,
                'description' => 'Product 3 description',
                'image_url' => 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg',
                'rating' => 4,
                'is_sale' => false,
                'is_popular' => false,
                'is_special' => false,
            ],
            [
                'name' => 'Product 4',
                'price' => 300000,
                'sale_price' => 250000,
                'description' => 'Product 4 description',
                'image_url' => 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg',
                'rating' => 5,
                'is_sale' => true,
                'is_popular' => true,
                'is_special' => false,
            ],
            [
                'name' => 'Product 5',
                'price' => 50000,
                'sale_price' => null,
                'description' => 'Product 5 description',
                'image_url' => 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg',
                'rating' => 3,
                'is_sale' => false,
                'is_popular' => false,
                'is_special' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
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
                'name' => 'European Oak Plank',
                'category' => 'Lumber',
                'description' => 'Premium kiln-dried European oak, planed on all four sides. Ideal for fine joinery and furniture making.',
                'price' => 64.50,
                'stock' => 120,
            ],
            [
                'name' => 'Reclaimed Pine Board',
                'category' => 'Lumber',
                'description' => 'Character-rich reclaimed pine salvaged from old barns. Each board carries its own history and patina.',
                'price' => 38.00,
                'stock' => 60,
            ],
            [
                'name' => 'Herringbone Oak Flooring',
                'category' => 'Flooring',
                'description' => 'Engineered oak flooring in a classic herringbone pattern. Pre-finished with a natural matt oil.',
                'price' => 89.99,
                'stock' => 300,
            ],
            [
                'name' => 'Solid Walnut Dining Table',
                'category' => 'Furniture',
                'description' => 'Handcrafted solid walnut dining table seating six. Finished with a food-safe hardwax oil.',
                'price' => 1250.00,
                'stock' => 4,
            ],
            [
                'name' => 'Live-Edge Shelf',
                'category' => 'Furniture',
                'description' => 'A single live-edge oak shelf with concealed steel brackets. Every piece is unique.',
                'price' => 145.00,
                'stock' => 18,
            ],
            [
                'name' => 'Thermowood Decking Board',
                'category' => 'Decking',
                'description' => 'Thermally-modified pine decking, dimensionally stable and rot-resistant for outdoor use.',
                'price' => 27.75,
                'stock' => 500,
            ],
            [
                'name' => 'Western Red Cedar Cladding',
                'category' => 'Cladding',
                'description' => 'Naturally durable western red cedar cladding with a fine, even grain. Sold per linear metre.',
                'price' => 19.90,
                'stock' => 420,
            ],
            [
                'name' => 'Danish Oil 500ml',
                'category' => 'Accessories',
                'description' => 'Traditional Danish oil that penetrates deep to protect and enhance the natural colour of timber.',
                'price' => 14.25,
                'stock' => 200,
            ],
            [
                'name' => 'Beeswax Furniture Polish',
                'category' => 'Accessories',
                'description' => 'Pure beeswax and carnauba polish for a soft, lasting sheen on finished woodwork.',
                'price' => 9.99,
                'stock' => 0,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($product['name'])],
                $product,
            );
        }
    }
}

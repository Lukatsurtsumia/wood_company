<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = ucwords($this->faker->unique()->words(rand(2, 3), true));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => $this->faker->randomElement(Product::CATEGORIES),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 9.99, 999.99),
            'image_url' => null,
            'stock' => $this->faker->numberBetween(0, 250),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }
}

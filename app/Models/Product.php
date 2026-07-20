<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The product categories offered by the company.
     *
     * @var list<string>
     */
    public const CATEGORIES = [
        'Lumber',
        'Flooring',
        'Furniture',
        'Decking',
        'Cladding',
        'Accessories',
    ];

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'price',
        'image_url',
        'stock',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Keep the slug in sync with the name, and use it for route binding.
     */
    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug) || $product->isDirty('name')) {
                $product->slug = static::uniqueSlug($product->name, $product->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Generate a slug from the name that is unique across products.
     */
    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}

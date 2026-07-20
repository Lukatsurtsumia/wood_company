<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product = null;

    #[Validate]
    public string $name = '';

    #[Validate]
    public string $category = '';

    #[Validate]
    public string $description = '';

    #[Validate]
    public $price = '';

    #[Validate]
    public string $image_url = '';

    #[Validate]
    public $stock = 0;

    #[Validate]
    public bool $is_published = true;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ];
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;

        $this->name = $product->name;
        $this->category = $product->category;
        $this->description = (string) $product->description;
        $this->price = (string) $product->price;
        $this->image_url = (string) $product->image_url;
        $this->stock = $product->stock;
        $this->is_published = $product->is_published;
    }

    public function store(): Product
    {
        $data = $this->validate();

        return Product::create($data);
    }

    public function update(): Product
    {
        $data = $this->validate();

        $this->product->update($data);

        return $this->product;
    }
}

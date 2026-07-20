<?php

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component {
    public Product $product;

    public function mount(Product $product): void
    {
        abort_unless($product->is_published, 404);

        $this->product = $product;
    }

    public function with(): array
    {
        return [
            'related' => Product::query()
                ->published()
                ->where('category', $this->product->category)
                ->whereKeyNot($this->product->id)
                ->orderBy('name')
                ->take(4)
                ->get(),
        ];
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('products.index') }}" wire:navigate class="hover:text-amber-800">Products</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="aspect-[4/3] bg-amber-50 rounded-lg overflow-hidden">
            @if ($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-amber-200">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                    </svg>
                </div>
            @endif
        </div>

        <div>
            <p class="text-sm uppercase tracking-wide text-amber-700 font-medium">{{ $product->category }}</p>
            <h1 class="mt-2 text-3xl font-semibold text-gray-900">{{ $product->name }}</h1>
            <p class="mt-4 text-3xl font-bold text-gray-900">£{{ number_format($product->price, 2) }}</p>

            <div class="mt-4">
                @if ($product->in_stock)
                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-700">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        In stock ({{ $product->stock }} available)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        Out of stock
                    </span>
                @endif
            </div>

            @if ($product->description)
                <div class="mt-6 prose prose-sm text-gray-600 max-w-none">
                    <p>{{ $product->description }}</p>
                </div>
            @endif

            <a href="{{ route('products.index') }}" wire:navigate
               class="mt-8 inline-block text-amber-800 font-medium hover:underline">
                &larr; Back to all products
            </a>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">More in {{ $product->category }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related as $item)
                    <x-product-card :product="$item" wire:key="related-{{ $item->id }}" />
                @endforeach
            </div>
        </div>
    @endif
</div>

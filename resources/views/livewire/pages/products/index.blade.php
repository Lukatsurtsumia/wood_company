<?php

use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.public')] class extends Component {
    use WithPagination;

    #[Url(as: 'category', keep: false)]
    public string $category = '';

    #[Url(as: 'q', keep: false)]
    public string $search = '';

    public function updating($property): void
    {
        if (in_array($property, ['category', 'search'])) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('category', 'search');
        $this->resetPage();
    }

    public function with(): array
    {
        $products = Product::query()
            ->published()
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(12);

        return [
            'products' => $products,
            'categories' => Product::CATEGORIES,
        ];
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-gray-900">Our Timber &amp; Products</h1>
        <p class="mt-2 text-gray-500">Responsibly sourced wood, flooring, furniture and finishing supplies.</p>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
        <div class="relative flex-1 max-w-sm">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search products…"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
            >
        </div>

        <div class="flex flex-wrap gap-2">
            <button
                wire:click="$set('category', '')"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $category === '' ? 'bg-amber-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-amber-300' }}"
            >
                All
            </button>
            @foreach ($categories as $cat)
                <button
                    wire:click="$set('category', @js($cat))"
                    class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $category === $cat ? 'bg-amber-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-amber-300' }}"
                >
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>

    @if ($products->isEmpty())
        <div class="text-center py-24 bg-white rounded-lg border border-dashed border-gray-200">
            <p class="text-gray-500">No products match your filters.</p>
            <button wire:click="clearFilters" class="mt-3 text-amber-800 font-medium hover:underline">Clear filters</button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <x-product-card :product="$product" wire:key="product-{{ $product->id }}" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif
</div>

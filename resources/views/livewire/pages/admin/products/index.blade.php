<?php

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();

        $this->dispatch('product-deleted');
    }

    public function with(): array
    {
        return [
            'products' => Product::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(15),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
            <a href="{{ route('admin.products.create') }}" wire:navigate
               class="inline-flex items-center px-4 py-2 bg-amber-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition">
                New product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md px-4 py-2">
                    {{ session('status') }}
                </div>
            @endif

            <div
                x-data="{ shown: false }"
                x-on:product-deleted.window="shown = true; setTimeout(() => shown = false, 2500)"
                x-show="shown" x-cloak
                class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md px-4 py-2"
            >
                Product deleted.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-100">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search products…"
                        class="w-full max-w-xs rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm"
                    >
                </div>

                @if ($products->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        No products yet.
                        <a href="{{ route('admin.products.create') }}" wire:navigate class="text-amber-800 font-medium hover:underline">Add the first one.</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($products as $product)
                                    <tr wire:key="row-{{ $product->id }}" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $product->category }}</td>
                                        <td class="px-4 py-3 text-gray-600">£{{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $product->stock }}</td>
                                        <td class="px-4 py-3">
                                            @if ($product->is_published)
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            <a href="{{ route('admin.products.edit', $product) }}" wire:navigate
                                               class="text-amber-800 hover:underline font-medium">Edit</a>
                                            <button
                                                wire:click="delete({{ $product->id }})"
                                                wire:confirm="Delete “{{ $product->name }}”? This cannot be undone."
                                                class="ml-4 text-red-600 hover:underline font-medium"
                                            >Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

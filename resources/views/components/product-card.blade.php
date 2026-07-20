@props(['product'])

<a href="{{ route('products.show', $product) }}" wire:navigate
   class="group block bg-white rounded-lg border border-gray-100 overflow-hidden hover:shadow-md transition">
    <div class="aspect-[4/3] bg-amber-50 overflow-hidden">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-amber-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                </svg>
            </div>
        @endif
    </div>
    <div class="p-4">
        <p class="text-xs uppercase tracking-wide text-amber-700 font-medium">{{ $product->category }}</p>
        <h3 class="mt-1 font-medium text-gray-900 group-hover:text-amber-800">{{ $product->name }}</h3>
        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg font-semibold text-gray-900">£{{ number_format($product->price, 2) }}</span>
            @unless ($product->in_stock)
                <span class="text-xs font-medium text-red-500">Out of stock</span>
            @endunless
        </div>
    </div>
</a>

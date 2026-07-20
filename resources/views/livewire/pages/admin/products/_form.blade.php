<div class="grid grid-cols-1 gap-6">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="form.name" />
        <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="category" value="Category" />
            <select id="category" wire:model="form.category"
                    class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm">
                <option value="">Select a category…</option>
                @foreach (\App\Models\Product::CATEGORIES as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('form.category')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="price" value="Price (£)" />
            <x-text-input id="price" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model="form.price" />
            <x-input-error :messages="$errors->get('form.price')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" rows="4" wire:model="form.description"
                  class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm"></textarea>
        <x-input-error :messages="$errors->get('form.description')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="stock" value="Stock" />
            <x-text-input id="stock" type="number" min="0" class="mt-1 block w-full" wire:model="form.stock" />
            <x-input-error :messages="$errors->get('form.stock')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="image_url" value="Image URL (optional)" />
            <x-text-input id="image_url" type="url" class="mt-1 block w-full" wire:model="form.image_url" placeholder="https://…" />
            <x-input-error :messages="$errors->get('form.image_url')" class="mt-2" />
        </div>
    </div>

    <label class="inline-flex items-center gap-2">
        <input type="checkbox" wire:model="form.is_published"
               class="rounded border-gray-300 text-amber-800 shadow-sm focus:ring-amber-500">
        <span class="text-sm text-gray-700">Published (visible in the public catalog)</span>
    </label>
</div>

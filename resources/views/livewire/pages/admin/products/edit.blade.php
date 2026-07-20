<?php

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ProductForm $form;

    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->form->setProduct($product);
    }

    public function save(): void
    {
        $this->form->update();

        session()->flash('status', "“{$this->product->name}” updated.");

        $this->redirectRoute('admin.products.index', navigate: true);
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit product</h2>
            <a href="{{ route('products.show', $product) }}" wire:navigate target="_blank"
               class="text-sm text-amber-800 hover:underline">View public page &rarr;</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit="save" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                @include('livewire.pages.admin.products._form')

                <div class="flex items-center justify-end gap-4 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.products.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Save changes</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>

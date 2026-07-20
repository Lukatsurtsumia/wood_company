<?php

use App\Livewire\Forms\ProductForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ProductForm $form;

    public function save(): void
    {
        $product = $this->form->store();

        session()->flash('status', "“{$product->name}” created.");

        $this->redirectRoute('admin.products.index', navigate: true);
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit="save" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                @include('livewire.pages.admin.products._form')

                <div class="flex items-center justify-end gap-4 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.products.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Create product</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:180')]
    public string $email = '';

    #[Validate('nullable|string|max:150')]
    public string $subject = '';

    #[Validate('required|string|min:10|max:3000')]
    public string $message = '';

    public bool $sent = false;

    public function submit(): void
    {
        $data = $this->validate();

        $body = "New enquiry from the Wood Agency website\n\n"
            ."Name: {$data['name']}\n"
            ."Email: {$data['email']}\n"
            .'Subject: '.($data['subject'] ?: '(none)')."\n\n"
            .$data['message'];

        Mail::raw($body, function ($mail) use ($data) {
            $mail->to(config('mail.contact_to'))
                ->replyTo($data['email'], $data['name'])
                ->subject('Website enquiry'.($data['subject'] ? ': '.$data['subject'] : ''));
        });

        $this->reset('name', 'email', 'subject', 'message');
        $this->sent = true;
    }
}; ?>

<div>
    @if ($sent)
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-8 text-center" x-data x-init="setTimeout(() => $wire.set('sent', false), 6000)">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-emerald-900">Thanks, your message is on its way.</h3>
            <p class="mt-1 text-sm text-emerald-700">I read every enquiry personally and will get back to you within a day or two.</p>
            <button wire:click="$set('sent', false)" class="mt-4 text-sm font-medium text-emerald-800 underline">Send another message</button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="cf-name" class="block text-sm font-medium text-stone-700">Name</label>
                    <input id="cf-name" type="text" wire:model="name" autocomplete="name"
                           class="mt-1 block w-full rounded-lg border-stone-300 bg-white shadow-sm focus:border-amber-600 focus:ring-amber-600">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cf-email" class="block text-sm font-medium text-stone-700">Email</label>
                    <input id="cf-email" type="email" wire:model="email" autocomplete="email"
                           class="mt-1 block w-full rounded-lg border-stone-300 bg-white shadow-sm focus:border-amber-600 focus:ring-amber-600">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="cf-subject" class="block text-sm font-medium text-stone-700">Subject <span class="text-stone-400 font-normal">(optional)</span></label>
                <input id="cf-subject" type="text" wire:model="subject"
                       class="mt-1 block w-full rounded-lg border-stone-300 bg-white shadow-sm focus:border-amber-600 focus:ring-amber-600">
                @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cf-message" class="block text-sm font-medium text-stone-700">Tell me about your project</label>
                <textarea id="cf-message" rows="5" wire:model="message"
                          class="mt-1 block w-full rounded-lg border-stone-300 bg-white shadow-sm focus:border-amber-600 focus:ring-amber-600"></textarea>
                @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-amber-800 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2 disabled:opacity-60"
                    wire:loading.attr="disabled" wire:target="submit">
                <span wire:loading.remove wire:target="submit">Send enquiry</span>
                <span wire:loading wire:target="submit">Sending…</span>
                <svg wire:loading.remove wire:target="submit" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    @endif
</div>

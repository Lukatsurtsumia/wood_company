<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email:rfc|max:180')]
    public string $email = '';

    #[Validate('nullable|string|max:150')]
    public string $subject = '';

    #[Validate('required|string|min:10|max:3000')]
    public string $message = '';

    // Honeypot — real users never see or fill this. Bots often do.
    public string $website = '';

    public bool $sent = false;

    public function submit(): void
    {
        // Silently accept-and-drop obvious bot submissions.
        if ($this->website !== '') {
            $this->reset('name', 'email', 'subject', 'message', 'website');
            $this->sent = true;

            return;
        }

        // Throttle: max 3 messages per minute per visitor.
        $key = 'contact-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('throttle', 'Too many messages. Please wait '.RateLimiter::availableIn($key).' seconds and try again.');

            return;
        }

        $data = $this->validate();
        RateLimiter::hit($key, 60);

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
        <div class="rounded-2xl bg-wood-100 border border-wood-200 p-8 text-center" role="status" aria-live="polite"
             x-data x-init="setTimeout(() => $wire.set('sent', false), 8000)">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-brass-500 text-wood-950">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold text-wood-900">Thanks, your message is on its way.</h3>
            <p class="mt-1 text-sm text-wood-600">I read every enquiry personally and will get back to you within a day or two.</p>
            <button wire:click="$set('sent', false)" class="mt-4 text-sm font-medium text-brass-600 underline hover:text-brass-500">Send another message</button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5" novalidate>
            @error('throttle')
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700" role="alert">{{ $message }}</div>
            @enderror

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="cf-name" class="block text-sm font-medium text-wood-800">Name</label>
                    <input id="cf-name" type="text" wire:model.blur="name" autocomplete="name" required
                           aria-required="true" @error('name') aria-invalid="true" aria-describedby="cf-name-error" @enderror
                           class="mt-1 block w-full rounded-lg border-wood-300 bg-wood-50/40 shadow-sm focus:border-brass-500 focus:ring-brass-500">
                    @error('name') <p id="cf-name-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cf-email" class="block text-sm font-medium text-wood-800">Email</label>
                    <input id="cf-email" type="email" wire:model.blur="email" autocomplete="email" required
                           aria-required="true" @error('email') aria-invalid="true" aria-describedby="cf-email-error" @enderror
                           class="mt-1 block w-full rounded-lg border-wood-300 bg-wood-50/40 shadow-sm focus:border-brass-500 focus:ring-brass-500">
                    @error('email') <p id="cf-email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="cf-subject" class="block text-sm font-medium text-wood-800">Subject <span class="font-normal text-wood-400">(optional)</span></label>
                <input id="cf-subject" type="text" wire:model.blur="subject"
                       class="mt-1 block w-full rounded-lg border-wood-300 bg-wood-50/40 shadow-sm focus:border-brass-500 focus:ring-brass-500">
                @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cf-message" class="block text-sm font-medium text-wood-800">Tell me about your project</label>
                <textarea id="cf-message" rows="5" wire:model.blur="message" required aria-required="true"
                          @error('message') aria-invalid="true" aria-describedby="cf-message-error" @enderror
                          class="mt-1 block w-full rounded-lg border-wood-300 bg-wood-50/40 shadow-sm focus:border-brass-500 focus:ring-brass-500"></textarea>
                @error('message') <p id="cf-message-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Honeypot: hidden from people, tempting to bots --}}
            <div aria-hidden="true" class="absolute -left-[9999px] top-0 h-0 w-0 overflow-hidden" style="visibility:hidden">
                <label for="cf-website">Website (leave this blank)</label>
                <input id="cf-website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-wood-700 px-7 py-3 text-sm font-semibold text-wood-50 shadow-sm transition hover:bg-wood-800 focus:outline-none focus:ring-2 focus:ring-brass-500 focus:ring-offset-2 disabled:opacity-60"
                        wire:loading.attr="disabled" wire:target="submit">
                    <span wire:loading.remove wire:target="submit">Send enquiry</span>
                    <span wire:loading wire:target="submit">Sending…</span>
                    <svg wire:loading.remove wire:target="submit" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
                <p class="text-xs text-wood-400 sm:max-w-[15rem] sm:text-right">Your details are only used to reply to your enquiry — never shared.</p>
            </div>
        </form>
    @endif
</div>

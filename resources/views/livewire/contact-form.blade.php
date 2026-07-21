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

    // Honeypot - real users never see or fill this. Bots often do.
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
            $this->addError('throttle', __('Too many messages. Please wait :seconds seconds and try again.', ['seconds' => RateLimiter::availableIn($key)]));

            return;
        }

        $data = $this->validate();
        RateLimiter::hit($key, 60);

        // The name is already in the subject line and the sender column, so the
        // body carries it exactly once - with the message itself leading.
        // Only the query string is encoded - encoding the address itself breaks
        // the link in some mail clients.
        $mailto = 'mailto:'.$data['email']
            .'?subject='.rawurlencode('Re: '.($data['subject'] ?: 'your enquiry'));

        $html = '
<div style="background:#f4eee3;padding:32px 14px;font-family:Helvetica,Arial,sans-serif;">
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e4d9c6;">
    <tr>
      <td style="padding:26px 34px 20px;border-bottom:1px solid #e4d9c6;">
        <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#a8875a;">Wood Agency</div>
        <div style="font-family:Georgia,serif;font-size:23px;color:#2b2620;padding-top:5px;">New enquiry</div>
      </td>
    </tr>
    <tr>
      <td style="padding:28px 34px 26px;font-size:15px;line-height:1.75;color:#2b2620;white-space:pre-wrap;">'.e($data['message']).'</td>
    </tr>
    <tr>
      <td style="padding:0 34px;">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#faf7f2;border:1px solid #e4d9c6;">
          <tr>
            <td style="padding:18px 22px;">
              <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#a8875a;">Sent by</div>
              <div style="font-size:17px;color:#2b2620;padding-top:6px;">'.e($data['name']).'</div>
              <div style="font-size:14px;padding-top:3px;"><a href="mailto:'.e($data['email']).'" style="color:#8a6d43;text-decoration:none;">'.e($data['email']).'</a></div>
            </td>
            <td align="right" style="padding:18px 22px;">
              <a href="'.e($mailto).'" style="display:inline-block;background:#2b2620;color:#f4eee3;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;text-decoration:none;padding:12px 24px;">Reply</a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding:16px 34px 28px;font-size:12px;line-height:1.6;color:#9b9184;">
        Hitting reply in your mail app works too - it goes straight back to the sender.
      </td>
    </tr>
  </table>
</div>';

        // Gmail requires the From address to be the authenticated account, but
        // the display NAME is free - so show the visitor there. Otherwise every
        // enquiry lands in the inbox looking like it came from yourself.
        Mail::html($html, function ($mail) use ($data) {
            $mail->to(config('mail.contact_to'))
                ->from(config('mail.from.address'), $data['name'].' (Wood Agency)')
                ->replyTo($data['email'], $data['name'])
                ->subject($data['subject']
                    ? $data['name'].' - '.$data['subject']
                    : $data['name'].' - website enquiry');
        });

        $this->reset('name', 'email', 'subject', 'message');
        $this->sent = true;
    }
}; ?>

<div>
    @if ($sent)
        <div class="rounded-sm border border-hair bg-porcelain p-10 text-center" role="status" aria-live="polite"
             x-data x-init="setTimeout(() => $wire.set('sent', false), 8000)">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gold text-ivory">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="font-serif text-2xl font-medium text-espresso">{{ __('Thank you - your message is on its way.') }}</h3>
            <p class="mt-2 text-sm text-mocha">{{ __('I read every enquiry personally and will reply within a day or two.') }}</p>
            <button wire:click="$set('sent', false)" class="mt-5 text-xs font-semibold uppercase tracking-widest text-gold underline-offset-4 hover:text-golddark hover:underline">{{ __('Send another message') }}</button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5" novalidate>
            @error('throttle')
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700" role="alert">{{ $message }}</div>
            @enderror

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="cf-name" class="block text-xs font-semibold uppercase tracking-widest text-mocha">{{ __('Name') }}</label>
                    <input id="cf-name" type="text" wire:model.blur="name" autocomplete="name" required
                           aria-required="true" @error('name') aria-invalid="true" aria-describedby="cf-name-error" @enderror
                           class="mt-2 block w-full rounded-sm border-hair bg-porcelain text-espresso shadow-sm focus:border-gold focus:ring-gold">
                    @error('name') <p id="cf-name-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cf-email" class="block text-xs font-semibold uppercase tracking-widest text-mocha">{{ __('Email') }}</label>
                    <input id="cf-email" type="email" wire:model.blur="email" autocomplete="email" required
                           aria-required="true" @error('email') aria-invalid="true" aria-describedby="cf-email-error" @enderror
                           class="mt-2 block w-full rounded-sm border-hair bg-porcelain text-espresso shadow-sm focus:border-gold focus:ring-gold">
                    @error('email') <p id="cf-email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="cf-subject" class="block text-xs font-semibold uppercase tracking-widest text-mocha">{{ __('Subject') }} <span class="font-normal normal-case text-mocha/70">{{ __('(optional)') }}</span></label>
                <input id="cf-subject" type="text" wire:model.blur="subject"
                       class="mt-2 block w-full rounded-sm border-hair bg-porcelain text-espresso shadow-sm focus:border-gold focus:ring-gold">
                @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cf-message" class="block text-xs font-semibold uppercase tracking-widest text-mocha">{{ __('Tell me about your project') }}</label>
                <textarea id="cf-message" rows="5" wire:model.blur="message" required aria-required="true"
                          @error('message') aria-invalid="true" aria-describedby="cf-message-error" @enderror
                          class="mt-2 block w-full rounded-sm border-hair bg-porcelain text-espresso shadow-sm focus:border-gold focus:ring-gold"></textarea>
                @error('message') <p id="cf-message-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Honeypot: hidden from people, tempting to bots --}}
            <div aria-hidden="true" class="absolute -left-[9999px] top-0 h-0 w-0 overflow-hidden" style="visibility:hidden">
                <label for="cf-website">Website (leave this blank)</label>
                <input id="cf-website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-espresso px-8 py-3.5 text-xs font-semibold uppercase tracking-[0.2em] text-ivory transition hover:bg-golddark focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 disabled:opacity-60"
                        wire:loading.attr="disabled" wire:target="submit">
                    <span wire:loading.remove wire:target="submit">{{ __('Send enquiry') }}</span>
                    <span wire:loading wire:target="submit">{{ __('Sending…') }}</span>
                </button>
                <p class="text-xs text-mocha/80 sm:max-w-[15rem] sm:text-right">{{ __('Your details are only used to reply to your enquiry - never shared.') }}</p>
            </div>
        </form>
    @endif
</div>

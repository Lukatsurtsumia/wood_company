@php
    // ── Edit these to make the site your own ─────────────────────────────
    $name     = 'James Wood';
    $brand    = 'Wood Agency';
    $role     = 'Master Woodworker & Furniture Maker';
    $email    = 'hello@woodagency.test';
    $phone    = '+44 7700 900123';
    $location = 'Brighton, United Kingdom';

    // Helper to build a sized Unsplash image URL. Swap these for your own
    // project photos anytime (drop files in public/images and use /images/x.jpg).
    $u = fn (string $id, int $w, int $h) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&q=80&w={$w}&h={$h}";

    $slides = [
        ['id' => '1732801134112-23827e7cbd0d', 'eyebrow' => 'Handmade in Britain',        'title' => 'Furniture that lasts generations', 'text' => 'Bespoke pieces shaped by hand from responsibly sourced hardwood.'],
        ['id' => '1487015307662-6ce6210680f1', 'eyebrow' => 'From sketch to finish',        'title' => 'A craft honed over 15 years',      'text' => 'Every joint cut with intention. Every surface finished by hand.'],
        ['id' => '1712171984530-e25a4aaa46dd', 'eyebrow' => 'Available for commissions',    'title' => "Let's build something remarkable", 'text' => 'Kitchens, staircases, tables and one-off commissions.'],
    ];

    $projects = [
        ['id' => '1611486212557-88be5ff6f941', 'title' => 'Oak Bedside Table',  'cat' => 'Furniture',   'meta' => 'English Oak · 2024'],
        ['id' => '1611486212355-d276af4581c0', 'title' => 'Nesting Side Tables', 'cat' => 'Furniture',   'meta' => 'Black Walnut · 2024'],
        ['id' => '1653971858625-9cb23d0dca80', 'title' => 'Oak Sideboard',      'cat' => 'Joinery',     'meta' => 'Oak & Brass · 2023'],
        ['id' => '1611269154421-4e27233ac5c7', 'title' => 'Writing Desk',       'cat' => 'Furniture',   'meta' => 'Ash & Steel · 2023'],
        ['id' => '1516650556972-e9904734f467', 'title' => 'Round Dining Table', 'cat' => 'Furniture',   'meta' => 'Solid Oak · 2023'],
        ['id' => '1544691560-fc2053d97726',    'title' => 'Restored Dresser',   'cat' => 'Restoration', 'meta' => 'Mahogany · 2022'],
    ];

    $nav = ['Work' => '#work', 'About' => '#about', 'Contact' => '#contact'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $brand }} — {{ $role }}</title>
        <meta name="description" content="{{ $name }} — {{ $role }}. Bespoke handmade furniture and joinery. Available for commissions.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
        <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700|figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak]{ display:none !important; }
            body{ font-family:'Figtree', ui-sans-serif, system-ui, sans-serif; }
            @keyframes kenburns{ from{ transform:scale(1);} to{ transform:scale(1.10);} }
            .kenburns{ animation:kenburns 9s ease-out forwards; }
            section[id]{ scroll-margin-top:5rem; }
        </style>
    </head>
    <body class="bg-white text-wood-900 antialiased selection:bg-brass-300 selection:text-wood-950">

        {{-- ───────────────────────── Header ───────────────────────── --}}
        <header
            x-data="{ scrolled:false, mobile:false }"
            @scroll.window="scrolled = window.scrollY > 40"
            class="fixed inset-x-0 top-0 z-50 transition-colors duration-300"
            :class="scrolled ? 'bg-white/95 backdrop-blur border-b border-wood-100 shadow-sm' : 'bg-transparent'"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <a href="#top" class="flex items-center gap-2.5 font-display text-xl font-semibold"
                       :class="scrolled ? 'text-wood-900' : 'text-white'">
                        <svg class="h-7 w-7 text-brass-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                        </svg>
                        {{ $brand }}
                    </a>

                    <nav class="hidden items-center gap-9 md:flex">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-sm font-medium tracking-wide transition hover:text-brass-500"
                               :class="scrolled ? 'text-wood-600' : 'text-white/90'">{{ $label }}</a>
                        @endforeach
                        <a href="#contact"
                           class="rounded-full bg-wood-800 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-wood-900">
                            Hire me
                        </a>
                    </nav>

                    <button @click="mobile = !mobile" class="md:hidden" :class="scrolled ? 'text-wood-900' : 'text-white'" aria-label="Menu" :aria-expanded="mobile.toString()">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path x-show="!mobile" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                            <path x-show="mobile" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="mobile" x-cloak x-transition class="border-t border-wood-100 bg-white md:hidden">
                <nav class="space-y-1 px-6 py-4">
                    @foreach ($nav as $label => $href)
                        <a href="{{ $href }}" @click="mobile=false" class="block py-2 text-wood-700 hover:text-brass-600">{{ $label }}</a>
                    @endforeach
                    <a href="#contact" @click="mobile=false" class="mt-2 block rounded-full bg-wood-800 px-5 py-2.5 text-center font-semibold text-white">Hire me</a>
                </nav>
            </div>
        </header>

        {{-- ───────────────────────── Hero slideshow ───────────────────────── --}}
        <section id="top"
            x-data="{
                active: 0,
                total: {{ count($slides) }},
                timer: null,
                start(){ this.timer = setInterval(() => this.next(), 6000); },
                next(){ this.active = (this.active + 1) % this.total; },
                prev(){ this.active = (this.active - 1 + this.total) % this.total; },
                go(i){ this.active = i; clearInterval(this.timer); this.start(); }
            }"
            x-init="start()"
            class="relative h-screen min-h-[600px] w-full overflow-hidden bg-wood-900"
        >
            @foreach ($slides as $i => $slide)
                <div x-show="active === {{ $i }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-1000"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img src="{{ $u($slide['id'], 1920, 1080) }}" alt="" @if($i === 0) fetchpriority="high" @else loading="lazy" @endif
                         class="absolute inset-0 h-full w-full object-cover kenburns">
                    <div class="absolute inset-0 bg-gradient-to-t from-wood-950/85 via-wood-950/45 to-wood-950/25"></div>
                </div>
            @endforeach

            <div class="relative z-10 mx-auto flex h-full max-w-7xl items-center px-6 lg:px-8">
                <div class="max-w-2xl">
                    @foreach ($slides as $i => $slide)
                        <div x-show="active === {{ $i }}" x-cloak
                             x-transition:enter="transition ease-out duration-700 delay-200"
                             x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <p class="mb-4 flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.25em] text-brass-400">
                                <span class="h-px w-8 bg-brass-400"></span>{{ $slide['eyebrow'] }}
                            </p>
                            <h1 class="font-display text-4xl font-semibold leading-[1.1] text-white sm:text-6xl">{{ $slide['title'] }}</h1>
                            <p class="mt-6 max-w-xl text-lg text-white/85">{{ $slide['text'] }}</p>
                        </div>
                    @endforeach

                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <a href="#work" class="rounded-full bg-brass-500 px-7 py-3.5 text-sm font-semibold text-wood-950 shadow-lg transition hover:bg-brass-400">View my work</a>
                        <a href="#contact" class="rounded-full border border-white/40 px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">Start a project</a>
                    </div>
                </div>
            </div>

            <div class="absolute inset-x-0 bottom-8 z-10 mx-auto flex max-w-7xl items-center justify-between px-6 lg:px-8">
                <div class="flex gap-2.5">
                    @foreach ($slides as $i => $slide)
                        <button @click="go({{ $i }})" aria-label="Go to slide {{ $i + 1 }}"
                                class="h-1.5 rounded-full transition-all duration-300"
                                :class="active === {{ $i }} ? 'w-8 bg-brass-400' : 'w-4 bg-white/50 hover:bg-white/80'"></button>
                    @endforeach
                </div>
                <div class="hidden gap-3 sm:flex">
                    <button @click="prev()" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 text-white transition hover:bg-white/10" aria-label="Previous slide">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 text-white transition hover:bg-white/10" aria-label="Next slide">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Work / Portfolio ───────────────────────── --}}
        <section id="work" class="mx-auto max-w-7xl px-6 py-24 lg:px-8 lg:py-28">
            <div class="mx-auto mb-16 max-w-2xl text-center">
                <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-brass-600">Selected Work</p>
                <h2 class="font-display text-4xl font-semibold text-wood-900">Recent commissions</h2>
                <p class="mt-4 text-lg text-wood-500">A selection of bespoke pieces made for homes and businesses across the UK — each one designed, built and finished by hand.</p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $p)
                    <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-wood-900/5 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="aspect-[4/3] w-full overflow-hidden bg-wood-100">
                            <img src="{{ $u($p['id'], 800, 600) }}" alt="{{ $p['title'] }} — {{ $p['meta'] }}" loading="lazy"
                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-brass-600">{{ $p['cat'] }}</p>
                            <h3 class="mt-1 font-display text-lg font-medium text-wood-900">{{ $p['title'] }}</h3>
                            <p class="mt-1 text-sm text-wood-500">{{ $p['meta'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- ───────────────────────── About ───────────────────────── --}}
        <section id="about" class="bg-wood-50">
            <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-14 px-6 py-24 lg:grid-cols-2 lg:px-8 lg:py-28">
                <div class="relative">
                    <div class="overflow-hidden rounded-3xl shadow-xl ring-1 ring-wood-900/5">
                        <img src="{{ $u('1679797850019-3d0d8659a695', 900, 1100) }}" alt="{{ $name }} at work in the workshop"
                             class="aspect-[4/5] w-full object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-6 -right-4 hidden rounded-2xl bg-wood-800 px-8 py-6 text-white shadow-xl sm:block">
                        <p class="font-display text-4xl font-bold leading-none text-brass-400">15+</p>
                        <p class="mt-1 text-sm text-white/80">years at the bench</p>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-brass-600">About</p>
                    <h2 class="font-display text-4xl font-semibold leading-tight text-wood-900">Hello, I'm {{ $name }}.</h2>
                    <div class="mt-6 space-y-4 text-lg leading-relaxed text-wood-600">
                        <p>I'm a {{ strtolower($role) }} working from a small workshop in {{ $location }}. For over fifteen years I've been shaping raw timber into furniture and joinery designed to be used, loved, and passed on.</p>
                        <p>I work closely with each client — from the first sketch to the final coat of oil — so every piece fits its home and its owner perfectly. No flat-packs, no shortcuts; just honest materials and traditional craft.</p>
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-6 border-t border-wood-200 pt-8">
                        <div>
                            <p class="font-display text-3xl font-semibold text-wood-900">240+</p>
                            <p class="text-sm text-wood-500">pieces made</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl font-semibold text-wood-900">15</p>
                            <p class="text-sm text-wood-500">years' craft</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl font-semibold text-wood-900">100%</p>
                            <p class="text-sm text-wood-500">handmade</p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach (['Bespoke Furniture', 'Kitchens', 'Staircases', 'French Polishing', 'Steam Bending', 'Restoration'] as $skill)
                            <span class="rounded-full border border-wood-200 bg-white px-4 py-1.5 text-sm text-wood-600">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Contact ───────────────────────── --}}
        <section id="contact" class="mx-auto max-w-7xl px-6 py-24 lg:px-8 lg:py-28">
            <div class="grid grid-cols-1 gap-14 lg:grid-cols-2">
                <div>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-brass-600">Get in touch</p>
                    <h2 class="font-display text-4xl font-semibold leading-tight text-wood-900">Have a project in mind?</h2>
                    <p class="mt-5 max-w-md text-lg text-wood-600">Tell me a little about what you'd like made and I'll get back to you with some initial thoughts and a rough estimate — usually within a day or two.</p>

                    <dl class="mt-10 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-wood-100 text-wood-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-wood-400">Email</dt>
                                <dd><a href="mailto:{{ $email }}" class="font-medium text-wood-800 hover:text-brass-600">{{ $email }}</a></dd>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-wood-100 text-wood-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 005 5l1-2 5 2v2a2 2 0 01-2 2A16 16 0 013 5z"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-wood-400">Phone</dt>
                                <dd class="font-medium text-wood-800">{{ $phone }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-wood-100 text-wood-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-5.2-7-11a7 7 0 1114 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-wood-400">Workshop</dt>
                                <dd class="font-medium text-wood-800">{{ $location }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <div class="rounded-3xl border border-wood-100 bg-wood-50 p-8 shadow-sm">
                    <livewire:contact-form />
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Footer ───────────────────────── --}}
        <footer class="bg-wood-950 text-wood-200">
            <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                    <div>
                        <a href="#top" class="flex items-center gap-2.5 font-display text-xl font-semibold text-white">
                            <svg class="h-7 w-7 text-brass-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7"/></svg>
                            {{ $brand }}
                        </a>
                        <p class="mt-3 max-w-xs text-sm text-wood-300/80">{{ $role }} — bespoke, handmade, built to last.</p>
                    </div>

                    <nav class="flex flex-wrap gap-x-8 gap-y-2">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-sm text-wood-300 transition hover:text-brass-400">{{ $label }}</a>
                        @endforeach
                    </nav>

                    <div class="flex gap-3">
                        @foreach (['Instagram', 'Pinterest', 'LinkedIn'] as $social)
                            <a href="#" aria-label="{{ $social }}" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-wood-200 transition hover:border-brass-400 hover:text-brass-400">
                                <span class="text-xs font-semibold">{{ substr($social, 0, 2) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Built with Claude --}}
                <div class="mt-12 flex flex-col items-center gap-4 rounded-2xl border border-white/10 bg-white/5 px-6 py-5 text-center sm:flex-row sm:justify-between sm:text-left">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brass-500/20 text-brass-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l1.9 4.9L19 9.8l-4.1 2 -0.9 5.2L12 13.6 9.9 17l-0.9-5.2L5 9.8l5.1-1.9L12 3z"/></svg>
                        </span>
                        <p class="text-sm text-wood-100">Curious how this site was made? It was designed &amp; built with <span class="font-semibold text-white">Claude</span> — no agency required.</p>
                    </div>
                    <a href="https://claude.ai" target="_blank" rel="noopener"
                       class="shrink-0 rounded-full bg-white px-5 py-2 text-sm font-semibold text-wood-950 transition hover:bg-brass-300">
                        Build yours with Claude →
                    </a>
                </div>

                <div class="mt-8 flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-6 text-sm text-wood-400 sm:flex-row">
                    <p>&copy; {{ date('Y') }} {{ $brand }}. All rights reserved.</p>
                    <p>Handmade with care in {{ $location }}.</p>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>

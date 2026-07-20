@php
    // ── Edit these to make the site your own ─────────────────────────────
    $name    = 'James Wood';
    $brand   = 'Wood Agency';
    $role    = 'Master Woodworker & Furniture Maker';
    $email   = 'hello@woodagency.test';
    $phone   = '+44 7700 900123';
    $location = 'Brighton, United Kingdom';

    $slides = [
        [
            'img'     => 'https://picsum.photos/seed/woodgrain/1920/1080',
            'eyebrow' => 'Handmade in Britain',
            'title'   => 'Furniture that lasts generations',
            'text'    => 'Bespoke pieces shaped by hand from responsibly sourced hardwood.',
        ],
        [
            'img'     => 'https://picsum.photos/seed/workshop/1920/1080',
            'eyebrow' => 'From sketch to finish',
            'title'   => 'A craft honed over 15 years',
            'text'    => 'Every joint cut with intention. Every surface finished by hand.',
        ],
        [
            'img'     => 'https://picsum.photos/seed/timber/1920/1080',
            'eyebrow' => 'Available for commissions',
            'title'   => "Let's build something remarkable",
            'text'    => 'Kitchens, staircases, tables and one-off commissions.',
        ],
    ];

    $projects = [
        ['img' => 'https://picsum.photos/seed/proj-table/800/600',  'title' => 'Live-Edge Dining Table', 'cat' => 'Furniture'],
        ['img' => 'https://picsum.photos/seed/proj-kitchen/800/600','title' => 'Solid Oak Kitchen',      'cat' => 'Joinery'],
        ['img' => 'https://picsum.photos/seed/proj-stairs/800/600', 'title' => 'Floating Staircase',     'cat' => 'Architectural'],
        ['img' => 'https://picsum.photos/seed/proj-chair/800/600',  'title' => 'Steam-Bent Chair',       'cat' => 'Furniture'],
        ['img' => 'https://picsum.photos/seed/proj-desk/800/600',   'title' => 'Walnut Writing Desk',    'cat' => 'Furniture'],
        ['img' => 'https://picsum.photos/seed/proj-shelf/800/600',  'title' => 'Library Shelving',       'cat' => 'Joinery'],
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
        <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700|figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak]{ display:none !important; }
            .font-display{ font-family:'Fraunces', ui-serif, Georgia, serif; }
            body{ font-family:'Figtree', ui-sans-serif, system-ui, sans-serif; }
            @keyframes kenburns{ from{ transform:scale(1);} to{ transform:scale(1.12);} }
            .kenburns{ animation:kenburns 8s ease-out forwards; }
            section[id]{ scroll-margin-top:5rem; }
        </style>
    </head>
    <body class="bg-stone-50 text-stone-800 antialiased">

        {{-- ───────────────────────── Header ───────────────────────── --}}
        <header
            x-data="{ scrolled:false, mobile:false }"
            @scroll.window="scrolled = window.scrollY > 40"
            class="fixed inset-x-0 top-0 z-50 transition-colors duration-300"
            :class="scrolled ? 'bg-stone-50/95 backdrop-blur border-b border-stone-200 shadow-sm' : 'bg-transparent'"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <a href="#top" class="flex items-center gap-2.5 font-display text-xl font-semibold"
                       :class="scrolled ? 'text-stone-900' : 'text-white'">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                        </svg>
                        {{ $brand }}
                    </a>

                    <nav class="hidden items-center gap-9 md:flex">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-sm font-medium tracking-wide transition hover:text-amber-600"
                               :class="scrolled ? 'text-stone-600' : 'text-stone-100'">{{ $label }}</a>
                        @endforeach
                        <a href="#contact"
                           class="rounded-full bg-amber-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-800">
                            Hire me
                        </a>
                    </nav>

                    <button @click="mobile = !mobile" class="md:hidden" :class="scrolled ? 'text-stone-900' : 'text-white'" aria-label="Menu">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path x-show="!mobile" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                            <path x-show="mobile" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobile" x-cloak x-transition class="border-t border-stone-200 bg-stone-50 md:hidden">
                <nav class="space-y-1 px-6 py-4">
                    @foreach ($nav as $label => $href)
                        <a href="{{ $href }}" @click="mobile=false" class="block py-2 text-stone-700 hover:text-amber-700">{{ $label }}</a>
                    @endforeach
                    <a href="#contact" @click="mobile=false" class="mt-2 block rounded-full bg-amber-700 px-5 py-2.5 text-center font-semibold text-white">Hire me</a>
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
            class="relative h-screen min-h-[620px] w-full overflow-hidden"
        >
            @foreach ($slides as $i => $slide)
                <div x-show="active === {{ $i }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-1000"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <div class="absolute inset-0 bg-cover bg-center kenburns"
                         style="background-image:url('{{ $slide['img'] }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-900/55 to-stone-900/40"></div>
                </div>
            @endforeach

            {{-- Slide copy --}}
            <div class="relative z-10 mx-auto flex h-full max-w-7xl items-center px-6 lg:px-8">
                <div class="max-w-2xl">
                    @foreach ($slides as $i => $slide)
                        <div x-show="active === {{ $i }}" x-cloak
                             x-transition:enter="transition ease-out duration-700 delay-200"
                             x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-amber-400">{{ $slide['eyebrow'] }}</p>
                            <h1 class="font-display text-4xl font-semibold leading-tight text-white sm:text-6xl">{{ $slide['title'] }}</h1>
                            <p class="mt-6 max-w-xl text-lg text-stone-200">{{ $slide['text'] }}</p>
                        </div>
                    @endforeach

                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <a href="#work" class="rounded-full bg-amber-700 px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-amber-800">View my work</a>
                        <a href="#contact" class="rounded-full border border-white/40 px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">Start a project</a>
                    </div>
                </div>
            </div>

            {{-- Controls --}}
            <div class="absolute inset-x-0 bottom-8 z-10 mx-auto flex max-w-7xl items-center justify-between px-6 lg:px-8">
                <div class="flex gap-2.5">
                    @foreach ($slides as $i => $slide)
                        <button @click="go({{ $i }})" aria-label="Slide {{ $i + 1 }}"
                                class="h-1.5 rounded-full transition-all duration-300"
                                :class="active === {{ $i }} ? 'w-8 bg-amber-400' : 'w-4 bg-white/50 hover:bg-white/80'"></button>
                    @endforeach
                </div>
                <div class="hidden gap-3 sm:flex">
                    <button @click="prev()" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 text-white transition hover:bg-white/10" aria-label="Previous">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 text-white transition hover:bg-white/10" aria-label="Next">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Work / Portfolio ───────────────────────── --}}
        <section id="work" class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="mb-14 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Selected Work</p>
                    <h2 class="font-display text-4xl font-semibold text-stone-900">Recent commissions</h2>
                </div>
                <p class="max-w-sm text-stone-500">A small selection of bespoke pieces made for homes and businesses across the UK.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $p)
                    <div class="group relative overflow-hidden rounded-2xl bg-stone-200 shadow-sm">
                        <div class="aspect-[4/3] w-full overflow-hidden">
                            <img src="{{ $p['img'] }}" alt="{{ $p['title'] }}" loading="lazy"
                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/80 via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6">
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-300">{{ $p['cat'] }}</p>
                            <h3 class="mt-1 font-display text-xl font-medium text-white">{{ $p['title'] }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ───────────────────────── About ───────────────────────── --}}
        <section id="about" class="bg-stone-100">
            <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-14 px-6 py-24 lg:grid-cols-2 lg:px-8">
                <div class="relative">
                    <div class="overflow-hidden rounded-3xl shadow-xl">
                        <img src="https://picsum.photos/seed/craftsman/900/1100" alt="{{ $name }} in the workshop"
                             class="aspect-[4/5] w-full object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-4 hidden rounded-2xl bg-amber-700 px-8 py-6 text-white shadow-lg sm:block">
                        <p class="font-display text-4xl font-bold leading-none">15+</p>
                        <p class="mt-1 text-sm text-amber-100">years at the bench</p>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">About</p>
                    <h2 class="font-display text-4xl font-semibold leading-tight text-stone-900">Hello, I'm {{ $name }}.</h2>
                    <div class="mt-6 space-y-4 text-lg leading-relaxed text-stone-600">
                        <p>I'm a {{ strtolower($role) }} working from a small workshop in {{ $location }}. For over fifteen years I've been shaping raw timber into furniture and joinery designed to be used, loved, and passed on.</p>
                        <p>I work closely with each client — from the first sketch to the final coat of oil — so every piece fits its home and its owner perfectly. No flat-packs, no shortcuts; just honest materials and traditional craft.</p>
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-6 border-t border-stone-200 pt-8">
                        <div>
                            <p class="font-display text-3xl font-semibold text-stone-900">240+</p>
                            <p class="text-sm text-stone-500">pieces made</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl font-semibold text-stone-900">15</p>
                            <p class="text-sm text-stone-500">years' craft</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl font-semibold text-stone-900">100%</p>
                            <p class="text-sm text-stone-500">handmade</p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach (['Bespoke Furniture', 'Kitchens', 'Staircases', 'French Polishing', 'Steam Bending', 'Restoration'] as $skill)
                            <span class="rounded-full border border-stone-300 bg-white px-4 py-1.5 text-sm text-stone-600">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Contact ───────────────────────── --}}
        <section id="contact" class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="grid grid-cols-1 gap-14 lg:grid-cols-2">
                <div>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Get in touch</p>
                    <h2 class="font-display text-4xl font-semibold leading-tight text-stone-900">Have a project in mind?</h2>
                    <p class="mt-5 max-w-md text-lg text-stone-600">Tell me a little about what you'd like made and I'll get back to you with some initial thoughts and a rough estimate.</p>

                    <dl class="mt-10 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-stone-400">Email</dt>
                                <dd><a href="mailto:{{ $email }}" class="font-medium text-stone-800 hover:text-amber-700">{{ $email }}</a></dd>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 005 5l1-2 5 2v2a2 2 0 01-2 2A16 16 0 013 5z"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-stone-400">Phone</dt>
                                <dd class="font-medium text-stone-800">{{ $phone }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-5.2-7-11a7 7 0 1114 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-stone-400">Workshop</dt>
                                <dd class="font-medium text-stone-800">{{ $location }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm">
                    <livewire:contact-form />
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Footer ───────────────────────── --}}
        <footer class="bg-stone-900 text-stone-300">
            <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                    <div>
                        <a href="#top" class="flex items-center gap-2.5 font-display text-xl font-semibold text-white">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7"/></svg>
                            {{ $brand }}
                        </a>
                        <p class="mt-3 max-w-xs text-sm text-stone-400">{{ $role }} — bespoke, handmade, built to last.</p>
                    </div>

                    <nav class="flex flex-wrap gap-x-8 gap-y-2">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-sm text-stone-400 transition hover:text-amber-400">{{ $label }}</a>
                        @endforeach
                    </nav>

                    <div class="flex gap-3">
                        @foreach (['Instagram', 'Pinterest', 'LinkedIn'] as $social)
                            <a href="#" aria-label="{{ $social }}" class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-700 text-stone-400 transition hover:border-amber-500 hover:text-amber-400">
                                <span class="text-xs font-semibold">{{ substr($social, 0, 2) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-12 flex flex-col items-center justify-between gap-2 border-t border-stone-800 pt-6 text-sm text-stone-500 sm:flex-row">
                    <p>&copy; {{ date('Y') }} {{ $brand }}. All rights reserved.</p>
                    <p>Handmade with care in {{ $location }}.</p>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>

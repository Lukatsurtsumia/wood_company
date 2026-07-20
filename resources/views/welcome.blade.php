@php
    // ── Edit these to make the site your own ─────────────────────────────
    $name     = 'James Wood';
    $brand    = 'Wood Agency';
    $role     = 'Master Woodworker & Furniture Maker';
    $email    = 'hello@woodagency.test';
    $phone    = '+44 7700 900123';
    $location = 'Brighton, United Kingdom';

    // Sized Unsplash image URL helper — swap ids for your own project photos.
    $u = fn (string $id, int $w, int $h) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&q=80&w={$w}&h={$h}";

    $heroImg = '1732801134112-23827e7cbd0d'; // wall of chairs — editorial

    // First two are shown large; the rest form the numbered index.
    $projects = [
        ['id' => '1653971858625-9cb23d0dca80', 'title' => 'Oak Sideboard',      'cat' => 'Joinery',     'year' => '2023', 'blurb' => 'A wall-to-wall sideboard in quarter-sawn oak with hand-cut dovetails and cast-brass pulls.'],
        ['id' => '1516650556972-e9904734f467', 'title' => 'Round Dining Table',  'cat' => 'Furniture',   'year' => '2023', 'blurb' => 'A pedestal dining table in solid oak, shaped to seat six and finished in hardwax oil.'],
        ['id' => '1611486212557-88be5ff6f941', 'title' => 'Oak Bedside Table',   'cat' => 'Furniture',   'year' => '2024'],
        ['id' => '1611486212355-d276af4581c0', 'title' => 'Nesting Side Tables', 'cat' => 'Furniture',   'year' => '2024'],
        ['id' => '1611269154421-4e27233ac5c7', 'title' => 'Writing Desk',        'cat' => 'Furniture',   'year' => '2023'],
        ['id' => '1544691560-fc2053d97726',    'title' => 'Restored Dresser',    'cat' => 'Restoration', 'year' => '2022'],
    ];
    $featured = array_slice($projects, 0, 2);
    $index    = array_slice($projects, 2);

    $services = ['Bespoke Furniture', 'Fitted Kitchens', 'Staircases', 'Restoration', 'French Polishing', 'Steam Bending'];

    $nav = ['Work' => '#work', 'Studio' => '#about', 'Contact' => '#contact'];
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
            html, body{ overflow-x:clip; max-width:100%; }
            body{ font-family:'Figtree', ui-sans-serif, system-ui, sans-serif; background:#0c0a09; }
            .font-display{ font-family:'Fraunces', ui-serif, Georgia, serif; }
            section[id]{ scroll-margin-top:4rem; }

            .reveal{ opacity:0; transform:translateY(28px); transition:opacity .8s cubic-bezier(.2,.7,.2,1), transform .8s cubic-bezier(.2,.7,.2,1); }
            .reveal.is-in{ opacity:1; transform:none; }

            @keyframes marquee{ from{ transform:translateX(0);} to{ transform:translateX(-50%);} }
            .marquee-track{ display:flex; width:max-content; animation:marquee 32s linear infinite; }
            @media (prefers-reduced-motion: reduce){ .marquee-track{ animation:none; } .reveal{ opacity:1; transform:none; } }

            @keyframes kenburns{ from{ transform:scale(1);} to{ transform:scale(1.08);} }
            .kenburns{ animation:kenburns 12s ease-out forwards; }
        </style>
    </head>
    <body class="bg-stone-950 text-stone-200 antialiased selection:bg-brass-400 selection:text-stone-950">

        {{-- ───────────────────────── Header ───────────────────────── --}}
        <header
            x-data="{ scrolled:false, mobile:false }"
            @scroll.window="scrolled = window.scrollY > 40"
            class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="scrolled ? 'bg-stone-950/80 backdrop-blur border-b border-white/10' : 'border-b border-transparent'"
        >
            <div class="mx-auto max-w-[1400px] px-6 lg:px-10">
                <div class="flex h-16 items-center justify-between md:h-20">
                    <a href="#top" class="flex items-center gap-2.5 font-display text-lg font-semibold tracking-tight text-white">
                        <svg class="h-6 w-6 text-brass-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                        </svg>
                        {{ $brand }}
                    </a>

                    <nav class="hidden items-center gap-10 md:flex">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="group relative text-sm font-medium text-stone-300 transition hover:text-white">
                                {{ $label }}
                                <span class="absolute -bottom-1 left-0 h-px w-0 bg-brass-400 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        @endforeach
                        <a href="#contact" class="rounded-full border border-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:border-brass-400 hover:text-brass-300">
                            Start a project
                        </a>
                    </nav>

                    <button @click="mobile=!mobile" class="text-white md:hidden" aria-label="Menu" :aria-expanded="mobile.toString()">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path x-show="!mobile" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                            <path x-show="mobile" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>
            </div>
            <div x-show="mobile" x-cloak x-transition class="border-t border-white/10 bg-stone-950 md:hidden">
                <nav class="space-y-1 px-6 py-4">
                    @foreach ($nav as $label => $href)
                        <a href="{{ $href }}" @click="mobile=false" class="block py-2 text-stone-200 hover:text-brass-300">{{ $label }}</a>
                    @endforeach
                    <a href="#contact" @click="mobile=false" class="mt-2 block rounded-full border border-white/20 px-5 py-2.5 text-center font-semibold text-white">Start a project</a>
                </nav>
            </div>
        </header>

        {{-- ───────────────────────── Hero (editorial split) ───────────────────────── --}}
        <section id="top" class="relative overflow-hidden">
            {{-- oversized ghost word --}}
            <span aria-hidden="true" class="pointer-events-none absolute -left-4 bottom-10 select-none font-display text-[26vw] font-semibold leading-none text-white/[0.03] lg:text-[20vw]">craft</span>

            <div class="mx-auto grid max-w-[1400px] grid-cols-1 items-center gap-12 px-6 pb-16 pt-32 lg:grid-cols-12 lg:gap-10 lg:px-10 lg:pb-24 lg:pt-40">
                <div class="lg:col-span-6">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-medium text-stone-300">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brass-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-brass-400"></span>
                        </span>
                        Available for commissions — 2026
                    </div>

                    <h1 class="font-display text-5xl font-semibold leading-[0.98] tracking-tight text-white sm:text-7xl xl:text-8xl">
                        Timber,<br>shaped by<br><span class="italic text-brass-400">hand.</span>
                    </h1>

                    <p class="mt-8 max-w-md text-lg leading-relaxed text-stone-400">
                        I'm {{ $name }} — a {{ strtolower($role) }} in {{ $location }}, making heirloom furniture and joinery designed to be passed on.
                    </p>

                    <div class="mt-10 flex flex-wrap items-center gap-5">
                        <a href="#work" class="group inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-stone-950 transition hover:bg-brass-300">
                            View selected work
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a href="#contact" class="text-sm font-semibold text-stone-300 underline-offset-4 hover:text-white hover:underline">Start a commission</a>
                    </div>
                </div>

                <div class="relative lg:col-span-6">
                    <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] ring-1 ring-white/10 sm:aspect-[3/2] lg:aspect-[4/5]">
                        <img src="{{ $u($heroImg, 1100, 1300) }}" alt="A collection of handmade wooden chairs" fetchpriority="high"
                             class="h-full w-full object-cover kenburns">
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/60 to-transparent"></div>
                    </div>
                    {{-- floating stat card --}}
                    <div class="absolute -bottom-6 -left-4 rounded-2xl border border-white/10 bg-stone-900/90 px-6 py-5 backdrop-blur sm:-left-6">
                        <p class="font-display text-3xl font-semibold text-brass-400">240+</p>
                        <p class="text-xs uppercase tracking-wider text-stone-400">pieces delivered</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Marquee ───────────────────────── --}}
        <div class="overflow-hidden border-y border-white/10 bg-stone-900/50 py-5">
            <div class="marquee-track">
                @for ($k = 0; $k < 2; $k++)
                    @foreach ($services as $s)
                        <span class="mx-6 flex items-center gap-6 font-display text-xl italic text-stone-300 sm:text-2xl">
                            {{ $s }}
                            <svg class="h-3 w-3 text-brass-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
                        </span>
                    @endforeach
                @endfor
            </div>
        </div>

        {{-- ───────────────────────── Featured work (case-study rows) ───────────────────────── --}}
        <section id="work" class="mx-auto max-w-[1400px] px-6 py-24 lg:px-10 lg:py-32">
            <div class="reveal mb-16 flex flex-col justify-between gap-6 border-b border-white/10 pb-10 md:flex-row md:items-end">
                <h2 class="font-display text-4xl font-semibold tracking-tight text-white sm:text-6xl">Selected<br class="hidden sm:block"> work</h2>
                <p class="max-w-sm text-stone-400">A handful of recent commissions — each designed, built and finished by hand in the workshop.</p>
            </div>

            <div class="space-y-24 lg:space-y-32">
                @foreach ($featured as $i => $p)
                    <article class="reveal grid grid-cols-1 items-center gap-8 lg:grid-cols-2 lg:gap-16 {{ $i % 2 ? 'lg:[&>figure]:order-2' : '' }}">
                        <figure class="group overflow-hidden rounded-[1.75rem] ring-1 ring-white/10">
                            <img src="{{ $u($p['id'], 1000, 800) }}" alt="{{ $p['title'] }}" loading="lazy"
                                 class="aspect-[5/4] w-full object-cover transition duration-700 group-hover:scale-[1.04]">
                        </figure>
                        <div>
                            <div class="flex items-center gap-4 text-sm text-stone-400">
                                <span class="font-display text-brass-400">0{{ $i + 1 }}</span>
                                <span class="h-px w-10 bg-white/20"></span>
                                <span class="uppercase tracking-wider">{{ $p['cat'] }} · {{ $p['year'] }}</span>
                            </div>
                            <h3 class="mt-5 font-display text-3xl font-semibold text-white sm:text-5xl">{{ $p['title'] }}</h3>
                            <p class="mt-5 max-w-md text-lg leading-relaxed text-stone-400">{{ $p['blurb'] }}</p>
                            <a href="#contact" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-white underline-offset-4 hover:text-brass-300 hover:underline">
                                Commission something similar
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Numbered index with cursor-following preview --}}
            <div class="reveal mt-28" x-data="{ img:null, x:0, y:0 }" @mousemove.window="x=$event.clientX; y=$event.clientY">
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-brass-500">More commissions</p>
                <div class="divide-y divide-white/10 border-y border-white/10">
                    @foreach ($index as $i => $p)
                        <a href="#contact"
                           @mouseenter="img='{{ $u($p['id'], 600, 450) }}'" @mouseleave="img=null"
                           class="group flex items-center gap-5 py-6 transition-colors hover:bg-white/[0.02]">
                            <span class="font-display text-sm text-stone-500 group-hover:text-brass-400">0{{ $i + 3 }}</span>
                            {{-- mobile thumbnail --}}
                            <img src="{{ $u($p['id'], 160, 120) }}" alt="{{ $p['title'] }}" loading="lazy"
                                 class="h-14 w-20 shrink-0 rounded-lg object-cover ring-1 ring-white/10 lg:hidden">
                            <span class="flex-1 font-display text-2xl font-medium text-stone-200 transition group-hover:translate-x-2 group-hover:text-white sm:text-3xl">{{ $p['title'] }}</span>
                            <span class="hidden text-sm uppercase tracking-wider text-stone-500 sm:block">{{ $p['cat'] }}</span>
                            <span class="text-sm text-stone-500">{{ $p['year'] }}</span>
                            <svg class="h-5 w-5 text-stone-600 transition group-hover:text-brass-400" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" d="M7 17L17 7M17 7H8M17 7v9"/></svg>
                        </a>
                    @endforeach
                </div>

                <img x-show="img" x-cloak :src="img" alt=""
                     class="pointer-events-none fixed z-40 hidden h-64 w-80 -translate-x-1/2 -translate-y-1/2 rounded-2xl object-cover shadow-2xl ring-1 ring-white/20 lg:block"
                     :style="`left:${x}px; top:${y}px`">
            </div>
        </section>

        {{-- ───────────────────────── Studio / About ───────────────────────── --}}
        <section id="about" class="border-t border-white/10 bg-stone-900/40">
            <div class="mx-auto max-w-[1400px] px-6 py-24 lg:px-10 lg:py-32">
                <div class="grid grid-cols-1 gap-14 lg:grid-cols-12 lg:gap-16">
                    <div class="reveal lg:col-span-5">
                        <div class="overflow-hidden rounded-[1.75rem] ring-1 ring-white/10">
                            <img src="{{ $u('1679797850019-3d0d8659a695', 900, 1100) }}" alt="{{ $name }} at work in the workshop"
                                 class="aspect-[4/5] w-full object-cover" loading="lazy">
                        </div>
                    </div>
                    <div class="reveal lg:col-span-7 lg:pt-6">
                        <p class="mb-6 text-sm font-semibold uppercase tracking-[0.2em] text-brass-500">The Studio</p>
                        <p class="font-display text-3xl font-medium leading-snug text-white sm:text-4xl">
                            &ldquo;I build furniture the slow way — by hand, from a single log where I can, so each piece carries the grain of the tree it came from.&rdquo;
                        </p>
                        <div class="mt-8 max-w-xl space-y-4 text-lg leading-relaxed text-stone-400">
                            <p>For over fifteen years I've worked from a small workshop in {{ $location }}, shaping raw timber into heirloom furniture and joinery. I work closely with each client, from the first sketch to the final coat of oil.</p>
                        </div>

                        <div class="mt-12 grid grid-cols-2 gap-8 border-t border-white/10 pt-10 sm:grid-cols-4">
                            @foreach (['15' => 'years at the bench', '240+' => 'pieces made', '100%' => 'handmade', '1' => 'maker, start to finish'] as $stat => $label)
                                <div>
                                    <p class="font-display text-3xl font-semibold text-brass-400">{{ $stat }}</p>
                                    <p class="mt-1 text-sm text-stone-400">{{ $label }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-10 flex flex-wrap gap-2">
                            @foreach (['Bespoke Furniture', 'Kitchens', 'Staircases', 'French Polishing', 'Steam Bending', 'Restoration'] as $skill)
                                <span class="rounded-full border border-white/10 px-4 py-1.5 text-sm text-stone-300">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Contact ───────────────────────── --}}
        <section id="contact" class="mx-auto max-w-[1400px] px-6 py-24 lg:px-10 lg:py-32">
            <div class="grid grid-cols-1 gap-14 lg:grid-cols-12 lg:gap-16">
                <div class="reveal lg:col-span-5">
                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-brass-500">Get in touch</p>
                    <h2 class="font-display text-4xl font-semibold leading-tight text-white sm:text-6xl">Let's make<br>something<br><span class="italic text-brass-400">lasting.</span></h2>
                    <p class="mt-6 max-w-md text-lg text-stone-400">Tell me about your project and I'll come back with some initial thoughts and a rough estimate — usually within a day or two.</p>

                    <dl class="mt-10 space-y-4 text-stone-300">
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-brass-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                            </span>
                            <a href="mailto:{{ $email }}" class="hover:text-white">{{ $email }}</a>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-brass-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 005 5l1-2 5 2v2a2 2 0 01-2 2A16 16 0 013 5z"/></svg>
                            </span>
                            <span>{{ $phone }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-brass-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-5.2-7-11a7 7 0 1114 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            </span>
                            <span>{{ $location }}</span>
                        </div>
                    </dl>
                </div>

                <div class="reveal lg:col-span-7">
                    <div class="rounded-[1.75rem] border border-white/10 bg-stone-50 p-8 shadow-2xl sm:p-10">
                        <livewire:contact-form />
                    </div>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Footer ───────────────────────── --}}
        <footer class="border-t border-white/10 bg-stone-950">
            <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-10">
                <div class="flex flex-col items-start justify-between gap-10 md:flex-row md:items-center">
                    <div>
                        <a href="#top" class="flex items-center gap-2.5 font-display text-lg font-semibold text-white">
                            <svg class="h-6 w-6 text-brass-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7"/></svg>
                            {{ $brand }}
                        </a>
                        <p class="mt-3 max-w-xs text-sm text-stone-500">{{ $role }} — bespoke, handmade, built to last.</p>
                    </div>
                    <nav class="flex flex-wrap gap-x-8 gap-y-2">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-sm text-stone-400 transition hover:text-brass-400">{{ $label }}</a>
                        @endforeach
                    </nav>
                    <div class="flex gap-3">
                        @foreach (['Instagram', 'Pinterest', 'LinkedIn'] as $social)
                            <a href="#" aria-label="{{ $social }}" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-stone-300 transition hover:border-brass-400 hover:text-brass-400">
                                <span class="text-xs font-semibold">{{ substr($social, 0, 2) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Built with Claude --}}
                <div class="mt-14 flex flex-col items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.03] px-6 py-5 text-center sm:flex-row sm:justify-between sm:text-left">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brass-400/15 text-brass-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l1.9 4.9L19 9.8l-4.1 2 -0.9 5.2L12 13.6 9.9 17l-0.9-5.2L5 9.8l5.1-1.9L12 3z"/></svg>
                        </span>
                        <p class="text-sm text-stone-300">Curious how this site was made? It was designed &amp; built with <span class="font-semibold text-white">Claude</span> — no agency required.</p>
                    </div>
                    <a href="https://claude.ai" target="_blank" rel="noopener"
                       class="shrink-0 rounded-full bg-white px-5 py-2 text-sm font-semibold text-stone-950 transition hover:bg-brass-300">
                        Build yours with Claude →
                    </a>
                </div>

                <div class="mt-10 flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-6 text-sm text-stone-500 sm:flex-row">
                    <p>&copy; {{ date('Y') }} {{ $brand }}. All rights reserved.</p>
                    <p>Handmade with care in {{ $location }}.</p>
                </div>
            </div>
        </footer>

        <script>
            // Scroll-reveal
            (function () {
                var els = document.querySelectorAll('.reveal');
                if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('is-in'); }); return; }
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (en) {
                        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
                    });
                }, { threshold: 0.12 });
                els.forEach(function (e) { io.observe(e); });
            })();
        </script>

        @livewireScripts
    </body>
</html>

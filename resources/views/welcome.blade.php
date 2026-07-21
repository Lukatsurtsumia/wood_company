@php
    // ── Edit these to make the site your own ─────────────────────────────
    $name     = 'James Wood';
    $brand    = 'Wood Agency';
    $role     = 'Master Woodworker & Furniture Maker';
    $email    = 'hello@woodagency.test';
    $phone    = '+44 7700 900123';
    $location = 'Brighton, United Kingdom';

    // The About portrait uses a real photo (the maker at work).
    $aboutImg = '/images/wood-14.jpg';

    // HERO: professional woodworking stock photos (Unsplash CDN) for a
    // polished first impression. Real work is shown further down.
    $u = fn (string $id, int $w, int $h) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&q=80&w={$w}&h={$h}";
    $heroSlides = [
        '1712171984530-e25a4aaa46dd', // finishing a wood surface (warm)
        '1487015307662-6ce6210680f1', // solid-wood round table
        '1516650556972-e9904734f467', // wood table in a bright room
    ];

    // ── GALLERY (folder-driven, one folder per piece) ────────────────────
    // Each folder in public/images/gallery/ is one piece. The folder name is
    // the title (a leading "01-" just controls order). Put MORE than one photo
    // in a folder and its card becomes a mini-slideshow.
    // To add a piece: make a folder like "oak-console" and drop photo(s) in.
    $gallery = collect(glob(public_path('images/gallery/*'), GLOB_ONLYDIR))
        ->sort(SORT_NATURAL)
        ->map(function ($dir) {
            $slug = basename($dir);
            $images = collect(glob($dir.'/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG}', GLOB_BRACE))
                ->sort(SORT_NATURAL)
                ->map(fn ($p) => '/images/gallery/'.$slug.'/'.basename($p))
                ->values();

            return [
                'title'  => \Illuminate\Support\Str::title(str_replace('-', ' ', preg_replace('/^\d+-/', '', $slug))),
                'images' => $images,
            ];
        })
        ->filter(fn ($p) => $p['images']->isNotEmpty())
        ->values();

    // ── BEFORE & AFTER (folder-driven) ───────────────────────────────────
    // Drop matching pairs in public/images/before-after/ named:
    //   <sort>-<name>__before.jpg   and   <sort>-<name>__after.jpg
    // e.g. 4-oak-console__before.jpg / 4-oak-console__after.jpg
    $beforeAfter = collect(glob(public_path('images/before-after/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG}'), GLOB_BRACE))
        ->reduce(function ($pairs, $p) {
            $base = basename($p);
            if (preg_match('/^(.*)__(before|after)\.[A-Za-z]+$/', $base, $m)) {
                $pairs[$m[1]][$m[2]] = '/images/before-after/'.$base;
            }
            return $pairs;
        }, []);
    ksort($beforeAfter, SORT_NATURAL);
    $beforeAfter = collect($beforeAfter)
        ->filter(fn ($p) => isset($p['before'], $p['after']))
        ->map(function ($p, $key) {
            $dims = @getimagesize(public_path(ltrim($p['after'], '/'))) ?: [4, 3];
            return [
                'before' => $p['before'],
                'after'  => $p['after'],
                'title'  => \Illuminate\Support\Str::title(str_replace('-', ' ', preg_replace('/^\d+-/', '', $key))),
                'aspect' => $dims[1] >= $dims[0] ? 'aspect-[3/4]' : 'aspect-[4/3]',
            ];
        })
        ->values();

    $nav = ['Work' => '#work', 'Before & After' => '#restoration', 'Studio' => '#about', 'Contact' => '#contact'];
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
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,500i,600i|figtree:400,500,600&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak]{ display:none !important; }
            html, body{ overflow-x:clip; }
            body{ font-family:'Figtree', ui-sans-serif, system-ui, sans-serif; }
            .font-serif{ font-family:'Cormorant Garamond', ui-serif, Georgia, serif; }
            section[id]{ scroll-margin-top:5rem; }
            /* Fine film grain over the whole page — adds a tactile, printed feel */
            body::after{
                content:''; position:fixed; inset:0; pointer-events:none; z-index:100; opacity:.035;
                background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            }
            .reveal{ opacity:0; transform:translateY(20px); transition:opacity 1s ease, transform 1s ease; }
            .reveal.is-in{ opacity:1; transform:none; }
            @media (prefers-reduced-motion: reduce){ .reveal{ opacity:1; transform:none; } }
        </style>
    </head>
    <body class="bg-ivory text-espresso antialiased selection:bg-gold/25">

        {{-- ───────────────────────── Header ───────────────────────── --}}
        <header
            x-data="{ scrolled:false, mobile:false }"
            @scroll.window="scrolled = window.scrollY > 30"
            class="fixed inset-x-0 top-0 z-50 transition-all duration-500"
            :class="scrolled ? 'bg-ivory/90 backdrop-blur border-b border-hair' : 'bg-gradient-to-b from-espresso/75 via-espresso/35 to-transparent'"
        >
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <a href="#top" class="font-serif text-2xl font-semibold tracking-tight transition-colors" :class="scrolled ? 'text-espresso drop-shadow-none' : 'text-ivory [text-shadow:0_1px_6px_rgba(0,0,0,0.45)]'">{{ $brand }}</a>

                    <nav class="hidden items-center gap-10 md:flex" :class="scrolled ? '' : '[&_a]:[text-shadow:0_1px_6px_rgba(0,0,0,0.5)]'">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-xs font-medium uppercase tracking-[0.2em] transition-colors" :class="scrolled ? 'text-mocha hover:text-espresso' : 'text-ivory hover:text-white'">{{ $label }}</a>
                        @endforeach
                        <a href="#contact" class="text-xs font-medium uppercase tracking-[0.2em] transition-colors" :class="scrolled ? 'text-gold hover:text-golddark' : 'text-gold hover:text-ivory'">Enquire</a>
                    </nav>

                    <button @click="mobile=!mobile" class="transition-colors md:hidden" :class="scrolled ? 'text-espresso' : 'text-ivory'" aria-label="Menu" :aria-expanded="mobile.toString()">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path x-show="!mobile" stroke-linecap="round" d="M4 8h16M4 16h16" />
                            <path x-show="mobile" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>
            </div>
            <div x-show="mobile" x-cloak x-transition class="border-t border-hair bg-ivory md:hidden">
                <nav class="space-y-1 px-6 py-4">
                    @foreach ($nav as $label => $href)
                        <a href="{{ $href }}" @click="mobile=false" class="block py-2 text-sm uppercase tracking-[0.2em] text-mocha hover:text-espresso">{{ $label }}</a>
                    @endforeach
                </nav>
            </div>
        </header>

        {{-- ───────────────────────── Hero slideshow ───────────────────────── --}}
        <section id="top"
            x-data="{
                active: 0,
                total: {{ count($heroSlides) }},
                timer: null,
                start(){ this.timer = setInterval(() => this.next(), 6000); },
                next(){ this.active = (this.active + 1) % this.total; },
                go(i){ this.active = i; clearInterval(this.timer); this.start(); }
            }"
            x-init="start()"
            class="relative h-screen min-h-[600px] w-full overflow-hidden bg-espresso">

            @foreach ($heroSlides as $i => $sid)
                <div x-show="active === {{ $i }}"
                     x-transition:enter="transition ease-out duration-[1400ms]"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-[1400ms]"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img src="{{ $u($sid, 1920, 1080) }}" alt="" @if($i === 0) fetchpriority="high" @else loading="lazy" @endif
                         class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-espresso/85 via-espresso/55 to-espresso/45"></div>
                    <div class="absolute inset-0 bg-espresso/25"></div>
                </div>
            @endforeach

            <div class="relative z-10 mx-auto flex h-full max-w-4xl flex-col items-center justify-center px-6 text-center lg:px-8">
                <p class="mb-6 text-xs font-semibold uppercase tracking-[0.35em] text-brass-300 [text-shadow:0_1px_10px_rgba(0,0,0,0.7)]">Bespoke Furniture · Est. 2010</p>
                <h1 class="font-serif text-5xl font-medium leading-[1.05] text-ivory [text-shadow:0_2px_20px_rgba(0,0,0,0.6)] sm:text-7xl">
                    Furniture, made to<br>last a lifetime.
                </h1>
                <p class="mx-auto mt-7 max-w-xl text-lg leading-relaxed text-ivory [text-shadow:0_1px_10px_rgba(0,0,0,0.7)]">
                    I'm {{ $name }} — a {{ strtolower($role) }} in {{ $location }}, crafting heirloom pieces by hand, from a single sketch to the final coat of oil.
                </p>
                <div class="mt-9 flex items-center justify-center gap-8">
                    <a href="#work" class="rounded-full border border-ivory/50 px-8 py-3.5 text-xs font-semibold uppercase tracking-[0.2em] text-ivory transition hover:bg-ivory hover:text-espresso">
                        View the Collection
                    </a>
                    <a href="#contact" class="text-xs font-semibold uppercase tracking-[0.2em] text-ivory underline-offset-8 transition hover:underline">Enquire</a>
                </div>
            </div>

            {{-- slide dots --}}
            <div class="absolute inset-x-0 bottom-10 z-10 flex justify-center gap-3">
                @foreach ($heroSlides as $i => $sid)
                    <button @click="go({{ $i }})" aria-label="Go to slide {{ $i + 1 }}"
                            class="h-1.5 rounded-full transition-all duration-300"
                            :class="active === {{ $i }} ? 'w-8 bg-gold' : 'w-4 bg-ivory/50 hover:bg-ivory/80'"></button>
                @endforeach
            </div>
        </section>

        {{-- ───────────────────────── Work / Collection ───────────────────────── --}}
        <section id="work" class="border-t border-hair bg-ivory py-24 lg:py-32">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="reveal mx-auto mb-16 max-w-2xl text-center">
                    <p class="mb-4 text-xs font-medium uppercase tracking-[0.35em] text-gold">Selected Work</p>
                    <h2 class="font-serif text-4xl font-medium text-espresso sm:text-5xl">The Collection</h2>
                    <p class="mt-5 text-lg leading-relaxed text-mocha">From bespoke builds to fine antique restoration — a gallery of recent work, each piece made or restored by hand.</p>
                    <div class="mt-8 flex items-center justify-center gap-3" aria-hidden="true">
                        <span class="h-px w-14 bg-gradient-to-r from-transparent to-gold/50"></span>
                        <span class="h-1.5 w-1.5 rotate-45 bg-gold"></span>
                        <span class="h-px w-14 bg-gradient-to-l from-transparent to-gold/50"></span>
                    </div>
                </div>

                {{-- Masonry gallery — each folder in public/images/gallery/ is a card here --}}
                <div class="columns-1 gap-6 [column-fill:balance] sm:columns-2 lg:columns-3 xl:columns-4">
                    @foreach ($gallery as $p)
                        <figure class="group mb-6 break-inside-avoid">
                            @if ($p['images']->count() > 1)
                                {{-- multi-photo piece → mini slideshow --}}
                                <div x-data="{ i: 0, n: {{ $p['images']->count() }}, t: null,
                                               play(){ this.t = setInterval(() => this.i = (this.i + 1) % this.n, 3200); },
                                               stop(){ clearInterval(this.t); } }"
                                     x-init="play()" @mouseenter="stop()" @mouseleave="play()"
                                     class="relative aspect-[4/5] overflow-hidden rounded-sm bg-hair ring-1 ring-espresso/[0.08] transition-shadow duration-500 group-hover:shadow-[0_22px_45px_-24px_rgba(43,38,32,0.45)]">
                                    @foreach ($p['images'] as $k => $img)
                                        <img src="{{ $img }}" alt="{{ $p['title'] }}" loading="lazy"
                                             x-show="i === {{ $k }}"
                                             x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                             class="absolute inset-0 h-full w-full object-cover">
                                    @endforeach
                                    <div class="absolute inset-x-0 bottom-3 z-10 flex justify-center gap-1.5">
                                        @foreach ($p['images'] as $k => $img)
                                            <button type="button" @click="i = {{ $k }}" aria-label="Photo {{ $k + 1 }}"
                                                    class="h-1.5 rounded-full transition-all duration-300"
                                                    :class="i === {{ $k }} ? 'w-4 bg-ivory' : 'w-1.5 bg-ivory/60'"></button>
                                        @endforeach
                                    </div>
                                    <span class="pointer-events-none absolute right-3 top-3 rounded-full bg-espresso/60 px-2 py-0.5 text-[10px] font-semibold text-ivory">{{ $p['images']->count() }} photos</span>
                                </div>
                            @else
                                <div class="overflow-hidden rounded-sm bg-hair ring-1 ring-espresso/[0.08] transition-shadow duration-500 group-hover:shadow-[0_22px_45px_-24px_rgba(43,38,32,0.45)]">
                                    <img src="{{ $p['images'][0] }}" alt="{{ $p['title'] }}" loading="lazy"
                                         class="w-full transition duration-[1400ms] ease-out group-hover:scale-[1.05]">
                                </div>
                            @endif
                            <figcaption class="mt-4 text-center">
                                <span class="mx-auto mb-2 block h-px w-6 bg-gold/60 transition-all duration-500 group-hover:w-12"></span>
                                <h3 class="font-serif text-lg font-medium tracking-wide text-espresso transition-colors duration-300 group-hover:text-gold">{{ $p['title'] }}</h3>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Before & After ───────────────────────── --}}
        <section id="restoration" class="relative overflow-hidden bg-noir py-24 lg:py-32">
            {{-- soft warm glow for depth --}}
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_50%_0%,rgba(168,135,90,0.16),transparent_60%)]"></div>
            <div class="relative mx-auto max-w-6xl px-6 lg:px-8">
                <div class="reveal mx-auto mb-16 max-w-2xl text-center">
                    <p class="mb-4 text-xs font-medium uppercase tracking-[0.35em] text-goldlight">From Rough to Refined</p>
                    <h2 class="font-serif text-4xl font-medium text-ivory sm:text-5xl">Before &amp; After</h2>
                    <p class="mt-5 text-lg leading-relaxed text-ivory/65">Drag the handle to see each piece transformed — from raw timber and tired carcasses to finished, heirloom furniture.</p>
                    <div class="mt-8 flex items-center justify-center gap-3" aria-hidden="true">
                        <span class="h-px w-14 bg-gradient-to-r from-transparent to-goldlight/60"></span>
                        <span class="h-1.5 w-1.5 rotate-45 bg-goldlight"></span>
                        <span class="h-px w-14 bg-gradient-to-l from-transparent to-goldlight/60"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-10 gap-y-14 sm:grid-cols-2">
                    @foreach ($beforeAfter as $i => $ba)
                        <div class="reveal">
                            <figure>
                                <div x-data="{
                                        pos: 50, drag: false,
                                        move(e){ const r = this.$el.getBoundingClientRect(); const x = (e.touches ? e.touches[0].clientX : e.clientX); this.pos = Math.min(100, Math.max(0, ((x - r.left) / r.width) * 100)); }
                                     }"
                                     @pointerdown="drag = true; move($event)"
                                     @pointermove="drag && move($event)"
                                     @pointerup.window="drag = false"
                                     class="group relative {{ $ba['aspect'] ?? 'aspect-[4/3]' }} mx-auto w-full max-w-[280px] cursor-ew-resize touch-none select-none overflow-hidden rounded-sm bg-espresso ring-1 ring-goldlight/25 shadow-[0_28px_60px_-28px_rgba(0,0,0,0.85)]">
                                    {{-- After (full) --}}
                                    <img src="{{ $ba['after'] }}" alt="{{ $ba['title'] }} — after" draggable="false"
                                         class="pointer-events-none absolute inset-0 h-full w-full object-cover" loading="lazy">
                                    <span class="pointer-events-none absolute right-4 top-4 rounded-full bg-espresso/70 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-ivory">After</span>
                                    {{-- Before (clipped) --}}
                                    <div class="pointer-events-none absolute inset-0" :style="`clip-path: inset(0 ${100 - pos}% 0 0)`">
                                        <img src="{{ $ba['before'] }}" alt="{{ $ba['title'] }} — before" draggable="false"
                                             class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                                        <span class="absolute left-4 top-4 rounded-full bg-espresso/70 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-ivory">Before</span>
                                    </div>
                                    {{-- Handle --}}
                                    <div class="pointer-events-none absolute inset-y-0 z-10 flex w-0 items-center justify-center" :style="`left: ${pos}%`">
                                        <div class="absolute inset-y-0 w-0.5 bg-ivory/90"></div>
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full border border-gold/60 bg-ivory shadow-lg ring-2 ring-white/40">
                                            <svg class="h-5 w-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-4 3 4 3M16 9l4 3-4 3"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </figure>
                            <figcaption class="mt-5 text-center">
                                <span class="mx-auto mb-3 block h-px w-8 bg-goldlight/70"></span>
                                <p class="mb-1.5 text-xs font-medium uppercase tracking-[0.3em] text-goldlight">0{{ $i + 1 }} — Restoration</p>
                                <h3 class="font-serif text-2xl font-medium tracking-wide text-ivory">{{ $ba['title'] }}</h3>
                                <p class="mt-1.5 text-sm text-ivory/50">Drag the handle to reveal the transformation.</p>
                            </figcaption>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Studio / About ───────────────────────── --}}
        <section id="about" class="border-t border-hair bg-porcelain py-24 lg:py-32">
            <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-14 px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">
                <div class="reveal">
                    <div class="overflow-hidden rounded-sm bg-hair">
                        <img src="{{ $aboutImg }}" alt="{{ $name }} at work in the workshop"
                             class="aspect-[4/5] w-full object-cover" loading="lazy">
                    </div>
                </div>
                <div class="reveal">
                    <p class="mb-6 text-xs font-medium uppercase tracking-[0.35em] text-gold">The Studio</p>
                    <p class="font-serif text-3xl font-medium italic leading-snug text-espresso sm:text-4xl">
                        &ldquo;Every piece begins with a single log — and the patience to let its grain lead the way.&rdquo;
                    </p>
                    <div class="mt-8 space-y-4 leading-relaxed text-mocha">
                        <p>For over fifteen years I've worked from a small workshop in {{ $location }}, shaping raw timber into furniture and joinery made to be used, loved and passed on. I work closely with each client, from the first drawing to the final finish.</p>
                        <p>No flat-packs, no shortcuts — only honest materials and traditional craft.</p>
                    </div>

                    <div class="mt-10 flex divide-x divide-hair border-y border-hair">
                        @foreach (['15 yrs' => 'at the bench', '240+' => 'pieces made', '100%' => 'by hand'] as $stat => $label)
                            <div class="flex-1 py-5 pr-6 first:pl-0 [&:not(:first-child)]:pl-6">
                                <p class="font-serif text-3xl font-medium text-espresso">{{ $stat }}</p>
                                <p class="mt-1 text-xs uppercase tracking-widest text-mocha">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>

                    <p class="mt-8 font-serif text-3xl italic text-espresso">{{ $name }}</p>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Contact ───────────────────────── --}}
        <section id="contact" class="border-t border-hair bg-ivory py-24 lg:py-32">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-14 lg:grid-cols-2 lg:gap-20">
                    <div class="reveal">
                        <p class="mb-5 text-xs font-medium uppercase tracking-[0.35em] text-gold">Enquiries</p>
                        <h2 class="font-serif text-4xl font-medium leading-tight text-espresso sm:text-5xl">Commission<br>a piece.</h2>
                        <p class="mt-6 max-w-md text-lg leading-relaxed text-mocha">Tell me a little about what you have in mind and I'll reply with some initial thoughts and a rough estimate — usually within a day or two.</p>

                        <div class="mt-10 space-y-4 border-t border-hair pt-8 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="uppercase tracking-widest text-mocha">Email</span>
                                <a href="mailto:{{ $email }}" class="text-espresso hover:text-gold">{{ $email }}</a>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="uppercase tracking-widest text-mocha">Phone</span>
                                <span class="text-espresso">{{ $phone }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="uppercase tracking-widest text-mocha">Workshop</span>
                                <span class="text-espresso">{{ $location }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="reveal">
                        <livewire:contact-form />
                    </div>
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Footer ───────────────────────── --}}
        <footer class="bg-espresso text-ivory/80">
            <div class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
                <div class="flex flex-col items-center gap-8 text-center">
                    <a href="#top" class="font-serif text-3xl font-semibold text-ivory">{{ $brand }}</a>
                    <nav class="flex flex-wrap justify-center gap-x-8 gap-y-2">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-xs uppercase tracking-[0.2em] text-ivory/70 transition hover:text-gold">{{ $label }}</a>
                        @endforeach
                    </nav>
                    <div class="flex gap-6 text-xs uppercase tracking-[0.2em] text-ivory/60">
                        @foreach (['Instagram', 'Pinterest', 'LinkedIn'] as $social)
                            <a href="#" class="transition hover:text-gold">{{ $social }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-14 flex flex-col items-center justify-between gap-3 border-t border-white/10 pt-8 text-xs text-ivory/50 sm:flex-row">
                    <p>&copy; {{ date('Y') }} {{ $brand }}. All rights reserved.</p>
                    <p>Designed &amp; built with <a href="https://claude.ai" target="_blank" rel="noopener" class="text-ivory/80 underline-offset-4 hover:text-gold hover:underline">Claude</a> — build yours at claude.ai</p>
                </div>
            </div>
        </footer>

        <script>
            (function () {
                var els = document.querySelectorAll('.reveal');
                if (!('IntersectionObserver' in window)) { els.forEach(function (e){ e.classList.add('is-in'); }); return; }
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
                }, { threshold: 0.1 });
                els.forEach(function (e){ io.observe(e); });
            })();
        </script>

        @livewireScripts
    </body>
</html>

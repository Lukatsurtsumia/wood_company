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

    $heroImg = '1653971858625-9cb23d0dca80'; // oak sideboard, bright & airy

    $projects = [
        ['id' => '1611486212557-88be5ff6f941', 'title' => 'Oak Bedside Table',   'meta' => 'English Oak · 2024'],
        ['id' => '1611486212355-d276af4581c0', 'title' => 'Nesting Side Tables',  'meta' => 'Black Walnut · 2024'],
        ['id' => '1516650556972-e9904734f467', 'title' => 'Round Dining Table',   'meta' => 'Solid Oak · 2023'],
        ['id' => '1611269154421-4e27233ac5c7', 'title' => 'Writing Desk',         'meta' => 'Ash & Steel · 2023'],
        ['id' => '1487015307662-6ce6210680f1', 'title' => 'Walnut Coffee Table',  'meta' => 'Black Walnut · 2023'],
        ['id' => '1544691560-fc2053d97726',    'title' => 'Restored Dresser',     'meta' => 'Mahogany · 2022'],
    ];

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
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,500i,600i|figtree:400,500,600&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak]{ display:none !important; }
            html, body{ overflow-x:clip; }
            body{ font-family:'Figtree', ui-sans-serif, system-ui, sans-serif; }
            .font-serif{ font-family:'Cormorant Garamond', ui-serif, Georgia, serif; }
            section[id]{ scroll-margin-top:5rem; }
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
            :class="scrolled ? 'bg-ivory/90 backdrop-blur border-b border-hair' : ''"
        >
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <a href="#top" class="font-serif text-2xl font-semibold tracking-tight text-espresso">{{ $brand }}</a>

                    <nav class="hidden items-center gap-10 md:flex">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="text-xs font-medium uppercase tracking-[0.2em] text-mocha transition hover:text-espresso">{{ $label }}</a>
                        @endforeach
                        <a href="#contact" class="text-xs font-medium uppercase tracking-[0.2em] text-gold transition hover:text-golddark">Enquire</a>
                    </nav>

                    <button @click="mobile=!mobile" class="text-espresso md:hidden" aria-label="Menu" :aria-expanded="mobile.toString()">
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

        {{-- ───────────────────────── Hero ───────────────────────── --}}
        <section id="top" class="bg-ivory">
            <div class="mx-auto max-w-3xl px-6 pb-14 pt-36 text-center lg:pt-44">
                <p class="mb-6 text-xs font-medium uppercase tracking-[0.35em] text-gold">Bespoke Furniture · Est. 2010</p>
                <h1 class="font-serif text-5xl font-medium leading-[1.05] text-espresso sm:text-7xl">
                    Furniture, made to<br>last a lifetime.
                </h1>
                <p class="mx-auto mt-7 max-w-xl text-lg leading-relaxed text-mocha">
                    I'm {{ $name }} — a {{ strtolower($role) }} in {{ $location }}, crafting heirloom pieces by hand, from a single sketch to the final coat of oil.
                </p>
                <div class="mt-9 flex items-center justify-center gap-8">
                    <a href="#work" class="rounded-full border border-espresso/25 px-8 py-3.5 text-xs font-semibold uppercase tracking-[0.2em] text-espresso transition hover:bg-espresso hover:text-ivory">
                        View the Collection
                    </a>
                    <a href="#contact" class="text-xs font-semibold uppercase tracking-[0.2em] text-gold underline-offset-8 transition hover:text-golddark hover:underline">Enquire</a>
                </div>
            </div>

            <div class="mx-auto max-w-6xl px-6 pb-24 lg:px-8">
                <div class="overflow-hidden rounded-sm">
                    <img src="{{ $u($heroImg, 1600, 900) }}" alt="A handmade oak sideboard in a bright, calm interior" fetchpriority="high"
                         class="aspect-[16/9] w-full object-cover">
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Work / Collection ───────────────────────── --}}
        <section id="work" class="border-t border-hair bg-ivory py-24 lg:py-32">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="reveal mx-auto mb-16 max-w-2xl text-center">
                    <p class="mb-4 text-xs font-medium uppercase tracking-[0.35em] text-gold">Selected Work</p>
                    <h2 class="font-serif text-4xl font-medium text-espresso sm:text-5xl">The Collection</h2>
                    <p class="mt-5 text-lg leading-relaxed text-mocha">A selection of recent commissions — each one designed, built and finished entirely by hand.</p>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $p)
                        <figure class="group reveal">
                            <div class="overflow-hidden rounded-sm bg-hair">
                                <img src="{{ $u($p['id'], 700, 875) }}" alt="{{ $p['title'] }} — {{ $p['meta'] }}" loading="lazy"
                                     class="aspect-[4/5] w-full object-cover transition duration-[1200ms] ease-out group-hover:scale-[1.05]">
                            </div>
                            <figcaption class="mt-5 text-center">
                                <h3 class="font-serif text-2xl font-medium text-espresso">{{ $p['title'] }}</h3>
                                <p class="mt-1 text-sm tracking-wide text-mocha">{{ $p['meta'] }}</p>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ───────────────────────── Studio / About ───────────────────────── --}}
        <section id="about" class="border-t border-hair bg-porcelain py-24 lg:py-32">
            <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-14 px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">
                <div class="reveal">
                    <div class="overflow-hidden rounded-sm bg-hair">
                        <img src="{{ $u('1679797850019-3d0d8659a695', 900, 1080) }}" alt="{{ $name }} at work in the workshop"
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

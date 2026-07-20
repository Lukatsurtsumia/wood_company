# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

"Wood Company" is a Laravel 13 application scaffolded from Laravel Breeze (Livewire/Volt stack). It is currently at skeleton stage: authentication (register/login/password reset/email verification), a profile page, and a dashboard are wired up, but no application-specific domain models, controllers, or Livewire components have been added yet beyond what Breeze generates.

## Commands

### Environment setup
- `composer install && npm install` — install PHP and JS dependencies
- `cp .env.example .env && php artisan key:generate` — first-time env setup (an `.env` already exists in this repo)
- MySQL is provided via Docker Compose: `docker compose up -d` starts a `mysql:8.4` container on port 3306 matching the `.env` credentials (db `wood_company`, user `root`/`root`)
- `php artisan migrate` — run migrations against the configured DB

### Running the app
- `composer dev` — runs the full local dev stack concurrently: `php artisan serve`, `php artisan queue:listen`, `php artisan pail` (log tailing), and `npm run dev` (Vite)
- `npm run dev` — Vite dev server only
- `npm run build` — production asset build

### Testing
- `composer test` or `php artisan test` — run the full PHPUnit suite (this clears config cache first)
- `php artisan test --filter=TestName` — run a single test by method/class name
- `vendor/bin/phpunit tests/Feature/ProfileTest.php` — run a single test file directly
- Tests use PHPUnit (not Pest), configured in `phpunit.xml`. The test environment forces `DB_CONNECTION=sqlite` with `DB_DATABASE=:memory:`, `CACHE_STORE=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync` — tests never touch the local MySQL database.

### Code style
- `vendor/bin/pint` — run Laravel Pint (PHP code style fixer) over the codebase

## Architecture

### Livewire Volt for pages, classic Livewire for components
This app mixes two Livewire authoring styles — know which one you're editing:
- **Volt single-file components** live in `resources/views/livewire/pages/**` (e.g. `pages/auth/login.blade.php`). These combine PHP logic and Blade markup in one file using `Volt::route(...)` registered in `routes/auth.php`. All auth pages (login, register, forgot/reset password, confirm password, verify email) are Volt components.
- **Classic Livewire class components** live in `app/Livewire/**` with paired views in `resources/views/livewire/**` (e.g. `app/Livewire/Forms/LoginForm.php` is a Livewire `Form` object used by the Volt login page; `app/Livewire/Actions/Logout.php` is an invokable logout action).
- `Volt::mount()` is configured in [app/Providers/VoltServiceProvider.php](app/Providers/VoltServiceProvider.php) to scan both `resources/views/livewire` and `resources/views/pages` — new Volt pages can go in either location.

### Routing
- `routes/web.php` defines plain routes (`/`, `dashboard`, `profile`) and pulls in `routes/auth.php`.
- `routes/auth.php` defines all authentication routes, mixing `Volt::route()` (for Volt pages) and standard `Route::get()` (for the `VerifyEmailController` signed-URL callback).
- Dashboard and profile routes render plain Blade views (`dashboard.blade.php`, `profile.blade.php`) that in turn embed Livewire components — they are not Volt components themselves.

### Layouts and components
- Two Blade layout components: [AppLayout](app/View/Components/AppLayout.php) (authenticated pages, with nav) and [GuestLayout](app/View/Components/GuestLayout.php) (auth pages).
- Reusable Blade components (buttons, inputs, dropdown, modal, nav links, etc.) live in `resources/views/components/` and are Breeze defaults — extend these rather than introducing a new component library.

### Frontend build
- Tailwind CSS v3 (`tailwind.config.js`) with `@tailwindcss/forms`, Figtree as the primary font.
- Vite (`vite.config.js`) builds `resources/css/app.css` and `resources/js/app.js` via `laravel-vite-plugin`.

### Database
- Local/dev: MySQL 8.4 (via `docker-compose.yml`), configured in `.env`.
- Only Breeze's default migrations exist so far (`users`, `cache`, `jobs` tables) — no domain schema yet.
- Session, cache, and queue drivers are all `database` in `.env` for local dev (vs. `array`/`sync` in tests).

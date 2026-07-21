# Wood Agency

Portfolio site for **Artem Tsurtsumia** - master woodworker and furniture maker,
based in Nice, France. 25+ years of bespoke furniture, joinery and fine antique
restoration.

Built with Laravel, Livewire (Volt), Tailwind CSS and Alpine.js.

## Features

- **Single elegant page** - hero slideshow, gallery, before/after sliders,
  studio, contact.
- **Three languages** - English, French and Russian, switchable in the header.
  A first-time visitor gets their browser's language automatically.
- **Folder-driven content** - photos are added by dropping files into folders;
  no code changes needed (see [DEPLOY.md](DEPLOY.md)).
- **Interactive before/after** - drag sliders showing each restoration.
- **Contact form** - emails enquiries directly, with spam honeypot and rate
  limiting. No database.

## Local development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build          # or: npm run dev
php artisan serve
```

The site needs **no database**. Sessions and cache are file-based.

Set `MAIL_MAILER=log` locally and contact-form submissions are written to
`storage/logs/laravel.log` instead of being sent.

## Content

| What | Where |
|---|---|
| Name, role, contact details | top of `resources/views/welcome.blade.php` |
| Gallery pieces | `public/images/gallery/<piece-name>/` |
| Before/after pairs | `public/images/before-after/<name>__before.jpg` + `__after.jpg` |
| Studio photo | `public/images/studio.jpg` |
| Translations | `lang/fr.json`, `lang/ru.json` |

## Deployment

See **[DEPLOY.md](DEPLOY.md)**.

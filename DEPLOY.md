# Deploying Wood Agency

A single-page Laravel site. **No database required** — sessions and cache are
file-based, and the contact form sends email directly.

## Requirements

- PHP 8.3+ with the usual Laravel extensions (`mbstring`, `openssl`, `curl`,
  `fileinfo`, `dom`, `xml`)
- Composer
- Node.js 18+ (only to build the CSS/JS once)
- A web server pointing at **`public/`** (Nginx, Apache, or a host like
  Hostinger / o2switch / Infomaniak)

## 1. Upload

Upload the whole project **except** `node_modules/`, `vendor/` and `.env`.
Point the domain's document root at the **`public/`** directory.

## 2. Install and build

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build        # creates public/build (required)
```

## 3. Configure

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env`:

- `APP_URL=https://your-domain.com`
- `APP_ENV=production` and `APP_DEBUG=false` (already set)
- `MAIL_PASSWORD=` your Gmail **App Password** (see below)

### Gmail App Password

1. Turn on 2-Step Verification: <https://myaccount.google.com/security>
2. Create an App Password: <https://myaccount.google.com/apppasswords>
   (choose "Mail" → "Other", name it "Wood Agency")
3. Paste the 16-character code into `MAIL_PASSWORD` in `.env` — no spaces.

The site sends *from* the Gmail account and sets the visitor's address as
**Reply-To**, so hitting reply answers the client directly.

## 4. Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

## 5. Cache for speed

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Re-run these after **any** change to `.env` or the code.

## 6. Check it works

- Open the site, switch between EN / FR / RU.
- Send yourself a test message through the contact form.

---

## Adding photos later

No code changes needed — just add files:

- **A finished piece:** create a folder in `public/images/gallery/` named after
  the piece (e.g. `oak-console`) and put the photo(s) inside. Several photos in
  one folder become an automatic mini-slideshow. The folder name becomes the
  title (a leading number like `05-` only controls the order).
- **A before/after:** put two files in `public/images/before-after/` named
  `oak-console__before.jpg` and `oak-console__after.jpg`.

To translate a new title, add it to `lang/fr.json` and `lang/ru.json`.

After adding photos on a server with caching enabled, run
`php artisan view:cache` again (or `php artisan optimize:clear`).

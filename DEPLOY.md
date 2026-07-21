# Deploying Wood Agency (Hetzner + Coolify)

Same server and workflow as the PCN site. A single-page Laravel app with
**no database** - it only needs the container and Gmail SMTP.

- Server: Hetzner VPS `168.119.254.233` (Coolify)
- Build: **Dockerfile** (Nixpacks mis-generates the nginx config - do not use it)
- DNS: Cloudflare

---

## 1. Push to GitHub

Coolify deploys from a Git repo, so the project needs one:

```bash
git remote add origin git@github.com:Lukatsurtsumia/<repo-name>.git
git push -u origin master
```

## 2. Create the app in Coolify

1. **+ New** -> **Application** -> **Public/Private Repository**, pick the repo.
2. **Build Pack: `Dockerfile`** (not Nixpacks).
3. Port: **80**.

## 3. Environment variables

Paste these into the app's **Environment Variables** (Developer view), then
click **Save All Environment Variables** and redeploy.

> `.env` is **not** deployed - if these are missing the app 500s with
> "No application encryption key".

```
APP_NAME=Wood Agency
APP_ENV=production
APP_DEBUG=false
APP_KEY=            # generate locally: php artisan key:generate --show
APP_URL=https://your-subdomain.example.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

SESSION_DRIVER=cookie
SESSION_SECURE_COOKIE=true
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=artemwurwumia1976@gmail.com
MAIL_PASSWORD=      # Gmail App Password, 16 chars, no spaces
MAIL_FROM_ADDRESS=artemwurwumia1976@gmail.com
MAIL_FROM_NAME=Wood Agency

CONTACT_TO=artemwurwumia1976@gmail.com
```

### Gmail App Password

1. Turn on 2-Step Verification: <https://myaccount.google.com/security>
2. Create an App Password: <https://myaccount.google.com/apppasswords>
3. Paste the 16 characters into `MAIL_PASSWORD` with no spaces.

Mail is sent **from** the Gmail account with the visitor's address as
**Reply-To**, so hitting reply answers the client directly.

## 4. Domain + DNS

In Coolify set the app's **Domain** to the real hostname (do **not** rely on the
sslip.io auto-URL - it is flaky and HTTP-only).

In Cloudflare add an **A record** -> `168.119.254.233`, set to
**DNS only (grey cloud)** so Coolify can issue its own Let's Encrypt
certificate. (It can be switched to orange-cloud proxy afterwards if wanted.)

## 5. Deploy and check

- Open the site, switch **EN / FR / RU**.
- Send a test message through the contact form and confirm it arrives.

---

## Notes and gotchas

- **Coolify skips the build** if an image already exists for the same commit
  SHA ("Build step skipped"). Push a new commit, or change a build setting, to
  force a rebuild.
- `bootstrap/app.php` sets `trustProxies(at: '*')`. Without it, assets are
  served over `http://` behind Coolify's proxy and get blocked as mixed
  content, leaving the page unstyled.
- The image runs **Alpine (musl)**, where `GLOB_BRACE` does not exist. The
  photo loaders deliberately avoid it - keep it that way.
- Sessions are **cookie**-based, so the visitor's language survives redeploys
  and no persistent volume is needed.

## Adding photos later

No code changes - add files and redeploy:

- **A finished piece:** a folder in `public/images/gallery/` named after the
  piece (e.g. `oak-console`) with the photo(s) inside. Several photos in one
  folder become an automatic mini-slideshow; the folder name becomes the title
  (a leading `05-` only controls order).
- **A before/after:** two files in `public/images/before-after/` named
  `oak-console__before.jpg` and `oak-console__after.jpg`.

Translate a new title by adding it to `lang/fr.json` and `lang/ru.json`.

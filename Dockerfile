# ─────────────────────────────────────────────────────────────────────────────
# Wood Agency - production image (Coolify / Docker)
# Build pack in Coolify must be "Dockerfile" (Nixpacks mis-generates nginx).
# ─────────────────────────────────────────────────────────────────────────────

# ---- 1. Build the CSS/JS ----------------------------------------------------
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# ---- 1b. Optimise the photos (build-time only) ------------------------------
# The homepage ships ~8 MB of full-size JPEGs; the biggest alone takes ~6 s.
# This shrinks the copy that goes INTO THE IMAGE. The originals in the repo and
# on your machine are never modified - re-run a deploy to regenerate.
FROM alpine:3.20 AS images
RUN apk add --no-cache imagemagick
WORKDIR /src
COPY public/images ./images
# 1000px long edge is still ~2x the largest on-screen size (gallery cards render
# ~260px, before/after ~280px), so this is retina-sharp. Filenames are unchanged,
# so nothing in the app can break. '>' means shrink-only, never upscale.
RUN before=$(du -sk images | cut -f1); \
    find ./images -type f \( -iname '*.jpg' -o -iname '*.jpeg' \) -print0 \
      | xargs -0 -r -P 4 -n 8 magick mogrify -strip -interlace Plane -resize '1000x1000>' -quality 80; \
    after=$(du -sk images | cut -f1); \
    echo "images optimised: ${before}KB -> ${after}KB"

# ---- 2. Install PHP dependencies -------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-interaction

# ---- 3. Runtime -------------------------------------------------------------
FROM webdevops/php-nginx:8.4-alpine

ENV WEB_DOCUMENT_ROOT=/app/public \
    APP_ENV=production \
    APP_DEBUG=false

WORKDIR /app

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
# Must come after "COPY . ." so the optimised photos replace the full-size ones.
COPY --from=images /src/images ./public/images

# Cache headers for static media (see the file for details).
COPY docker/nginx/20-static-cache.conf /opt/docker/etc/nginx/vhost.common.d/

# Storage dirs must exist and be writable by the web user.
RUN mkdir -p storage/framework/cache storage/framework/sessions \
             storage/framework/views storage/logs bootstrap/cache \
 && chown -R application:application storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

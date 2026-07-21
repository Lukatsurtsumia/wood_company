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

# Storage dirs must exist and be writable by the web user.
RUN mkdir -p storage/framework/cache storage/framework/sessions \
             storage/framework/views storage/logs bootstrap/cache \
 && chown -R application:application storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

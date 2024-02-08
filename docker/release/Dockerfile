FROM dunglas/frankenphp as setup
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV="${APP_ENV:-production}"
ENV APP_DEBUG="${APP_DEBUG:-false}"
RUN install-php-extensions \
    @composer \
    pdo_mysql \
    intl \
    mailparse \
    zip

WORKDIR /app
COPY . /app/
RUN set -eux; \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
	ls -l storage && \
	ls -l storage/framework && \
    composer install --optimize-autoloader --no-dev

FROM node:${NODE_VERSION:-20}-buster-slim as node
ENV APP_ENV="${APP_ENV:-production}"
ENV APP_DEBUG="${APP_DEBUG:-false}"

WORKDIR /app
COPY --from=setup /app /app
RUN npm install && \
    npx vite build

FROM setup as site
ENV SERVER_NAME=:80
ENV APP_ENV="${APP_ENV:-production}"
ENV APP_DEBUG="${APP_DEBUG:-false}"
WORKDIR /app
COPY --from=node /app /app

RUN cp .env.example .env &&  \
    composer dump && \
    php /app/artisan storage:link && \
    php /app/artisan key:generate && \
    php /app/artisan config:cache && \
    php /app/artisan event:cache && \
    php /app/artisan route:cache && \
    php /app/artisan view:cache

EXPOSE 80

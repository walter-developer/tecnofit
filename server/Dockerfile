FROM hyperf/hyperf:8.3-alpine-v3.19-swoole

ARG APP_ENV=prod
ARG TIMEZONE=America/Sao_Paulo
ARG SCAN_CACHEABLE=true

ENV TIMEZONE=${TIMEZONE} \
    APP_ENV=${APP_ENV} \
    SCAN_CACHEABLE=${SCAN_CACHEABLE}

ENV COMPOSER_CACHE_DIR=/root/.composer/cache

RUN apk add --no-cache mysql-client

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./php/php.ini /etc/php8/conf.d/custom.ini

WORKDIR /app

EXPOSE 9501

ENTRYPOINT ["php", "bin/hyperf.php"]

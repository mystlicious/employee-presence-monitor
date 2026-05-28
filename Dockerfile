FROM php:8.2-cli-alpine

RUN apk add --no-cache icu-dev libzip-dev \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install -j$(nproc) intl pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN mkdir -p public/uploads/employees storage/app/absence_proofs \
	&& chmod -R 775 public/uploads storage/app

ENV PORT=8080
EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public public/index.php"]

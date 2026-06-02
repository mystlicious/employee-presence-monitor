FROM php:8.4-cli-alpine

RUN apk add --no-cache \
	freetype-dev \
	libjpeg-turbo-dev \
	libpng-dev \
	icu-dev \
	libzip-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install -j"$(nproc)" gd intl pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN mkdir -p public/uploads/employees storage/app/absence_proofs \
	&& chmod -R 775 public/uploads storage/app

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENV PORT=8080
EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]

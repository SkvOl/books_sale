FROM php:8.4-fpm

ENV TZ="Europe/Moscow"
RUN date

WORKDIR /var/www/books_sale

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    procps \
    systemd

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#
# RUN cd /var/www/ && composer create-project laravel/laravel:^11.0 books_sale
# RUN cd /var/www/books_sale &&  \
#     composer require laravel/breeze --dev && \
#     php artisan breeze:install blade && \
#     php artisan install:api && \
#     composer require darkaonline/l5-swagger --dev && \
#     php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider" && \
#     php artisan make:event SaleEvent && \
#     php artisan make:event BookUpdateEvent && \
#     php artisan view:clear && \
#     composer require yajra/laravel-datatables-oracle:"^11"
#

# RUN apt-get install -y nodejs npm
# RUN npm install vite@latest --save-dev && npm run build \
# && npm run build && npm i pusher-js && npm i laravel-echo


COPY . /var/www/books_sale

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN chmod -R 777 storage/logs

EXPOSE 9000 9001
CMD ["php-fpm"]
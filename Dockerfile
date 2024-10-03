# Установка базового образа
FROM --platform=linux/amd64 php:7.4-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    libmcrypt-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    gnupg2 \
    libonig-dev \
    libxml2-dev \
    libldb-dev \
    libssl-dev \
    unixodbc-dev \
    gcc \
    g++ \
    make

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools unixodbc-dev

# RUN wget http://pecl.php.net/get/sqlsrv-5.3.0.tgz \ pear install sqlsrv-5.3.0.tgz
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

RUN usermod -u 1000 www-data
# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создание рабочей директории
WORKDIR /var/www/html

# Копирование файла composer.json и composer.lock в рабочую директорию
COPY composer.json composer.lock ./

# Установка зависимостей через Composer
RUN composer install --no-scripts --no-autoloader

# Копирование остальных файлов проекта
COPY . .

# Копирование файла настроек PHP-FPM
COPY nginx/conf.d/app.conf /usr/local/etc/php-fpm.d/zzz-www.conf

# Настройка прав на файлы
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Настройка переменных окружения для подключения к MSSQL
#ENV DB_CONNECTION=sqlsrv \
#    DB_HOST=<your-db-host> \
#    DB_PORT=<your-db-port> \
#    DB_DATABASE=<your-db-database> \
#    DB_USERNAME=<your-db-username> \
#    DB_PASSWORD=<your-db-password>

# Генерация оптимизированных файлов и настройка прав на файлы
RUN composer dump-autoload --optimize
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN chmod -R 775 storage/ bootstrap/cache

# Запуск PHP-FPM
CMD ["php-fpm"]

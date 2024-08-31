FROM ubuntu:20.04

RUN apt-get update && \
    apt-get install -y software-properties-common && \
    add-apt-repository -y ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y nginx php8.3-fpm php8.3-curl php8.3-mysql php8.3-dom \
                       php8.3-imagick php8.3-mbstring php8.3-zip php8.3-gd php8.3-intl \
                       bash ca-certificates git unzip curl

WORKDIR /var/www/html

COPY ./app .

COPY /conf/www.conf /etc/php/8.3/fpm/pool.d/www.conf
COPY /conf/nginx.conf /etc/nginx/nginx.conf
COPY /conf/default.conf /etc/nginx/conf.d/default.conf

RUN chmod 777 /etc/php/8.3/fpm/pool.d/www.conf
RUN chmod 777 /etc/php/8.3/fpm/php.ini

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    chmod a+x /usr/local/bin/composer

RUN composer install --no-interaction --prefer-dist

RUN npm install

RUN sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.3/fpm/php.ini && \
    mkdir -p /run/php && \
    sed -i 's/listen = \/run\/php\/php8.3-fpm.sock/listen = 0.0.0.0:9000/' /etc/php/8.3/fpm/pool.d/www.conf

RUN chmod -R 755 /var/www/html
RUN chown -R www-data:www-data /var/www/html

CMD service php8.3-fpm start && npm run watch && nginx -g 'daemon off;'
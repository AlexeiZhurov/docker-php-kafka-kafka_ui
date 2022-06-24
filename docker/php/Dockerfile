FROM php:8.0-apache
WORKDIR /var/www/html
COPY ./ /var/www/html
# ENV CONTEXT_DOCUMENT_ROOT /var/www/html/public

# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
EXPOSE 80

RUN apt-get update --fix-missing \
    && apt-get install -y curl wget zip unzip git \
    && rm -rf /var/lib/apt/lists/*

# DEPENDENCIES
RUN apt-get update --fix-missing \
    && apt-get install -y  python \
    && rm -rf /var/lib/apt/lists/*

# KAFKA
RUN git clone --depth 1 https://github.com/edenhill/librdkafka.git \
    && ( \
        cd librdkafka \
        && ./configure \
        && make \
        && make install \
    ) \
    && pecl install rdkafka \
    && echo "extension=rdkafka.so" > /usr/local/etc/php/conf.d/rdkafka.ini
ARG DRUPAL_TAG

FROM drupal:${DRUPAL_TAG}

ARG MEMORY_LIMIT

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN echo memory_limit=${MEMORY_LIMIT} > /usr/local/etc/php/conf.d/memory_limit.ini

RUN echo [mail function] > /usr/local/etc/php/conf.d/sendmail.ini \
    && echo "sendmail_path='/usr/local/bin/mhsendmail --smtp-addr="mailhog:1025"'" >> /usr/local/etc/php/conf.d/sendmail.ini

RUN apt-get update && apt-get install wget git -y && wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail && apt-get --purge remove wget -y
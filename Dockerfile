ARG DRUPAL_TAG
ARG MEMORY_LIMIT

FROM drupal:${DRUPAL_TAG}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN echo memory_limit=$MEMORY_LIMIT > /usr/local/etc/php/conf.d/memory_limit.ini
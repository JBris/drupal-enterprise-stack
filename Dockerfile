ARG DRUPAL_TAG

FROM drupal:${DRUPAL_TAG}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis 
#&& echo extension=redis.so > /etc/php7.X-sp/conf.d/redis.ini
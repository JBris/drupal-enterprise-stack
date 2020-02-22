ARG DRUPAL_TAG

FROM drupal:${DRUPAL_TAG}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
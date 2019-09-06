ARG PHP_VERSION=php72-fpm
FROM srcoder/development-php:${PHP_VERSION}

ARG MAGENTO_VERSION=2.3.2
ARG MAGENTO_TYPE=project-community-edition

RUN apt-get update --fix-missing && \
    apt-get install -y procps mariadb-client-10.3 && \
    rm -rf /var/lib/apt/lists/* && \
    echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/memory-limit-php.ini && \
    curl -o magento.zip https://repo-magento-mirror.fooman.co.nz/dist/magento/$MAGENTO_TYPE/magento-$MAGENTO_TYPE-$MAGENTO_VERSION.zip && \
    unzip magento.zip -d . && \
    rm magento.zip && \
    composer config --unset repositories.0 && \
    composer config repositories.fooman composer https://repo-magento-mirror.fooman.co.nz/ && \
    composer install && \
    composer require reach-digital/magento2-test-framework && \
    composer config repositories.dev-extensions path extensions/* && \
    mkdir -p extensions

COPY templates/install-config-mysql.php dev/tests/integration/etc/
COPY templates/phpunit.xml dev/tests/quick-integration/

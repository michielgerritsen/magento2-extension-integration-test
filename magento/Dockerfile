ARG PHP_VERSION=php72-fpm
FROM srcoder/development-php:${PHP_VERSION}

ARG MAGENTO_VERSION=2.3.2
ARG MAGENTO_TYPE=project-community-edition
ARG DEPLOY_MODE=developer

ENV CI=true
ENV MAGE_MODE=${DEPLOY_MODE}

RUN apt-get update --fix-missing && \
    apt-get install -y procps mariadb-server mariadb-client && \
    rm -rf /var/lib/apt/lists/* && \
    /bin/bash -c "/usr/bin/mysqld_safe &" &&\
    sleep 5 && \
    echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/memory-limit-php.ini && \
    curl -o magento.zip https://repo-magento-mirror.fooman.co.nz/dist/magento/$MAGENTO_TYPE/magento-$MAGENTO_TYPE-$MAGENTO_VERSION.zip && \
    unzip -q magento.zip -d . && \
    rm magento.zip && \
    composer config --unset repositories.0 && \
    composer config repositories.fooman composer https://repo-magento-mirror.fooman.co.nz/ && \
    composer install && \
    if [ -f "/data/vendor/magento/module-inventory-catalog/etc/communication.xml" ]; then sed -i 's/is_synchronous="false"//g' /data/vendor/magento/module-inventory-catalog/etc/communication.xml; fi && \
    if [ -f "/data/vendor/magento/module-inventory-sales/etc/communication.xml" ]; then sed -i 's/is_synchronous="false"//g' /data/vendor/magento/module-inventory-sales/etc/communication.xml; fi && \
    mysql -u root -e 'CREATE DATABASE `magento`;' && \
    mysql -u root -e 'CREATE DATABASE `magento-test`;' && \
    mysql -u root -e "CREATE USER 'magento'@'localhost' IDENTIFIED BY 'password';" && \
    mysql -u root -e "CREATE USER 'magento-test'@'localhost' IDENTIFIED BY 'password';" && \
    mysql -u root -e "GRANT ALL PRIVILEGES ON * . * TO 'magento'@'localhost';" && \
    mysql -u root -e "GRANT ALL PRIVILEGES ON * . * TO 'magento-test'@'localhost';" && \
    php bin/magento setup:install --backend-frontname=admin --session-save=db --db-host=127.0.0.1 --db-name=magento --db-user=magento --db-password=password --base-url=http://localhost --timezone=Europe/Amsterdam --currency=EUR --admin-user=exampleuser --admin-password=examplepassword123 --admin-email=user@example.com --admin-firstname=Example --admin-lastname=Example --use-rewrites=1 && \
    php bin/magento deploy:mode:set developer && \
    php bin/magento setup:di:compile && \
    mkdir -p extensions && \
    if [ $(php -r 'echo version_compare(PHP_VERSION, "7.2", "ge") ? "true" : "false";') -eq "true" ]; then composer require reach-digital/magento2-test-framework; fi && \
    composer config repositories.dev-extensions path extensions/*

COPY templates/install-config-mysql.php dev/tests/integration/etc/
COPY templates/phpunit-unit.xml dev/tests/unit/phpunit.xml
COPY templates/phpunit-integration.xml dev/tests/integration/phpunit.xml
COPY templates/phpunit-quick-integration.xml dev/tests/quick-integration/phpunit.xml

ENTRYPOINT ["mysqld_safe"]
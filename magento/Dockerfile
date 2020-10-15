ARG PHP_VERSION=php72-fpm
FROM srcoder/development-php:${PHP_VERSION}

ARG MAGENTO_VERSION=2.3.2
ARG MAGENTO_TYPE=project-community-edition
ARG DEPLOY_MODE=developer

ENV CI=true
ENV MAGE_MODE=${DEPLOY_MODE}

RUN apt update --fix-missing && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt install -y gnupg procps apt-transport-https lsb-release && \
    curl -L https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add - && \
    echo "deb https://artifacts.elastic.co/packages/7.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-7.x.list && \
    wget https://dev.mysql.com/get/mysql-apt-config_0.8.15-1_all.deb && \
    dpkg -i mysql-apt-config_0.8.15-1_all.deb && \
    echo mysql-apt-config mysql-apt-config/select-server select mysql-5.7 | debconf-set-selections && \
    dpkg-reconfigure mysql-apt-config && \
    apt update && \
    apt install -y elasticsearch mysql-server mysql-client && \
    rm -rf /var/lib/apt/lists/* && \
    echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/memory-limit-php.ini && \
    echo 'max_execution_time = 300' >> /usr/local/etc/php/conf.d/memory-limit-php.ini && \
    rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    curl -o magento.zip https://repo-magento-mirror.fooman.co.nz/dist/magento/$MAGENTO_TYPE/magento-$MAGENTO_TYPE-$MAGENTO_VERSION.zip && \
    unzip -q magento.zip -d . && \
    rm magento.zip && \
    composer global require hirak/prestissimo && \
    composer config --unset repositories.0 && \
    composer config repositories.fooman composer https://repo-magento-mirror.fooman.co.nz/ && \
    composer install && \
    if [ -f "/data/vendor/magento/module-inventory-catalog/etc/communication.xml" ]; then sed -i 's/`is_synchronous="false"//g' /data/vendor/magento/module-inventory-catalog/etc/communication.xml; fi && \
    if [ -f "/data/vendor/magento/module-inventory-sales/etc/communication.xml" ]; then sed -i 's/is_synchronous="false"//g' /data/vendor/magento/module-inventory-sales/etc/communication.xml; fi && \
    if [ -f "/data/vendor/magento/module-inventory-indexer/etc/communication.xml" ]; then sed -i 's/is_synchronous="false"//g' /data/vendor/magento/module-inventory-indexer/etc/communication.xml; fi && \
    service elasticsearch start && \
    /bin/bash -c "/usr/bin/mysqld_safe &" && \
    sleep 5 && \
    mysql -u root -e 'CREATE DATABASE `magento`;' && \
    mysql -u root -e 'CREATE DATABASE `magento-test`;' && \
    mysql -u root -e "CREATE USER 'magento'@'%' IDENTIFIED BY 'password';" && \
    mysql -u root -e "CREATE USER 'magento-test'@'%' IDENTIFIED BY 'password';" && \
    mysql -u root -e "GRANT ALL PRIVILEGES ON * . * TO 'magento'@'%';" && \
    mysql -u root -e "GRANT ALL PRIVILEGES ON * . * TO 'magento-test'@'%';" && \
    sleep 5 && \
    php bin/magento setup:install --backend-frontname=admin --session-save=db --db-host=127.0.0.1 --db-name=magento --db-user=magento --db-password=password --base-url=http://localhost --timezone=Europe/Amsterdam --currency=EUR --admin-user=exampleuser --admin-password=examplepassword123 --admin-email=user@example.com --admin-firstname=Example --admin-lastname=Example --use-rewrites=1 --use-sample-data && \
    php bin/magento deploy:mode:set developer && \
    php bin/magento sampledata:deploy && \
    php bin/magento setup:upgrade && \
    php bin/magento setup:di:compile && \
    magerun2 config:store:set catalog/frontend/flat_catalog_category 1 && \
    magerun2 config:store:set catalog/frontend/flat_catalog_product 1 && \
    php bin/magento indexer:reindex && \
    mkdir -p extensions && \
#    if (( $(php -r 'echo version_compare(PHP_VERSION, "7.2", "ge") && version_compare(PHP_VERSION, "7.4", "lt") ? "true" : "false";') = "true" )); then composer require reach-digital/magento2-test-framework; fi && \
    composer config repositories.dev-extensions path extensions/*

COPY patches/bundle-2684_dotmailer_integration_tests-2020-08-04-04-31-22.patch bundle-2684_dotmailer_integration_tests-2020-08-04-04-31-22.patch
COPY patches/cors.patch cors.patch
COPY templates/install-config-mysql.php dev/tests/integration/etc/
COPY templates/phpunit-unit.xml dev/tests/unit/phpunit.xml
COPY templates/phpunit-integration.xml dev/tests/integration/phpunit.xml
COPY templates/phpunit-quick-integration.xml dev/tests/quick-integration/phpunit.xml
COPY entrypoint.sh entrypoint.sh

RUN if [ "${MAGENTO_VERSION}" = "2.4.0" -o "${MAGENTO_VERSION}" = "2.4.1" ]; then git apply bundle-2684_dotmailer_integration_tests-2020-08-04-04-31-22.patch; fi && \
    rm bundle-2684_dotmailer_integration_tests-2020-08-04-04-31-22.patch && \
    git apply cors.patch && \
    rm cors.patch

ENTRYPOINT ["/bin/bash", "entrypoint.sh"]

EXPOSE 80 3306

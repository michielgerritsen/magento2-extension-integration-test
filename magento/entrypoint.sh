#!/bin/bash

# Comes from the parent image
./start-services

nohup /usr/local/bin/php -S 0.0.0.0:80 -t /data/pub/ /data/phpserver/router.php &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start the php server: $status"
  exit $status
fi

if [ -n "$URL" ] && [ "$URL" != "http://localhost/" ]; then
  echo "Updating Base URL"
  magerun2 config:store:set web/unsecure/base_url $URL
  magerun2 config:store:set web/secure/base_url $URL
  magerun2 config:store:set web/unsecure/base_link_url $URL
  magerun2 config:store:set web/secure/base_link_url $URL
  magerun2 cache:flush
fi

# Allow to set the commands in an environment variable
if [[ ! -z "${CUSTOM_ENTRYPOINT_COMMAND}" ]]; then
  echo "${CUSTOM_ENTRYPOINT_COMMAND}" > custom-entrypoint.sh
fi

if [ -f custom-entrypoint.sh ]; then
  bash ./custom-entrypoint.sh
fi

# Wait until Elasticsearch is up and running
counter=0
until curl -s -o /dev/null http://localhost:9200; do
  counter=$((counter + 1))
  echo "Elasticsearch not (yet) available, waiting... (attempt $counter)"
  sleep 1
done

if [ -n "$FLAT_TABLES" ]; then
  echo "Enabling Flat Tables"
  magerun2 config:store:set catalog/frontend/flat_catalog_category 1
  magerun2 config:store:set catalog/frontend/flat_catalog_product 1
  magerun2 cache:flush
  magerun2 indexer:reindex
fi

#if grep -q Magento_TwoFactorAuth "$File"; then
#  echo "Disabling Magento_TwoFactorAuth"
#  php bin/magento module:disable Magento_TwoFactorAuth
#fi

while sleep 5; do
  ps aux |grep elasticsearch |grep -q -v grep
  ELASTICSEARCH_STATUS=$?
  ps aux |grep mysqld_safe |grep -q -v grep
  MYSQL_STATUS=$?
  ps aux |grep php |grep -q -v grep
  PHP_STATUS=$?

  if [ $ELASTICSEARCH_STATUS -ne 0 -o $MYSQL_STATUS -ne 0 -o $PHP_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi
done

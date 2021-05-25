#!/bin/bash

nohup su elasticsearch -s /usr/share/elasticsearch/bin/elasticsearch &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start elasticsearch: $status"
  exit $status
fi

# Start the second process
nohup mysqld_safe &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start mysqld_safe: $status"
  exit $status
fi

nohup /usr/local/sbin/php-fpm -R &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start php fpm: $status"
  exit $status
fi

nohup /usr/sbin/nginx &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start nginx: $status"
  exit $status
fi

if [ -n "$URL" ]; then
  magerun2 config:store:set web/unsecure/base_url $URL
  magerun2 config:store:set web/secure/base_url $URL
  magerun2 config:store:set web/unsecure/base_link_url $URL
  magerun2 config:store:set web/secure/base_link_url $URL
  magerun2 cache:flush
fi

if [ -n "$FLAT_TABLES" ]; then
  magerun2 config:store:set catalog/frontend/flat_catalog_category 1
  magerun2 config:store:set catalog/frontend/flat_catalog_product 1
  magerun2 cache:flush
  magerun2 indexer:reindex
fi

while sleep 5; do
  ps aux |grep elasticsearch |grep -q -v grep
  ELASTICSEARCH_STATUS=$?
  ps aux |grep mysqld_safe |grep -q -v grep
  MYSQL_STATUS=$?
  ps aux |grep php-fpm |grep -q -v grep
  PHP_STATUS=$?
  ps aux |grep nginx |grep -q -v grep
  NGINX_STATUS=$?

  if [ $ELASTICSEARCH_STATUS -ne 0 -o $MYSQL_STATUS -ne 0 -o $PHP_STATUS -ne 0 -o $NGINX_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi
done

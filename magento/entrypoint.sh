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

nohup /usr/local/bin/php -S 0.0.0.0:80 -t /data/pub/ /data/phpserver/router.php &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start the php server: $status"
  exit $status
fi

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

<?php
$version = getenv('MAGENTO_VERSION');
$is248 = substr($version, 0, 5) == '2.4.8';
$is249 = substr($version, 0, 5) == '2.4.9';

if (!$is248 && !$is249) {
    echo 'This script is only for Magento 2.4.8 and 2.4.9' . PHP_EOL;
    exit(0);
}

copy('dev/tests/integration/phpunit-248.xml', 'dev/tests/integration/phpunit.xml');

echo 'Copied dev/tests/integration/phpunit-248.xml to dev/tests/integration/phpunit.xml' . PHP_EOL;
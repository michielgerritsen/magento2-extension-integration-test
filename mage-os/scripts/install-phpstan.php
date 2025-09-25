#!/usr/bin/env php
<?php

//$version = getenv('MAGENTO_VERSION');

$output = null;
$code = null;
exec('composer require --dev phpstan/phpstan:~1.0 bitexpert/phpstan-magento phpstan/extension-installer', $output, $code);

if ($code !== 0) {
    echo 'Unable to install PHPStan' . PHP_EOL;
    die($code);
}

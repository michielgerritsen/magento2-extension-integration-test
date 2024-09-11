#!/usr/bin/env php
<?php

// Magento version < 2.3.4
// PHP version > 7.1

$version = getenv('MAGENTO_VERSION');
$is230 = substr($version, 0, 5) == '2.3.0';
$is231 = substr($version, 0, 5) == '2.3.1';
$is232 = substr($version, 0, 5) == '2.3.2';
$is233 = substr($version, 0, 5) == '2.3.3';

if ($is230 || $is231 || $is232 || $is233) {
    echo 'Magento version (' . $version . ') does not have updated PHPStan support. Skipping.' . PHP_EOL;
    die(0);
}

if (version_compare(PHP_VERSION, '7.2', '<')) {
    echo 'PHP version (' . PHP_VERSION . ') does not have updated PHPStan support. Skipping.' . PHP_EOL;
    die(0);
}

$output = null;
$code = null;
exec('composer require --dev phpstan/phpstan:~1.0 bitexpert/phpstan-magento phpstan/extension-installer', $output, $code);

if ($code !== 0) {
    echo 'Unable to install PHPStan' . PHP_EOL;
    die($code);
}

<?php

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

/**
 * Monolog has been updated but Magento does not match the signature. That's why we want to downgrade it sometimes.
 * See: https://github.com/magento/magento2/pull/35596
 */

require '/data/vendor/autoload.php';

$version = getenv('MAGENTO_VERSION');
$is240 = substr($version, 0, 5) == '2.4.0';
$is241 = substr($version, 0, 5) == '2.4.1';
$is242 = substr($version, 0, 5) == '2.4.2';
$is243 = substr($version, 0, 5) == '2.4.3';
$is244 = substr($version, 0, 5) == '2.4.4';
$is245 = substr($version, 0, 5) == '2.4.5';
$is246 = substr($version, 0, 5) == '2.4.6';
$is247 = substr($version, 0, 5) == '2.4.7';
$isP0 = strlen($version) == 5;
$isP1 = substr($version, 6, 8) == 'p1';
$isP2 = substr($version, 6, 8) == 'p2';
$isP3 = substr($version, 6, 8) == 'p3';
$isP4 = substr($version, 6, 8) == 'p4';
$isP5 = substr($version, 6, 8) == 'p5';
$isP6 = substr($version, 6, 8) == 'p6';
$isP7 = substr($version, 6, 8) == 'p7';
$isP8 = substr($version, 6, 8) == 'p8';
$isP9 = substr($version, 6, 8) == 'p9';

if (!$is240 && !$is241 && !$is242 && !$is243 && !$is244) {
    echo 'No monolog downgrade needed, skipping' . PHP_EOL;
    return;
}

if (($is244 && !$isP0)) {
    echo 'No monolog downgrade needed, skipping' . PHP_EOL;
    return;
}

if (!class_exists(InstalledVersions::class)) {
    return;
}

$installed = InstalledVersions::satisfies(new VersionParser(), 'monolog/monolog', '^2.7');

if (!$installed) {
    echo 'monolog/monolog seems not to be installed in version ^2.7.0 so I\'m not downgrading' . PHP_EOL;
    return;
}

$output = null;
$code = null;
exec('composer require monolog/monolog:2.6.0', $output, $code);

if ($code !== 0) {
    echo 'Unable to downgrade monolog' . PHP_EOL;
    die($code);
}

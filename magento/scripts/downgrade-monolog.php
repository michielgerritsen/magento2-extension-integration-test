<?php

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

/**
 * Monolog has been updated but Magento does not match the signature. That's why we want to downgrade it sometimes.
 * See: https://github.com/magento/magento2/pull/35596
 */

require '/data/vendor/autoload.php';

if (!class_exists(InstalledVersions::class)) {
    return;
}

$installed = InstalledVersions::satisfies(new VersionParser(), 'monolog/monolog', '^2.7');

if (!$installed) {
    echo 'monolog/monolog seems not to be installed in version ^2.7.0 so I\'m not downgrading';
    return;
}

$output = null;
$code = null;
exec('composer require monolog/monolog:2.6.0', $output, $code);

if ($code !== 0) {
    echo 'Unable to downgrade monolog' . PHP_EOL;
    die($code);
}

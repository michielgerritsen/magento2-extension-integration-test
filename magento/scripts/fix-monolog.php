<?php

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

/**
 * 2.4.8 got an upgrade to monolog 3.x, but sometimes it installs 2.10, and 2.4.7 sometimes installs 3.9. It's a mess.
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
$is248 = substr($version, 0, 5) == '2.4.8';

//$isP0 = strlen($version) == 5;
//$isP1 = substr($version, 6, 8) == 'p1';
//$isP2 = substr($version, 6, 8) == 'p2';
//$isP3 = substr($version, 6, 8) == 'p3';
//$isP4 = substr($version, 6, 8) == 'p4';
//$isP5 = substr($version, 6, 8) == 'p5';
//$isP6 = substr($version, 6, 8) == 'p6';
//$isP7 = substr($version, 6, 8) == 'p7';
//$isP8 = substr($version, 6, 8) == 'p8';
//$isP9 = substr($version, 6, 8) == 'p9';

if ($is240 || $is241 || $is242 || $is243 || $is244 || $is245 || $is246) {
    echo 'This fix is not applicable to this version' . PHP_EOL;
    return;
}

if (!class_exists(InstalledVersions::class)) {
    return;
}

$isTwoDotTenInstalled = InstalledVersions::satisfies(new VersionParser(), 'monolog/monolog', '^2.10');
$isThreeDotNineInstalled = InstalledVersions::satisfies(new VersionParser(), 'monolog/monolog', '^3.9');

if ($is247 && !$isTwoDotTenInstalled) {
    runComposer('^2.10');
    return;
}

if ($is248 && !$isThreeDotNineInstalled) {
    runComposer('^3.9');
    return;
}

echo 'No monolog fix needed, skipping' . PHP_EOL;

function runComposer(string $version): void {
    $output = null;
    $code = null;
    exec('composer require monolog/monolog:' . $version, $output, $code);

    if ($code !== 0) {
        echo 'Unable to fix monolog' . PHP_EOL;
        die($code);
    }
}

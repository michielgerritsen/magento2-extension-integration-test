<?php

require '/data/vendor/autoload.php';

$version = getenv('MAGENTO_VERSION');
$is244 = substr($version, 0, 5) == '2.4.4';
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

if (!$is244) {
    echo 'PayPal/Braintree can stay' . PHP_EOL;
    return;
}

if ($isP0 || $isP1 || $isP2 || $isP3 || $isP4) {
    echo 'PayPal/Braintree can stay' . PHP_EOL;
    return;
}

$output = null;
$code = null;
exec('jq \'. + {replace: {"paypal/module-braintree": "*"}}\' composer.json > composer.tmp.json && mv composer.tmp.json composer.json', $output, $code);
exec('composer update paypal/module-braintree');

if ($code !== 0) {
    echo 'Unable to remove paypal/braintree' . PHP_EOL;
    die($code);
}

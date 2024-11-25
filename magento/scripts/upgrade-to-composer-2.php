<?php

$isPhp72OrLower = version_compare(PHP_VERSION, '7.2', '>=');

$version = getenv('MAGENTO_VERSION');
$is230 = substr($version, 0, 5) == '2.3.0';
$is231 = substr($version, 0, 5) == '2.3.1';
$is232 = substr($version, 0, 5) == '2.3.2';
$is233 = substr($version, 0, 5) == '2.3.3';
$is234 = substr($version, 0, 5) == '2.3.4';
$is235 = substr($version, 0, 5) == '2.3.5';
$is236 = substr($version, 0, 5) == '2.3.6';
$is237 = substr($version, 0, 5) == '2.3.7';
$is240 = substr($version, 0, 5) == '2.4.0';
$is241 = substr($version, 0, 5) == '2.4.1';
$is242 = substr($version, 0, 5) == '2.4.2';
$is243 = substr($version, 0, 5) == '2.4.3';
$is244 = substr($version, 0, 5) == '2.4.4';
$is245 = substr($version, 0, 5) == '2.4.5';
$is246 = substr($version, 0, 5) == '2.4.6';
$is247 = substr($version, 0, 5) == '2.4.7';

if ($is230 || $is231 || $is232 || $is233) {
    run('composer require magento/inventory-composer-installer:"1.2.0 as 1.1.0" --no-update');
    return;
}

$laminasDependencyPluginVersion =  "2.1.2 as 1.0.4";
if ($isPhp72OrLower) {
    $laminasDependencyPluginVersion = "2.0.0 as 1.0.4";
}

if ($is234 || $is235 || $is236 || $is240 || $is241) {
    run('composer require laminas/laminas-dependency-plugin:"' . $laminasDependencyPluginVersion . '" --no-update');
    run('composer require magento/inventory-composer-installer:"1.2.0 as 1.1.0" --no-update');
    run('composer require --dev dealerdirect/phpcodesniffer-composer-installer:^0.7.0 --no-update');
    return;
}

function run(string $command) {
    echo 'Running command ' . $command . PHP_EOL;

    $output = null;
    $code = null;
    exec($command, $output, $code);

    if ($code !== 0) {
        echo 'Error while running "' . $command . '"' . PHP_EOL;
        die($code);
    }
}
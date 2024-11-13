#!/usr/bin/env php
<?php

$version = getenv('MAGENTO_VERSION');
$is244 = substr($version, 0, 5) == '2.4.4';

if (!$is244) {
    echo 'AC2855 Breaking bug does not exist in this version' . PHP_EOL;
    exit(0);
}

$output = null;
$code = null;
exec('cd /data && patch -p1 < patches/AC2855.patch', $output, $code);

if ($code !== 0) {
    echo 'Error applying patch for AC2855' . PHP_EOL;
    echo implode(PHP_EOL, $output);
    exit($code);
}

echo 'Applied patch AC2855' . PHP_EOL;

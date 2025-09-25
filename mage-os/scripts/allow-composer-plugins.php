<?php

$composerPlugins = [
    'captainhook/plugin-composer',
    'dealerdirect/phpcodesniffer-composer-installer',
    'laminas/laminas-dependency-plugin',
    'phpstan/extension-installer',
    'magento/*',
];

foreach ($composerPlugins as $pluginName) {
    echo 'Allowing ' . $pluginName . PHP_EOL;
    exec('composer config --no-plugins allow-plugins.' . $pluginName . ' true');
}
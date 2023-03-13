<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

return [
    'db-host' => '127.0.0.1',
    'db-user' => 'magento-test',
    'db-password' => 'password',
    'db-name' => 'magento-test',
    'db-prefix' => '',
    'backend-frontname' => 'backend',
    'admin-user' => \Magento\TestFramework\Bootstrap::ADMIN_NAME,
    'admin-password' => \Magento\TestFramework\Bootstrap::ADMIN_PASSWORD,
    'admin-email' => \Magento\TestFramework\Bootstrap::ADMIN_EMAIL,
    'admin-firstname' => \Magento\TestFramework\Bootstrap::ADMIN_FIRSTNAME,
    'admin-lastname' => \Magento\TestFramework\Bootstrap::ADMIN_LASTNAME,
    'disable-modules' => join(
        ',',
        [
            'Magento_SampleData',
            'Magento_CatalogSampleData',
            'Magento_DownloadableSampleData',
            'Magento_GroupedProductSampleData',
            'Magento_BundleSampleData',
            'Magento_ThemeSampleData',
            'Magento_ConfigurableSampleData',
            'Magento_ReviewSampleData',
            'Magento_OfflineShippingSampleData',
            'Magento_CatalogRuleSampleData',
            'Magento_TaxSampleData',
            'Magento_SalesRuleSampleData',
            'Magento_SwatchesSampleData',
            'Magento_MsrpSampleData',
            'Magento_CustomerSampleData',
            'Magento_CmsSampleData',
            'Magento_SalesSampleData',
            'Magento_ProductLinksSampleData',
            'Magento_WidgetSampleData',
            'Magento_WishlistSampleData',
        ]
    )
];

<?php
/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

namespace MichielGerritsen\ExampleTest\Test\Integration;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * The only goal of this test is to check if test can be run successful.
     */
    public function testSuccess()
    {
        $this->assertTrue(true);
    }

    /**
     * @magentoConfigFixture current_store general/store_information/name Magento 2 test store
     */
    public function testCanUseConfigFixture(): void
    {
        $result = $this->objectManager->get(ScopeConfigInterface::class)->getValue(
            'general/store_information/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $this->assertEquals('Magento 2 test store', $result);
    }
}

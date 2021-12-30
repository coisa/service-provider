<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 * @copyright Copyright (c) 2020-2021 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\ServiceProvider\Test\Unit;

use CoiSA\ServiceProvider\AbstractServiceProvider;
use CoiSA\ServiceProvider\ServiceProviderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractServiceProviderTestCase.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
abstract class AbstractServiceProviderTestCase extends TestCase
{
    /** @var AbstractServiceProvider */
    private $serviceProvider;

    public function testServiceProviderImplementsServiceProviderInterface()
    {
        self::assertInstanceOf(ServiceProviderInterface::class, $this->getServiceProvider());
    }

    public function testServiceProviderExtendAbstractServiceProvider()
    {
        self::assertInstanceOf(AbstractServiceProvider::class, $this->getServiceProvider());
    }

    /**
     * @return AbstractServiceProvider
     */
    protected function getServiceProvider()
    {
        if (!$this->serviceProvider) {
            $this->serviceProvider = $this->createServiceProvider();
        }

        return $this->serviceProvider;
    }

    /**
     * @return AbstractServiceProvider
     */
    abstract protected function createServiceProvider();
}

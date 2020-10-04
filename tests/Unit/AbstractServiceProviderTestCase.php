<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\ServiceProvider\Test\Unit;

use CoiSA\ServiceProvider\AbstractServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractServiceProviderTestCase
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
abstract class AbstractServiceProviderTestCase extends TestCase
{
    /**
     * @var AbstractServiceProvider
     */
    protected $serviceProvider;

    public function testServiceProviderImplementsServiceProviderInterface()
    {
        self::assertInstanceOf('CoiSA\\ServiceProvider\\ServiceProviderInterface', $this->serviceProvider);
    }

    public function testServiceProviderExtendServiceProvider()
    {
        self::assertInstanceOf('CoiSA\\ServiceProvider\\ServiceProvider', $this->serviceProvider);
    }

    public function testServiceProviderExtendAbstractServiceProvider()
    {
        self::assertInstanceOf('CoiSA\\ServiceProvider\\AbstractServiceProvider', $this->serviceProvider);
    }
}
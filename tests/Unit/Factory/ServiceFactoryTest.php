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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Factory\ServiceFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
final class ServiceFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var \stdClass */
    private $service;

    public function setUp(): void
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->service   = new \stdClass();
    }

    public function testInvokeWillReturnService()
    {
        self::assertSame($this->service, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    public function testInvokeWithoutConstructorArgumentWillReturnNull()
    {
        $factory = new ServiceFactory();

        self::assertNull(\call_user_func($factory, $this->container->reveal()));
    }

    /**
     * @return ServiceFactory
     */
    protected function getFactory()
    {
        return new ServiceFactory($this->service);
    }
}

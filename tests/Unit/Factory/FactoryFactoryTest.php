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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\ServiceFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceFactoryTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
final class FactoryFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var string */
    private $service;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->service   = 'CoiSA\\ServiceProvider\\Factory\\ServiceFactory';
    }

    /**
     * @expectedException  \CoiSA\ServiceProvider\Exception\ReflectionException
     */
    public function testConstructWithStringNonExistentClassWillThrowReflectionException()
    {
        new FactoryFactory(\uniqid('factory', true));
    }

    public function testInvokeWithFactoryClassInsideContainerWillUseFactoryFromContainer()
    {
        $instance       = new \stdClass();
        $instance->test = \uniqid(__METHOD__, true);

        $this->container->has($this->service)->willReturn(true);
        $this->container->get($this->service)->willReturn(new ServiceFactory($instance));

        self::assertSame($instance, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    public function testInvokeWithoutFactoryClassInsideContainerWillInvokeIntoNewInstanceOfFactory()
    {
        $this->container->has($this->service)->willReturn(false);

        self::assertNull(\call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @throws \CoiSA\ServiceProvider\Exception\ReflectionException
     *
     * @return FactoryFactory
     */
    protected function getFactory()
    {
        return new FactoryFactory($this->service);
    }
}

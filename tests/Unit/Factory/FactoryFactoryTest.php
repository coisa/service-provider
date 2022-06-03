<?php

declare(strict_types=1);

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 * @copyright Copyright (c) 2020-2022 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Exception\ReflectionException;
use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\ServiceFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\Factory\FactoryFactory
 */
final class FactoryFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var string */
    private $service;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->service   = ServiceFactory::class;
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithStringNonExistentClassWillThrowReflectionException(): void
    {
        $this->expectException(ReflectionException::class);

        new FactoryFactory(uniqid('factory', true));
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWithFactoryClassInsideContainerWillUseFactoryFromContainer(): void
    {
        $instance       = new \stdClass();
        $instance->test = uniqid(__METHOD__, true);

        $this->container->has($this->service)->willReturn(true);
        $this->container->get($this->service)->willReturn(new ServiceFactory($instance));

        static::assertSame($instance, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWithoutFactoryClassInsideContainerWillInvokeIntoNewInstanceOfFactory(): void
    {
        $this->container->has($this->service)->willReturn(false);

        static::assertNull(\call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @throws \CoiSA\ServiceProvider\Exception\ReflectionException
     */
    protected function getFactory(): FactoryFactory
    {
        return new FactoryFactory($this->service);
    }
}

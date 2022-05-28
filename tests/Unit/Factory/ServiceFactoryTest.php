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

use CoiSA\ServiceProvider\Factory\ServiceFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 *
 * @internal
 * @coversNothing
 */
final class ServiceFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var \stdClass */
    private $service;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->service   = new \stdClass();
    }

    public function testInvokeWillReturnService(): void
    {
        static::assertSame($this->service, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    public function testInvokeWithoutConstructorArgumentWillReturnNull(): void
    {
        $factory = new ServiceFactory();

        static::assertNull(\call_user_func($factory, $this->container->reveal()));
    }

    /**
     * @return ServiceFactory
     */
    protected function getFactory()
    {
        return new ServiceFactory($this->service);
    }
}

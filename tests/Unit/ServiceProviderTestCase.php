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

namespace CoiSA\ServiceProvider\Test\Unit;

use CoiSA\ServiceProvider\Exception\UnexpectedValueException;
use CoiSA\ServiceProvider\Extension\CallableExtension;
use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;
use CoiSA\ServiceProvider\ServiceProvider;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceProviderTestCase.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
abstract class ServiceProviderTestCase extends AbstractServiceProviderTestCase
{
    use ProphecyTrait;

    /**
     * @coversNothing
     */
    public function testServiceProviderExtendServiceProvider(): void
    {
        static::assertInstanceOf(ServiceProvider::class, $this->getServiceProvider());
    }

    /**
     * @covers ::getFactory
     */
    public function testGetFactoryWithoutGivenFactoryWillThrowUnexpectedValueException(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->getServiceProvider()->getFactory(uniqid('test', true));
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtensionWithoutGivenExtensionWillThrowUnexpectedValueException(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->getServiceProvider()->getExtension(uniqid('test', true));
    }

    /**
     * @covers ::setFactory
     */
    public function testSetFactoryWithFactoryObjectWillSetSameFactoryInstance(): void
    {
        $id      = uniqid('id', true);
        $factory = $this->prophesize(ServiceProviderFactoryInterface::class)->reveal();

        $this->getServiceProvider()->setFactory($id, $factory);

        static::assertSame($factory, $this->getServiceProvider()->getFactory($id));
    }

    /**
     * @covers ::setFactory
     */
    public function testSetFactoryWithStringFactoryWillSetFactoryFactoryInstanceForGivenString(): void
    {
        $id      = uniqid('id', true);
        $factory = 'stdClass';

        $this->getServiceProvider()->setFactory($id, $factory);

        static::assertInstanceOf(FactoryFactory::class, $this->getServiceProvider()->getFactory($id));
    }

    /**
     * @covers ::setFactory
     */
    public function testSetFactoryWithCallableWillSetFactoryFactoryForGivenCallable(): void
    {
        $id             = uniqid('id', true);
        $object         = new \stdClass();
        $object->uniqid = uniqid('test', true);

        $factory = fn (ContainerInterface $container) => $object;

        $this->getServiceProvider()->setFactory($id, $factory);

        static::assertInstanceOf(FactoryFactory::class, $this->getServiceProvider()->getFactory($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        static::assertSame(
            $object,
            \call_user_func($this->getServiceProvider()->getFactory($id), $container)
        );
    }

    /**
     * @covers ::setAlias
     */
    public function testSetAliasWillSetAliasForContainerGet(): void
    {
        $id             = uniqid('id', true);
        $alias          = uniqid('alias', true);
        $object         = new \stdClass();
        $object->uniqid = uniqid('test', true);

        $this->getServiceProvider()->setAlias($alias, $id);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($id)->willReturn($object);

        static::assertSame(
            $object,
            \call_user_func($this->getServiceProvider()->getFactory($alias), $container->reveal())
        );
    }

    /**
     * @covers ::extend
     */
    public function testExtendWithExtensionObjectWillSetExtension(): void
    {
        $id        = uniqid('id', true);
        $extension = $this->prophesize(ServiceProviderExtensionInterface::class)->reveal();

        $this->getServiceProvider()->extend($id, $extension);

        static::assertSame($extension, $this->getServiceProvider()->getExtension($id));
    }

    /**
     * @covers ::extend
     */
    public function testExtendWithCallableWillSetCallableExtensionToGivenId(): void
    {
        $id               = uniqid('id', true);
        $previous         = new \stdClass();
        $previous->uniqid = uniqid('test', true);
        $extension        = fn (ContainerInterface $container, $previous = null) => $previous;

        $this->getServiceProvider()->extend($id, $extension);

        static::assertInstanceOf(CallableExtension::class, $this->getServiceProvider()->getExtension($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        static::assertSame(
            $previous,
            \call_user_func($this->getServiceProvider()->getExtension($id), $container, $previous)
        );
    }

    /**
     * @covers ::extend
     */
    public function testExtendWithAlreadySetIdWillExtendExtension(): void
    {
        $id         = uniqid('id', true);
        $return1    = uniqid('return1', true);
        $extension1 = fn (ContainerInterface $container, $previous = null) => $previous . $return1;
        $return2    = uniqid('return2', true);
        $extension2 = fn (ContainerInterface $container, $previous = null) => $previous . $return2;

        $this->getServiceProvider()->extend($id, $extension1, false);
        $this->getServiceProvider()->extend($id, $extension2, false);

        static::assertInstanceOf(ExtendExtension::class, $this->getServiceProvider()->getExtension($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        static::assertSame(
            $return1 . $return2,
            \call_user_func($this->getServiceProvider()->getExtension($id), $container)
        );
    }

    /**
     * @covers ::extend
     */
    public function testExtendWithPrependAndAlreadySetIdWillPrependExtendExtension(): void
    {
        $id         = uniqid('id', true);
        $return1    = uniqid('return1', true);
        $extension1 = fn (ContainerInterface $container, $previous = null) => $previous . $return1;
        $return2    = uniqid('return2', true);
        $extension2 = fn (ContainerInterface $container, $previous = null) => $previous . $return2;

        $this->getServiceProvider()->extend($id, $extension1, true);
        $this->getServiceProvider()->extend($id, $extension2, true);

        static::assertInstanceOf(ExtendExtension::class, $this->getServiceProvider()->getExtension($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        static::assertSame(
            $return2 . $return1,
            \call_user_func($this->getServiceProvider()->getExtension($id), $container)
        );
    }
}

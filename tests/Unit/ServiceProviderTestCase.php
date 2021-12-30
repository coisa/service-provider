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

use CoiSA\ServiceProvider\Exception\UnexpectedValueException;
use CoiSA\ServiceProvider\Extension\CallableExtension;
use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;
use CoiSA\ServiceProvider\ServiceProvider;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceProviderTestCase.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
abstract class ServiceProviderTestCase extends AbstractServiceProviderTestCase
{
    public function testServiceProviderExtendServiceProvider()
    {
        self::assertInstanceOf(ServiceProvider::class, $this->getServiceProvider());
    }

    public function testGetFactoryWithoutGivenFactoryWillThrowUnexpectedValueException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->getServiceProvider()->getFactory(uniqid('test', true));
    }

    public function testGetExtensionWithoutGivenExtensionWillThrowUnexpectedValueException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->getServiceProvider()->getExtension(uniqid('test', true));
    }

    public function testSetFactoryWithFactoryObjectWillSetSameFactoryInstance()
    {
        $id      = uniqid('id', true);
        $factory = $this->prophesize(ServiceProviderFactoryInterface::class)->reveal();

        $this->getServiceProvider()->setFactory($id, $factory);

        self::assertSame($factory, $this->getServiceProvider()->getFactory($id));
    }

    public function testSetFactoryWithStringFactoryWillSetFactoryFactoryInstanceForGivenString()
    {
        $id      = uniqid('id', true);
        $factory = 'stdClass';

        $this->getServiceProvider()->setFactory($id, $factory);

        self::assertInstanceOf(FactoryFactory::class, $this->getServiceProvider()->getFactory($id));
    }

    public function testSetFactoryWithCallableWillSetFactoryFactoryForGivenCallable()
    {
        $id             = uniqid('id', true);
        $object         = new \stdClass();
        $object->uniqid = uniqid('test', true);

        $factory = function(ContainerInterface $container) use ($object) {
            return $object;
        };

        $this->getServiceProvider()->setFactory($id, $factory);

        self::assertInstanceOf(FactoryFactory::class, $this->getServiceProvider()->getFactory($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        self::assertSame(
            $object,
            \call_user_func($this->getServiceProvider()->getFactory($id), $container)
        );
    }

    public function testSetAliasWillSetAliasForContainerGet()
    {
        $id             = uniqid('id', true);
        $alias          = uniqid('alias', true);
        $object         = new \stdClass();
        $object->uniqid = uniqid('test', true);

        $this->getServiceProvider()->setAlias($alias, $id);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($id)->willReturn($object);

        self::assertSame(
            $object,
            \call_user_func($this->getServiceProvider()->getFactory($alias), $container->reveal())
        );
    }

    public function testExtendWithExtensionObjectWillSetExtension()
    {
        $id        = uniqid('id', true);
        $extension = $this->prophesize(ServiceProviderExtensionInterface::class)->reveal();

        $this->getServiceProvider()->extend($id, $extension);

        self::assertSame($extension, $this->getServiceProvider()->getExtension($id));
    }

    public function testExtendWithCallableWillSetCallableExtensionToGivenId()
    {
        $id               = uniqid('id', true);
        $previous         = new \stdClass();
        $previous->uniqid = uniqid('test', true);
        $extension        = function(ContainerInterface $container, $previous = null) {
            return $previous;
        };

        $this->getServiceProvider()->extend($id, $extension);

        self::assertInstanceOf(CallableExtension::class, $this->getServiceProvider()->getExtension($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        self::assertSame(
            $previous,
            \call_user_func($this->getServiceProvider()->getExtension($id), $container, $previous)
        );
    }

    public function testExtendWithAlreadySetIdWillExtendExtension()
    {
        $id         = uniqid('id', true);
        $return1    = uniqid('return1', true);
        $extension1 = function(ContainerInterface $container, $previous = null) use ($return1) {
            return $previous . $return1;
        };
        $return2    = uniqid('return2', true);
        $extension2 = function(ContainerInterface $container, $previous = null) use ($return2) {
            return $previous . $return2;
        };

        $this->getServiceProvider()->extend($id, $extension1, false);
        $this->getServiceProvider()->extend($id, $extension2, false);

        self::assertInstanceOf(ExtendExtension::class, $this->getServiceProvider()->getExtension($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        self::assertEquals(
            $return1 . $return2,
            \call_user_func($this->getServiceProvider()->getExtension($id), $container)
        );
    }

    public function testExtendWithPrependAndAlreadySetIdWillPrependExtendExtension()
    {
        $id         = uniqid('id', true);
        $return1    = uniqid('return1', true);
        $extension1 = function(ContainerInterface $container, $previous = null) use ($return1) {
            return $previous . $return1;
        };
        $return2    = uniqid('return2', true);
        $extension2 = function(ContainerInterface $container, $previous = null) use ($return2) {
            return $previous . $return2;
        };

        $this->getServiceProvider()->extend($id, $extension1, true);
        $this->getServiceProvider()->extend($id, $extension2, true);

        self::assertInstanceOf(ExtendExtension::class, $this->getServiceProvider()->getExtension($id));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        self::assertEquals(
            $return2 . $return1,
            \call_user_func($this->getServiceProvider()->getExtension($id), $container)
        );
    }
}

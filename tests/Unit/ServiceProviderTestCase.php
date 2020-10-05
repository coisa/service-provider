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

use Psr\Container\ContainerInterface;

/**
 * Class ServiceProviderTestCase
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
abstract class ServiceProviderTestCase extends AbstractServiceProviderTestCase
{
    public function testServiceProviderExtendServiceProvider()
    {
        self::assertInstanceOf('CoiSA\\ServiceProvider\\ServiceProvider', $this->serviceProvider);
    }

    public function testSetFactoryWithFactoryObjectWillSetSameFactoryInstance()
    {
        $id      = \uniqid('id', true);
        $factory = $this->prophesize('CoiSA\\ServiceProvider\\Factory\\FactoryInterface')->reveal();

        $this->serviceProvider->setFactory($id, $factory);

        self::assertSame($factory, $this->serviceProvider->getFactory($id));
    }

    public function testSetFactoryWithStringFactoryWillSetFactoryFactoryInstanceForGivenString()
    {
        $id      = \uniqid('id', true);
        $factory = 'stdClass';

        $this->serviceProvider->setFactory($id, $factory);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Factory\\FactoryFactory',
            $this->serviceProvider->getFactory($id)
        );
    }

    public function testSetFactoryWithCallableWillSetFactoryFactoryForGivenCallable()
    {
        $id             = \uniqid('id', true);
        $object         = new \stdClass();
        $object->uniqid = \uniqid('test', true);

        $factory = function (ContainerInterface $container) use ($object) {
            return $object;
        };

        $this->serviceProvider->setFactory($id, $factory);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Factory\\FactoryFactory',
            $this->serviceProvider->getFactory($id)
        );

        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

        self::assertSame(
            $object,
            \call_user_func($this->serviceProvider->getFactory($id), $container)
        );
    }

    public function testSetAliasWillSetAliasForContainerGet()
    {
        $id             = \uniqid('id', true);
        $alias          = \uniqid('alias', true);
        $object         = new \stdClass();
        $object->uniqid = \uniqid('test', true);

        $this->serviceProvider->setAlias($alias, $id);

        $container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $container->get($id)->willReturn($object);

        self::assertSame(
            $object,
            \call_user_func($this->serviceProvider->getFactory($alias), $container->reveal())
        );
    }

    public function testExtendWithExtensionObjectWillSetExtension()
    {
        $id        = \uniqid('id', true);
        $extension = $this->prophesize('CoiSA\\ServiceProvider\\Extension\\ExtensionInterface')->reveal();

        $this->serviceProvider->extend($id, $extension);

        self::assertSame($extension, $this->serviceProvider->getExtension($id));
    }

    public function testExtendWithCallableWillSetCallableExtensionToGivenId()
    {
        $id               = \uniqid('id', true);
        $previous         = new \stdClass();
        $previous->uniqid = \uniqid('test', true);
        $extension        = function (ContainerInterface $container, $previous = null) {
            return $previous;
        };

        $this->serviceProvider->extend($id, $extension);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\CallableExtension',
            $this->serviceProvider->getExtension($id)
        );

        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

        self::assertSame(
            $previous,
            \call_user_func($this->serviceProvider->getExtension($id), $container, $previous)
        );
    }

    public function testExtendWithAlreadySetIdWillExtendExtension()
    {
        $id         = \uniqid('id', true);
        $return1    = \uniqid('return1', true);
        $extension1 = function (ContainerInterface $container, $previous = null) use ($return1) {
            return $previous . $return1;
        };
        $return2    = \uniqid('return2', true);
        $extension2 = function (ContainerInterface $container, $previous = null) use ($return2) {
            return $previous . $return2;
        };

        $this->serviceProvider->extend($id, $extension1, false);
        $this->serviceProvider->extend($id, $extension2, false);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\ExtendExtension',
            $this->serviceProvider->getExtension($id)
        );

        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

        self::assertEquals(
            $return1 . $return2,
            \call_user_func($this->serviceProvider->getExtension($id), $container)
        );
    }

    public function testExtendWithPrependAndAlreadySetIdWillPrependExtendExtension()
    {
        $id         = \uniqid('id', true);
        $return1    = \uniqid('return1', true);
        $extension1 = function (ContainerInterface $container, $previous = null) use ($return1) {
            return $previous . $return1;
        };
        $return2    = \uniqid('return2', true);
        $extension2 = function (ContainerInterface $container, $previous = null) use ($return2) {
            return $previous . $return2;
        };

        $this->serviceProvider->extend($id, $extension1, true);
        $this->serviceProvider->extend($id, $extension2, true);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\ExtendExtension',
            $this->serviceProvider->getExtension($id)
        );

        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

        self::assertEquals(
            $return2 . $return1,
            \call_user_func($this->serviceProvider->getExtension($id), $container)
        );
    }
}

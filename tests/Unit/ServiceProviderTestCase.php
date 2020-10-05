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
}

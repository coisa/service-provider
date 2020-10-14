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

use CoiSA\ServiceProvider\LaminasConfigServiceProvider;

/**
 * Class LaminasConfigServiceProviderTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
final class LaminasConfigServiceProviderTest extends ServiceProviderTestCase
{
    public function testConstructWillAddConfigToFactories()
    {
        $config          = array(
            \uniqid('config', true),
        );
        $serviceProvider = new LaminasConfigServiceProvider($config);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Factory\\ServiceFactory',
            $serviceProvider->getFactory('config')
        );

        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

        self::assertEquals(
            $config,
            \call_user_func($serviceProvider->getFactory('config'), $container)
        );
    }

    public function testConstructWillAddConfigToExtensions()
    {
        $previous        = array(
            \uniqid('previous', true),
        );
        $config          = array(
            \uniqid('config', true),
        );
        $serviceProvider = new LaminasConfigServiceProvider($config);

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\MergeConfigExtension',
            $serviceProvider->getExtension('config')
        );

        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

        self::assertEquals(
            \array_merge($previous, $config),
            \call_user_func($serviceProvider->getExtension('config'), $container, $previous)
        );
    }

    public function testConstructWithServicesDependenciesWillAddServiceFactoryForEachGivenService()
    {
        $config = array(
            'dependencies' => array(
                'services' => array(),
            ),
        );

        $total = \mt_rand(5, 20);
        for ($i = 0; $i < $total; $i++) {
            $id                                      = \uniqid('id', true);
            $config['dependencies']['services'][$id] = \uniqid('service', true);
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        self::assertCount($total, $factories);

        foreach ($factories as $factory) {
            self::assertInstanceOf(
                'CoiSA\\ServiceProvider\\Factory\\ServiceFactory',
                $factory
            );
        }
    }

    public function testConstructWithFactoriesDependenciesWillAddFactoryFactoryForEachGivenFactory()
    {
        $config = array(
            'dependencies' => array(
                'factories' => array(),
            ),
        );

        $total = \mt_rand(5, 20);
        for ($i = 0; $i < $total; $i++) {
            $id                                       = \uniqid('id', true);
            $config['dependencies']['factories'][$id] = 'stdClass';
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        self::assertCount($total, $factories);

        foreach ($factories as $factory) {
            self::assertInstanceOf(
                'CoiSA\\ServiceProvider\\Factory\\FactoryFactory',
                $factory
            );
        }
    }

    public function testConstructWithInvokablesDependenciesWillAddInvokableFactoryForEachGivenInvokable()
    {
        $config = array(
            'dependencies' => array(
                'invokables' => array(),
            ),
        );

        $total = \mt_rand(5, 20);
        for ($i = 0; $i < $total; $i++) {
            $id                                        = \uniqid('id', true);
            $config['dependencies']['invokables'][$id] = 'stdClass';
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        self::assertCount($total, $factories);

        foreach ($factories as $factory) {
            self::assertInstanceOf(
                'CoiSA\\ServiceProvider\\Factory\\InvokableFactory',
                $factory
            );
        }
    }

    public function testConstructWithDelegatorsDependenciesWillAddDelegatorExtensionForEachGivenDelegator()
    {
        $config = array(
            'dependencies' => array(
                'delegators' => array(),
            ),
        );

        $total = \mt_rand(5, 20);
        for ($i = 0; $i < $total; $i++) {
            $id                                        = \uniqid('id', true);
            $config['dependencies']['delegators'][$id] = array(
                \uniqid('delegator', true) => function() {
                    return true;
                },
            );
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $extensions = $serviceProvider->getExtensions();
        unset($extensions['config']);

        self::assertCount($total, $extensions);

        foreach ($extensions as $extension) {
            self::assertInstanceOf(
                'CoiSA\\ServiceProvider\\Extension\\DelegatorExtension',
                $extension
            );
        }
    }

    public function testConstructWithInitializersDependenciesWillAddInitializerExtensionForEveryFactory()
    {
        $config = array(
            'dependencies' => array(
                'services'     => array(),
                'initializers' => array(),
            ),
        );

        $total = \mt_rand(5, 20);
        for ($i = 0; $i < $total; $i++) {
            $id                                       = \uniqid('id', true);
            $config['dependencies']['services'][$id]  = \uniqid('service', true);
            $config['dependencies']['initializers'][] = function() {
                return true;
            };
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $extensions = $serviceProvider->getExtensions();
        unset($extensions['config']);

        self::assertCount($total, $extensions);

        foreach ($extensions as $extension) {
            self::assertInstanceOf(
                'CoiSA\\ServiceProvider\\Extension\\ExtendExtension',
                $extension
            );
        }
    }

    public function testConstructWithAliasesDependenciesWillAddAliasFactoryForEachGivenAlias()
    {
        $config = array(
            'dependencies' => array(
                'aliases' => array(),
            ),
        );

        $total = \mt_rand(5, 20);
        for ($i = 0; $i <= $total; $i++) {
            $id                                     = \uniqid('id', true);
            $config['dependencies']['aliases'][$id] = \uniqid('alias', true);
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        self::assertCount(
            \count($config['dependencies']['aliases']),
            $factories
        );

        foreach ($factories as $factory) {
            self::assertInstanceOf(
                'CoiSA\\ServiceProvider\\Factory\\AliasFactory',
                $factory
            );
        }
    }

    /**
     * @throws \CoiSA\ServiceProvider\Exception\ReflectionException
     *
     * @return LaminasConfigServiceProvider
     */
    protected function createServiceProvider()
    {
        return new LaminasConfigServiceProvider(array());
    }
}

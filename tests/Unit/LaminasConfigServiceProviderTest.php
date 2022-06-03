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

use CoiSA\ServiceProvider\Extension\DelegatorExtension;
use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\MergeConfigExtension;
use CoiSA\ServiceProvider\Factory\AliasFactory;
use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\InvokableFactory;
use CoiSA\ServiceProvider\Factory\ServiceFactory;
use CoiSA\ServiceProvider\LaminasConfigServiceProvider;
use Psr\Container\ContainerInterface;

/**
 * Class LaminasConfigServiceProviderTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\LaminasConfigServiceProvider
 */
final class LaminasConfigServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructWillAddConfigToFactories(): void
    {
        $config          = [uniqid('config', true)];
        $serviceProvider = new LaminasConfigServiceProvider($config);

        static::assertInstanceOf(ServiceFactory::class, $serviceProvider->getFactory('config'));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        static::assertSame(
            $config,
            \call_user_func($serviceProvider->getFactory('config'), $container)
        );
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWillAddConfigToExtensions(): void
    {
        $previous        = [
            uniqid('previous', true),
        ];
        $config          = [
            uniqid('config', true),
        ];
        $serviceProvider = new LaminasConfigServiceProvider($config);

        static::assertInstanceOf(MergeConfigExtension::class, $serviceProvider->getExtension('config'));

        $container = $this->prophesize(ContainerInterface::class)->reveal();

        static::assertSame(
            array_merge($previous, $config),
            \call_user_func($serviceProvider->getExtension('config'), $container, $previous)
        );
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithServicesDependenciesWillAddServiceFactoryForEachGivenService(): void
    {
        $config = [
            'dependencies' => [
                'services' => [],
            ],
        ];

        $total = random_int(5, 20);
        for ($i = 0; $i < $total; ++$i) {
            $id                                      = uniqid('id', true);
            $config['dependencies']['services'][$id] = uniqid('service', true);
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        static::assertCount($total, $factories);

        foreach ($factories as $factory) {
            static::assertInstanceOf(ServiceFactory::class, $factory);
        }
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithFactoriesDependenciesWillAddFactoryFactoryForEachGivenFactory(): void
    {
        $config = [
            'dependencies' => [
                'factories' => [],
            ],
        ];

        $total = random_int(5, 20);
        for ($i = 0; $i < $total; ++$i) {
            $id                                       = uniqid('id', true);
            $config['dependencies']['factories'][$id] = 'stdClass';
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        static::assertCount($total, $factories);

        foreach ($factories as $factory) {
            static::assertInstanceOf(FactoryFactory::class, $factory);
        }
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithInvokablesDependenciesWillAddInvokableFactoryForEachGivenInvokable(): void
    {
        $config = [
            'dependencies' => [
                'invokables' => [],
            ],
        ];

        $total = random_int(5, 20);
        for ($i = 0; $i < $total; ++$i) {
            $id                                        = uniqid('id', true);
            $config['dependencies']['invokables'][$id] = 'stdClass';
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        static::assertCount($total, $factories);

        foreach ($factories as $factory) {
            static::assertInstanceOf(InvokableFactory::class, $factory);
        }
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithDelegatorsDependenciesWillAddDelegatorExtensionForEachGivenDelegator(): void
    {
        $config = [
            'dependencies' => [
                'delegators' => [],
            ],
        ];

        $total = random_int(5, 20);
        for ($i = 0; $i < $total; ++$i) {
            $id                                        = uniqid('id', true);
            $config['dependencies']['delegators'][$id] = [
                uniqid('delegator', true) => fn () => true,
            ];
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $extensions = $serviceProvider->getExtensions();
        unset($extensions['config']);

        static::assertCount($total, $extensions);

        foreach ($extensions as $extension) {
            static::assertInstanceOf(DelegatorExtension::class, $extension);
        }
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithInitializersDependenciesWillAddInitializerExtensionForEveryFactory(): void
    {
        $config = [
            'dependencies' => [
                'services'     => [],
                'initializers' => [],
            ],
        ];

        $total = random_int(5, 20);
        for ($i = 0; $i < $total; ++$i) {
            $id                                       = uniqid('id', true);
            $config['dependencies']['services'][$id]  = uniqid('service', true);
            $config['dependencies']['initializers'][] = fn () => true;
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $extensions = $serviceProvider->getExtensions();
        unset($extensions['config']);

        static::assertCount($total, $extensions);

        foreach ($extensions as $extension) {
            static::assertInstanceOf(ExtendExtension::class, $extension);
        }
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithAliasesDependenciesWillAddAliasFactoryForEachGivenAlias(): void
    {
        $config = [
            'dependencies' => [
                'aliases' => [],
            ],
        ];

        $total = random_int(5, 20);
        for ($i = 0; $i <= $total; ++$i) {
            $id                                     = uniqid('id', true);
            $config['dependencies']['aliases'][$id] = uniqid('alias', true);
        }

        $serviceProvider = new LaminasConfigServiceProvider($config);

        $factories = $serviceProvider->getFactories();
        unset($factories['config']);

        static::assertCount(
            \count($config['dependencies']['aliases']),
            $factories
        );

        foreach ($factories as $factory) {
            static::assertInstanceOf(AliasFactory::class, $factory);
        }
    }

    /**
     * @throws \CoiSA\ServiceProvider\Exception\ReflectionException
     */
    protected function createServiceProvider(): LaminasConfigServiceProvider
    {
        return new LaminasConfigServiceProvider([]);
    }
}

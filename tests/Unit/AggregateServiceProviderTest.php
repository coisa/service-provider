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

use CoiSA\ServiceProvider\AggregateServiceProvider;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\CallableFactory;
use CoiSA\ServiceProvider\ServiceProvider;

/**
 * Class AggregateServiceProviderTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
final class AggregateServiceProviderTest extends ServiceProviderTestCase
{
    public function testGetServiceProvidersWillReturnGivenServiceProviders()
    {
        $total            = mt_rand(5, 10);
        $serviceProviders = [];

        for ($i = 0; $i <= $total; $i++) {
            $serviceProvider = $this->prophesize(ServiceProvider::class);
            $serviceProvider->getFactories()->willReturn([]);
            $serviceProvider->getExtensions()->willReturn([]);

            $serviceProviders[] = $serviceProvider->reveal();
        }

        $serviceProviderAggregator = new AggregateServiceProvider($serviceProviders);

        self::assertEquals($serviceProviders, $serviceProviderAggregator->getServiceProviders());
    }

    public function testGetIteratorWillReturnIteratorOfGivenServiceProviders()
    {
        $total            = mt_rand(5, 10);
        $serviceProviders = [];

        for ($i = 0; $i <= $total; $i++) {
            $serviceProvider = $this->prophesize(ServiceProvider::class);
            $serviceProvider->getFactories()->willReturn([]);
            $serviceProvider->getExtensions()->willReturn([]);

            $serviceProviders[] = $serviceProvider->reveal();
        }

        $serviceProviderAggregator = new AggregateServiceProvider($serviceProviders);

        self::assertEquals(
            $serviceProviders,
            iterator_to_array($serviceProviderAggregator->getIterator())
        );
    }

    public function testAppendWillAppendServiceProvider()
    {
        $serviceProvider = $this->prophesize(ServiceProvider::class);
        $serviceProvider->getFactories()->willReturn([]);
        $serviceProvider->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider->reveal());

        self::assertEquals([$serviceProvider->reveal()], $serviceProviderAggregator->getServiceProviders());
    }

    public function testAppendWillExtendGivenServiceProviderExtensions()
    {
        $serviceProvider = $this->prophesize(ServiceProvider::class);
        $serviceProvider->getFactories()->willReturn([]);
        $serviceProvider->getExtensions()->willReturn(['id' => function() {
        }]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider->reveal());

        self::assertInstanceOf(
            ServiceProviderExtensionInterface::class,
            $serviceProviderAggregator->getExtension('id')
        );
    }

    public function testAppendWillSetServiceProviderFactories()
    {
        $factory1 = new CallableFactory(function() {
            return 1;
        });
        $factory2 = new CallableFactory(function() {
            return 2;
        });

        $serviceProvider1 = $this->prophesize(ServiceProvider::class);
        $serviceProvider1->getFactories()->willReturn(['test' => $factory1]);
        $serviceProvider1->getExtensions()->willReturn([]);

        $serviceProvider2 = $this->prophesize(ServiceProvider::class);
        $serviceProvider2->getFactories()->willReturn(['test' => $factory2]);
        $serviceProvider2->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider1->reveal());

        self::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));

        $serviceProviderAggregator->append($serviceProvider2->reveal());

        self::assertSame($factory2, $serviceProviderAggregator->getFactory('test'));
    }

    public function testPrependWillPrependServiceProvider()
    {
        $serviceProviderAppend = $this->prophesize(ServiceProvider::class);
        $serviceProviderAppend->getFactories()->willReturn([]);
        $serviceProviderAppend->getExtensions()->willReturn([]);

        $serviceProviderPrepend = $this->prophesize(ServiceProvider::class);
        $serviceProviderPrepend->getFactories()->willReturn([]);
        $serviceProviderPrepend->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProviderAppend->reveal());
        $serviceProviderAggregator->prepend($serviceProviderPrepend->reveal());

        self::assertEquals([
            $serviceProviderPrepend->reveal(),
            $serviceProviderAppend->reveal(),
        ], $serviceProviderAggregator->getServiceProviders());
    }

    public function testPrependWillNotOverwriteFactories()
    {
        $factory1 = new CallableFactory(function() {
            return 1;
        });
        $factory2 = new CallableFactory(function() {
            return 2;
        });

        $serviceProvider1 = $this->prophesize(ServiceProvider::class);
        $serviceProvider1->getFactories()->willReturn(['test' => $factory1]);
        $serviceProvider1->getExtensions()->willReturn([]);

        $serviceProvider2 = $this->prophesize(ServiceProvider::class);
        $serviceProvider2->getFactories()->willReturn(['test' => $factory2]);
        $serviceProvider2->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider1->reveal());

        self::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));

        $serviceProviderAggregator->prepend($serviceProvider2->reveal());

        self::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));
    }

    public function testPrependWillExtendGivenServiceProviderExtensions()
    {
        $serviceProvider = $this->prophesize(ServiceProvider::class);
        $serviceProvider->getFactories()->willReturn([]);
        $serviceProvider->getExtensions()->willReturn(['id' => function() {
        }]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->prepend($serviceProvider->reveal());

        self::assertInstanceOf(
            ServiceProviderExtensionInterface::class,
            $serviceProviderAggregator->getExtension('id')
        );
    }

    protected function createServiceProvider()
    {
        return new AggregateServiceProvider();
    }
}

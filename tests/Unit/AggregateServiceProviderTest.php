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

use CoiSA\ServiceProvider\AggregateServiceProvider;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\CallableFactory;
use CoiSA\ServiceProvider\ServiceProvider;

/**
 * Class AggregateServiceProviderTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\AggregateServiceProvider
 */
final class AggregateServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * @covers ::getServiceProviders
     */
    public function testGetServiceProvidersWillReturnGivenServiceProviders(): void
    {
        $total            = random_int(5, 10);
        $serviceProviders = [];

        for ($i = 0; $i <= $total; ++$i) {
            $serviceProvider = $this->prophesize(ServiceProvider::class);
            $serviceProvider->getFactories()->willReturn([]);
            $serviceProvider->getExtensions()->willReturn([]);

            $serviceProviders[] = $serviceProvider->reveal();
        }

        $serviceProviderAggregator = new AggregateServiceProvider($serviceProviders);

        static::assertSame($serviceProviders, $serviceProviderAggregator->getServiceProviders());
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIteratorWillReturnIteratorOfGivenServiceProviders(): void
    {
        $total            = random_int(5, 10);
        $serviceProviders = [];

        for ($i = 0; $i <= $total; ++$i) {
            $serviceProvider = $this->prophesize(ServiceProvider::class);
            $serviceProvider->getFactories()->willReturn([]);
            $serviceProvider->getExtensions()->willReturn([]);

            $serviceProviders[] = $serviceProvider->reveal();
        }

        $serviceProviderAggregator = new AggregateServiceProvider($serviceProviders);

        static::assertSame(
            $serviceProviders,
            iterator_to_array($serviceProviderAggregator->getIterator())
        );
    }

    /**
     * @covers ::append
     */
    public function testAppendWillAppendServiceProvider(): void
    {
        $serviceProvider = $this->prophesize(ServiceProvider::class);
        $serviceProvider->getFactories()->willReturn([]);
        $serviceProvider->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider->reveal());

        static::assertSame([$serviceProvider->reveal()], $serviceProviderAggregator->getServiceProviders());
    }

    /**
     * @covers ::append
     */
    public function testAppendWillExtendGivenServiceProviderExtensions(): void
    {
        $serviceProvider = $this->prophesize(ServiceProvider::class);
        $serviceProvider->getFactories()->willReturn([]);
        $serviceProvider->getExtensions()->willReturn(['id' => function (): void {
        }]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider->reveal());

        static::assertInstanceOf(
            ServiceProviderExtensionInterface::class,
            $serviceProviderAggregator->getExtension('id')
        );
    }

    /**
     * @covers ::append
     */
    public function testAppendWillSetServiceProviderFactories(): void
    {
        $factory1 = new CallableFactory(fn () => 1);
        $factory2 = new CallableFactory(fn () => 2);

        $serviceProvider1 = $this->prophesize(ServiceProvider::class);
        $serviceProvider1->getFactories()->willReturn(['test' => $factory1]);
        $serviceProvider1->getExtensions()->willReturn([]);

        $serviceProvider2 = $this->prophesize(ServiceProvider::class);
        $serviceProvider2->getFactories()->willReturn(['test' => $factory2]);
        $serviceProvider2->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider1->reveal());

        static::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));

        $serviceProviderAggregator->append($serviceProvider2->reveal());

        static::assertSame($factory2, $serviceProviderAggregator->getFactory('test'));
    }

    /**
     * @covers ::prepend
     */
    public function testPrependWillPrependServiceProvider(): void
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

        static::assertSame([
            $serviceProviderPrepend->reveal(),
            $serviceProviderAppend->reveal(),
        ], $serviceProviderAggregator->getServiceProviders());
    }

    /**
     * @covers ::prepend
     */
    public function testPrependWillNotOverwriteFactories(): void
    {
        $factory1 = new CallableFactory(fn () => 1);
        $factory2 = new CallableFactory(fn () => 2);

        $serviceProvider1 = $this->prophesize(ServiceProvider::class);
        $serviceProvider1->getFactories()->willReturn(['test' => $factory1]);
        $serviceProvider1->getExtensions()->willReturn([]);

        $serviceProvider2 = $this->prophesize(ServiceProvider::class);
        $serviceProvider2->getFactories()->willReturn(['test' => $factory2]);
        $serviceProvider2->getExtensions()->willReturn([]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider1->reveal());

        static::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));

        $serviceProviderAggregator->prepend($serviceProvider2->reveal());

        static::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));
    }

    /**
     * @covers ::prepend
     */
    public function testPrependWillExtendGivenServiceProviderExtensions(): void
    {
        $serviceProvider = $this->prophesize(ServiceProvider::class);
        $serviceProvider->getFactories()->willReturn([]);
        $serviceProvider->getExtensions()->willReturn(['id' => function (): void {
        }]);

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->prepend($serviceProvider->reveal());

        static::assertInstanceOf(
            ServiceProviderExtensionInterface::class,
            $serviceProviderAggregator->getExtension('id')
        );
    }

    protected function createServiceProvider(): AggregateServiceProvider
    {
        return new AggregateServiceProvider();
    }
}

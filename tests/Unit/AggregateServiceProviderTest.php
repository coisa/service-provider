<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 *
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */
namespace CoiSA\ServiceProvider\Test\Unit;

use CoiSA\ServiceProvider\AggregateServiceProvider;
use CoiSA\ServiceProvider\Factory\CallableFactory;

/**
 * Class AggregateServiceProviderTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
final class AggregateServiceProviderTest extends ServiceProviderTestCase
{
    public function testGetServiceProvidersWillReturnGivenServiceProviders()
    {
        $total            = \mt_rand(5, 10);
        $serviceProviders = array();

        for ($i = 0; $i <= $total; $i++) {
            $serviceProvider = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
            $serviceProvider->getFactories()->willReturn(array());
            $serviceProvider->getExtensions()->willReturn(array());

            $serviceProviders[] = $serviceProvider->reveal();
        }

        $serviceProviderAggregator = new AggregateServiceProvider($serviceProviders);

        self::assertEquals($serviceProviders, $serviceProviderAggregator->getServiceProviders());
    }

    public function testGetIteratorWillReturnIteratorOfGivenServiceProviders()
    {
        $total            = \mt_rand(5, 10);
        $serviceProviders = array();

        for ($i = 0; $i <= $total; $i++) {
            $serviceProvider = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
            $serviceProvider->getFactories()->willReturn(array());
            $serviceProvider->getExtensions()->willReturn(array());

            $serviceProviders[] = $serviceProvider->reveal();
        }

        $serviceProviderAggregator = new AggregateServiceProvider($serviceProviders);

        self::assertEquals(
            $serviceProviders,
            \iterator_to_array($serviceProviderAggregator->getIterator())
        );
    }

    public function testAppendWillAppendServiceProvider()
    {
        $serviceProvider = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider->getFactories()->willReturn(array());
        $serviceProvider->getExtensions()->willReturn(array());

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider->reveal());

        self::assertEquals(array($serviceProvider->reveal()), $serviceProviderAggregator->getServiceProviders());
    }

    public function testAppendWillExtendGivenServiceProviderExtensions()
    {
        $serviceProvider = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider->getFactories()->willReturn(array());
        $serviceProvider->getExtensions()->willReturn(array('id' => function() {
        }));

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider->reveal());

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface',
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

        $serviceProvider1 = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider1->getFactories()->willReturn(array('test' => $factory1));
        $serviceProvider1->getExtensions()->willReturn(array());

        $serviceProvider2 = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider2->getFactories()->willReturn(array('test' => $factory2));
        $serviceProvider2->getExtensions()->willReturn(array());

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider1->reveal());

        self::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));

        $serviceProviderAggregator->append($serviceProvider2->reveal());

        self::assertSame($factory2, $serviceProviderAggregator->getFactory('test'));
    }

    public function testPrependWillPrependServiceProvider()
    {
        $serviceProviderAppend = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProviderAppend->getFactories()->willReturn(array());
        $serviceProviderAppend->getExtensions()->willReturn(array());

        $serviceProviderPrepend = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProviderPrepend->getFactories()->willReturn(array());
        $serviceProviderPrepend->getExtensions()->willReturn(array());

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProviderAppend->reveal());
        $serviceProviderAggregator->prepend($serviceProviderPrepend->reveal());

        self::assertEquals(array(
            $serviceProviderPrepend->reveal(),
            $serviceProviderAppend->reveal(),
        ), $serviceProviderAggregator->getServiceProviders());
    }

    public function testPrependWillNotOverwriteFactories()
    {
        $factory1 = new CallableFactory(function() {
            return 1;
        });
        $factory2 = new CallableFactory(function() {
            return 2;
        });

        $serviceProvider1 = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider1->getFactories()->willReturn(array('test' => $factory1));
        $serviceProvider1->getExtensions()->willReturn(array());

        $serviceProvider2 = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider2->getFactories()->willReturn(array('test' => $factory2));
        $serviceProvider2->getExtensions()->willReturn(array());

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->append($serviceProvider1->reveal());

        self::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));

        $serviceProviderAggregator->prepend($serviceProvider2->reveal());

        self::assertSame($factory1, $serviceProviderAggregator->getFactory('test'));
    }

    public function testPrependWillExtendGivenServiceProviderExtensions()
    {
        $serviceProvider = $this->prophesize('CoiSA\\ServiceProvider\\ServiceProvider');
        $serviceProvider->getFactories()->willReturn(array());
        $serviceProvider->getExtensions()->willReturn(array('id' => function() {
        }));

        $serviceProviderAggregator = new AggregateServiceProvider();
        $serviceProviderAggregator->prepend($serviceProvider->reveal());

        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface',
            $serviceProviderAggregator->getExtension('id')
        );
    }

    protected function createServiceProvider()
    {
        return new AggregateServiceProvider();
    }
}

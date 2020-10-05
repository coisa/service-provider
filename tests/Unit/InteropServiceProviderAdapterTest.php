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

use CoiSA\ServiceProvider\InteropServiceProviderAdapter;
use Interop\Container\ServiceProviderInterface;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class InteropServiceProviderAdapterTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 */
final class InteropServiceProviderAdapterTest extends ServiceProviderTestCase
{
    /** @var ObjectProphecy|ServiceProviderInterface */
    private $interopServiceProvider;

    /** @var mixed[] */
    private $factories;

    /** @var mixed[] */
    private $extensions;

    public function setUp()
    {
        $this->interopServiceProvider = $this->prophesize('Interop\\Container\\ServiceProviderInterface');

        $this->factories = array(
            \uniqid('factory', true),
            \uniqid('factory', true),
            \uniqid('factory', true),
        );

        $this->extensions = array(
            \uniqid('extension', true),
            \uniqid('extension', true),
            \uniqid('extension', true),
        );

        $this->interopServiceProvider->getFactories()->willReturn($this->factories);
        $this->interopServiceProvider->getExtensions()->willReturn($this->extensions);

        $this->serviceProvider = new InteropServiceProviderAdapter($this->interopServiceProvider->reveal());
    }

    public function testGetFactoriesWillReturnSameFactoriesFromGivenInteropServiceProvider()
    {
        self::assertEquals(
            $this->factories,
            $this->serviceProvider->getFactories()
        );
    }

    public function testGetExtensionsWillReturnSameExtensionsFromGivenInteropServiceProvider()
    {
        self::assertEquals(
            $this->extensions,
            $this->serviceProvider->getExtensions()
        );
    }
}

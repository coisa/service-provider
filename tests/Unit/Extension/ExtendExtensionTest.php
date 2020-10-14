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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class ExtendExtensionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class ExtendExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|ServiceProviderExtensionInterface */
    private $currentExtension;

    /** @var ObjectProphecy|ServiceProviderExtensionInterface */
    private $wrapperExtension;

    public function setUp()
    {
        $this->container        = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->currentExtension = $this->prophesize(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface'
        );
        $this->wrapperExtension = $this->prophesize(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface'
        );

        $this->extension = new ExtendExtension(
            $this->currentExtension->reveal(),
            $this->wrapperExtension->reveal()
        );
    }

    public function testInvokeWithContainerWillResolveBothExtensions()
    {
        $extendExtension = $this->extension;
        $container       = $this->container->reveal();

        $previous = \uniqid('previous', true);
        $return   = \uniqid('return', true);

        $this->currentExtension->__invoke($container, null)->shouldBeCalledOnce()->willReturn($previous);
        $this->wrapperExtension->__invoke($container, $previous)->shouldBeCalledOnce()->willReturn($return);

        self::assertEquals($return, $extendExtension($container));
    }
}

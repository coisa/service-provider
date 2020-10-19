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
namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class ExtendExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class ExtendExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|ServiceProviderExtensionInterface */
    private $extension;

    /** @var ObjectProphecy|ServiceProviderExtensionInterface */
    private $wrapper;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->extension = $this->prophesize(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface'
        );
        $this->wrapper = $this->prophesize(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface'
        );
    }

    public function testInvokeWithContainerWillResolveBothExtensions()
    {
        $container = $this->container->reveal();

        $previous = \uniqid('previous', true);
        $return   = \uniqid('return', true);

        $this->extension->__invoke($container, null)->shouldBeCalledOnce()->willReturn($previous);
        $this->wrapper->__invoke($container, $previous)->shouldBeCalledOnce()->willReturn($return);

        self::assertEquals(
            $return,
            \call_user_func($this->getExtension(), $container)
        );
    }

    /**
     * @return ExtendExtension
     */
    protected function getExtension()
    {
        return new ExtendExtension(
            $this->extension->reveal(),
            $this->wrapper->reveal()
        );
    }
}

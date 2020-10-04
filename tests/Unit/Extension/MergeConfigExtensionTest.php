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

use CoiSA\ServiceProvider\Extension\MergeConfigExtension;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class MergeConfigExtensionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class MergeConfigExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->extension = new MergeConfigExtension(array());
    }

    public function provideNotArrayValues()
    {
        return array(
            array(true),
            array(false),
            array(\uniqid('test', true)),
            array(\mt_rand(1, 100)),
            array(new \stdClass()),
        );
    }

    /**
     * @dataProvider provideNotArrayValues
     * @expectedException \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     *
     * @param mixed $previous
     */
    public function testInvokeWithNotArrayPreviousWillReturnInvalidArgumentException($previous)
    {
        \call_user_func($this->extension, $this->container->reveal(), $previous);
    }
}

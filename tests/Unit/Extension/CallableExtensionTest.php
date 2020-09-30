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

use CoiSA\ServiceProvider\Extension\CallableExtension;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class CallableExtensionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class CallableExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var \stdClass */
    private $object;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');

        $object = $this->object = new \stdClass();

        $this->extension = new CallableExtension(function ($container, $previous = null) use ($object) {
            return $object;
        });
    }

    public function provideInvalidConstructorArgument()
    {
        return array(
            array(null),
            array(false),
            array(true),
            array(\uniqid('string', true)),
            array(\mt_rand(1, 1000)),
        );
    }

    /**
     * @dataProvider provideInvalidConstructorArgument
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $invalidArgument
     */
    public function testConstructWithInvalidArgumentWillThrowInvalidArgumentException($invalidArgument)
    {
        new CallableExtension($invalidArgument);
    }

    public function testInvokeWillReturnCallableResult()
    {
        $callableExtension = $this->extension;

        self::assertSame($this->object, $callableExtension($this->container->reveal()));
    }
}

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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Factory\CallableFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class CallableFactoryTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
final class CallableFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var callable */
    private $callable;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');

        $result         = \uniqid('callable', true);
        $this->callable = function(ContainerInterface $container) use ($result) {
            return $result;
        };

        $this->factory   = new CallableFactory($this->callable);
    }

    public function provideNonCallableValues()
    {
        return array(
            array(true),
            array(false),
            array(\uniqid('test', true)),
            array(array(\uniqid('test', true))),
            array(\mt_rand(1, 100)),
            array(new \stdClass()),
        );
    }

    /**
     * @dataProvider provideNonCallableValues
     * @expectedException \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     *
     * @param mixed $callable
     */
    public function testConstructWithNonCallableArgumentWillThrowInvalidArgumentException($callable)
    {
        new CallableFactory($callable);
    }

    public function testInvokeWillReturnCallableResult()
    {
        $result = \call_user_func($this->callable, $this->container->reveal());

        self::assertEquals($result, \call_user_func($this->factory, $this->container->reveal()));
    }
}

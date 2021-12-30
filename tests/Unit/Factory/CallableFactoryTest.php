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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Factory\CallableFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class CallableFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
final class CallableFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var callable */
    private $callable;

    public function setUp(): void
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');

        $result         = uniqid('callable', true);
        $this->callable = function(ContainerInterface $container) use ($result) {
            return $result;
        };
    }

    public function provideNonCallableValues()
    {
        return [
            [true],
            [false],
            [uniqid('test', true)],
            [[uniqid('test', true)]],
            [mt_rand(1, 100)],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provideNonCallableValues
     *
     * @param mixed $callable
     */
    public function testConstructWithNonCallableArgumentWillThrowInvalidArgumentException($callable)
    {
        $this->expectException(InvalidArgumentException::class);

        new CallableFactory($callable);
    }

    public function testInvokeWillReturnCallableResult()
    {
        $result = \call_user_func($this->callable, $this->container->reveal());

        self::assertEquals($result, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @return CallableFactory
     */
    protected function getFactory()
    {
        return new CallableFactory($this->callable);
    }
}

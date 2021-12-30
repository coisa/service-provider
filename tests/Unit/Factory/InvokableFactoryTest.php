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

use CoiSA\ServiceProvider\Factory\InvokableFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class InvokableFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
final class InvokableFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var string */
    private $invokable;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->invokable = 'stdClass';
    }

    public function provideNonStringArgument()
    {
        return [
            [true],
            [false],
            [[uniqid('test', true)]],
            [mt_rand(1, 100)],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provideNonStringArgument
     * @expectedException \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     *
     * @param mixed $invokable
     */
    public function testConstructWithNonStringInvokableWillThrowInvalidArgumentException($invokable)
    {
        new InvokableFactory($invokable);
    }

    /**
     * @expectedException \CoiSA\ServiceProvider\Exception\ReflectionException
     */
    public function testConstructWithNonExistentClassArgumentWillThrowReflectionException()
    {
        new InvokableFactory(uniqid('invokable', true));
    }

    public function testInvokeWillReturnNewInstanceOfGivenInvokableClassNamespace()
    {
        self::assertInstanceOf('stdClass', \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @throws \CoiSA\ServiceProvider\Exception\ReflectionException
     *
     * @return InvokableFactory
     */
    protected function getFactory()
    {
        return new InvokableFactory($this->invokable);
    }
}

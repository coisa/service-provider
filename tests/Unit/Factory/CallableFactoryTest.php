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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Factory\CallableFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class CallableFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 *
 * @internal
 * @coversNothing
 */
final class CallableFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var callable */
    private $callable;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        $result         = uniqid('callable', true);
        $this->callable = fn (ContainerInterface $container) => $result;
    }

    public function provideNonCallableValues()
    {
        return [
            [true],
            [false],
            [uniqid('test', true)],
            [[uniqid('test', true)]],
            [random_int(1, 100)],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provideNonCallableValues
     *
     * @param mixed $callable
     */
    public function testConstructWithNonCallableArgumentWillThrowInvalidArgumentException($callable): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CallableFactory($callable);
    }

    public function testInvokeWillReturnCallableResult(): void
    {
        $result = \call_user_func($this->callable, $this->container->reveal());

        static::assertSame($result, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @return CallableFactory
     */
    protected function getFactory()
    {
        return new CallableFactory($this->callable);
    }
}

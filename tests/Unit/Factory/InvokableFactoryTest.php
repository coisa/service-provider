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
use CoiSA\ServiceProvider\Exception\ReflectionException;
use CoiSA\ServiceProvider\Factory\InvokableFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class InvokableFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\Factory\InvokableFactory
 */
final class InvokableFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var string */
    private $invokable;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->invokable = 'stdClass';
    }

    public function provideNonStringArgument(): array
    {
        return [
            [true],
            [false],
            [[uniqid('test', true)]],
            [random_int(1, 100)],
            [new \stdClass()],
        ];
    }

    /**
     * @param mixed $invokable
     *
     * @dataProvider provideNonStringArgument
     * @covers ::__construct
     */
    public function testConstructWithNonStringInvokableWillThrowInvalidArgumentException($invokable): void
    {
        $this->expectException(InvalidArgumentException::class);

        new InvokableFactory($invokable);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithNonExistentClassArgumentWillThrowReflectionException(): void
    {
        $this->expectException(ReflectionException::class);

        new InvokableFactory(uniqid('invokable', true));
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWillReturnNewInstanceOfGivenInvokableClassNamespace(): void
    {
        static::assertInstanceOf(
            \stdClass::class,
            \call_user_func($this->getFactory(), $this->container->reveal())
        );
    }

    /**
     * @throws \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     */
    protected function getFactory(): InvokableFactory
    {
        return new InvokableFactory($this->invokable);
    }
}

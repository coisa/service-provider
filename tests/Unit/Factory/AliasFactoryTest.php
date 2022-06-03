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
use CoiSA\ServiceProvider\Factory\AliasFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class AliasFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\Factory\AliasFactory
 */
final class AliasFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var string */
    private $service;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->service   = uniqid('test', true);
    }

    public function provideNonStringValues()
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
     * @param mixed $service
     *
     * @dataProvider provideNonStringValues
     * @covers ::__construct
     */
    public function testConstructWithNonStringArgumentWillThrowInvalidArgumentException($service): void
    {
        $this->expectException(InvalidArgumentException::class);

        new AliasFactory($service);
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWillReturnContainerGetService(): void
    {
        $object         = new \stdClass();
        $object->uniqid = uniqid('uniqid', true);

        $this->container->get($this->service)->shouldBeCalledOnce()->willReturn($object);

        static::assertSame($object, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    protected function getFactory(): AliasFactory
    {
        return new AliasFactory($this->service);
    }
}

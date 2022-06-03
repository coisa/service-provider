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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Extension\DelegatorExtension;
use CoiSA\ServiceProvider\Factory\ServiceFactory;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;

/**
 * Class DelegatorExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\Extension\DelegatorExtension
 */
final class DelegatorExtensionTest extends AbstractExtensionTestCase
{
    /** @var string */
    private $id;

    /** @var callable */
    private $delegator;

    protected function setUp(): void
    {
        $id = uniqid('id', true);

        $this->id        = $id;
        $this->delegator = function (ContainerInterface $container, $name, $callable) use ($id): void {
            Assert::assertEquals($id, $name);
            Assert::assertInstanceOf(ServiceFactory::class, $callable);
        };
    }

    public function provideInvalidIdArgument()
    {
        return [
            [null],
            [false],
            [true],
            [fn () => true],
            [random_int(1, 1000)],
        ];
    }

    public function provideInvalidDelegatorArgument()
    {
        return [
            [null],
            [false],
            [true],
            [uniqid('test', true)],
            [random_int(1, 1000)],
        ];
    }

    /**
     * @param mixed $invalidId
     *
     * @dataProvider provideInvalidIdArgument
     * @covers ::__construct
     */
    public function testConstructWithNotStringIdArgumentWillThrowInvalidArgumentException($invalidId): void
    {
        $this->expectException(InvalidArgumentException::class);

        new DelegatorExtension($invalidId, fn () => true);
    }

    /**
     * @param mixed $invalidDelegator
     *
     * @dataProvider provideInvalidDelegatorArgument
     * @covers ::__construct
     */
    public function testConstructWithNotStringDelegatorArgumentWillThrowInvalidArgumentException(
        $invalidDelegator
    ): void {
        $this->expectException(InvalidArgumentException::class);

        new DelegatorExtension(uniqid('id', true), $invalidDelegator);
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWillCallDelegatorWithGivenIdAndCallableServiceFactory(): void
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();

        \call_user_func($this->getExtension(), $container);
    }

    protected function getExtension(): DelegatorExtension
    {
        return new DelegatorExtension($this->id, $this->delegator);
    }
}

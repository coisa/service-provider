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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Extension\CallableExtension;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class CallableExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class CallableExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var callable */
    private $callable;

    public function setUp(): void
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');

        $object         = new \stdClass();
        $object->uniqid = uniqid('test', true);

        $this->callable = function() use ($object) {
            return $object;
        };
    }

    public function provideInvalidConstructorArgument()
    {
        return [
            [null],
            [false],
            [true],
            [uniqid('string', true)],
            [mt_rand(1, 1000)],
        ];
    }

    /**
     * @dataProvider provideInvalidConstructorArgument
     *
     * @param mixed $invalidArgument
     */
    public function testConstructWithInvalidArgumentWillThrowInvalidArgumentException($invalidArgument)
    {
        $this->expectException(InvalidArgumentException::class);
        new CallableExtension($invalidArgument);
    }

    public function testInvokeWillReturnCallableResult()
    {
        self::assertSame(
            \call_user_func($this->callable),
            \call_user_func($this->getExtension(), $this->container->reveal())
        );
    }

    /**
     * @return CallableExtension
     */
    protected function getExtension()
    {
        return new CallableExtension($this->callable);
    }
}

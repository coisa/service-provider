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
use CoiSA\ServiceProvider\Extension\DelegatorExtension;
use CoiSA\ServiceProvider\Factory\ServiceFactory;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;

/**
 * Class DelegatorExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class DelegatorExtensionTest extends AbstractExtensionTestCase
{
    /** @var string */
    private $id;

    /** @var callable */
    private $delegator;

    public function setUp(): void
    {
        $id = uniqid('id', true);

        $this->id        = $id;
        $this->delegator = function(ContainerInterface $container, $name, $callable) use ($id) {
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
            [function() {
                return true;
            }],
            [mt_rand(1, 1000)],
        ];
    }

    public function provideInvalidDelegatorArgument()
    {
        return [
            [null],
            [false],
            [true],
            [uniqid('test', true)],
            [mt_rand(1, 1000)],
        ];
    }

    /**
     * @dataProvider provideInvalidIdArgument
     *
     * @param mixed $invalidId
     */
    public function testConstructWithNotStringIdArgumentWillThrowInvalidArgumentException($invalidId)
    {
        $this->expectException(InvalidArgumentException::class);

        new DelegatorExtension($invalidId, function() {
            return true;
        });
    }

    /**
     * @dataProvider provideInvalidDelegatorArgument
     *
     * @param mixed $invalidDelegator
     */
    public function testConstructWithNotStringDelegatorArgumentWillThrowInvalidArgumentException($invalidDelegator)
    {
        $this->expectException(InvalidArgumentException::class);

        new DelegatorExtension(uniqid('id', true), $invalidDelegator);
    }

    public function testInvokeWillCallDelegatorWithGivenIdAndCallableServiceFactory()
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();

        \call_user_func($this->getExtension(), $container);
    }

    /**
     * @return DelegatorExtension
     */
    protected function getExtension()
    {
        return new DelegatorExtension($this->id, $this->delegator);
    }
}

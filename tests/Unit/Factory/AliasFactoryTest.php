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

use CoiSA\ServiceProvider\Factory\AliasFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class AliasFactoryTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
final class AliasFactoryTest extends AbstractFactoryTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var string */
    private $service;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
        $this->service   = uniqid('test', true);
    }

    public function provideNonStringValues()
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
     * @dataProvider provideNonStringValues
     * @expectedException \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     *
     * @param mixed $service
     */
    public function testConstructWithNonStringArgumentWillThrowInvalidArgumentException($service)
    {
        new AliasFactory($service);
    }

    public function testInvokeWillReturnContainerGetService()
    {
        $object         = new \stdClass();
        $object->uniqid = uniqid('uniqid', true);

        $this->container->get($this->service)->shouldBeCalledOnce()->willReturn($object);

        self::assertSame($object, \call_user_func($this->getFactory(), $this->container->reveal()));
    }

    /**
     * @return AliasFactory
     */
    protected function getFactory()
    {
        return new AliasFactory($this->service);
    }
}

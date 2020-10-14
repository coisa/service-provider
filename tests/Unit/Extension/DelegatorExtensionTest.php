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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Extension\DelegatorExtension;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;

/**
 * Class DelegatorExtensionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class DelegatorExtensionTest extends AbstractExtensionTestCase
{
    /** @var string */
    private $id;

    /** @var callable */
    private $delegator;

    public function setUp()
    {
        $id = \uniqid('id', true);

        $this->id        = $id;
        $this->delegator = function(ContainerInterface $container, $name, $callable) use ($id) {
            Assert::assertEquals($id, $name);
            Assert::assertInstanceOf('CoiSA\\ServiceProvider\\Factory\\ServiceFactory', $callable);
        };
    }

    public function provideInvalidIdArgument()
    {
        return array(
            array(null),
            array(false),
            array(true),
            array(function() {
                return true;
            }),
            array(\mt_rand(1, 1000)),
        );
    }

    public function provideInvalidDelegatorArgument()
    {
        return array(
            array(null),
            array(false),
            array(true),
            array(\uniqid('test', true)),
            array(\mt_rand(1, 1000)),
        );
    }

    /**
     * @dataProvider provideInvalidIdArgument
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $invalidId
     */
    public function testConstructWithNotStringIdArgumentWillThrowInvalidArgumentException($invalidId)
    {
        new DelegatorExtension($invalidId, function() {
            return true;
        });
    }

    /**
     * @dataProvider provideInvalidDelegatorArgument
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $invalidDelegator
     */
    public function testConstructWithNotStringDelegatorArgumentWillThrowInvalidArgumentException($invalidDelegator)
    {
        new DelegatorExtension(\uniqid('id', true), $invalidDelegator);
    }

    public function testInvokeWillCallDelegatorWithGivenIdAndCallableServiceFactory()
    {
        $container = $this->prophesize('Psr\\Container\\ContainerInterface')->reveal();

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

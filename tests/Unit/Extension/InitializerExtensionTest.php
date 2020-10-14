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

use CoiSA\ServiceProvider\Extension\InitializerExtension;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class InitializerExtensionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class InitializerExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');

        $this->extension = new InitializerExtension(array($this, 'createInitializerCallable'));
    }

    public function createInitializerCallable()
    {
        return function (ContainerInterface $container, $object) {
            // noop
        };
    }

    /**
     * @expectedException \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     */
    public function testConstructWithNotCallableArgumentWillThrowInvalidArgumentException()
    {
        new InitializerExtension(\uniqid('test', true));
    }

    public function testInvokeWillCallInitializerCallableAndReturnPreviousInstance()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->prophesize('Psr\\Log\\LoggerInterface')->reveal();

        $initializerCallable = function (
            ContainerInterface $container,
            LoggerAwareInterface $object
        ) use ($logger) {
            $object->setLogger($logger);
        };

        $initializer = new InitializerExtension($initializerCallable);

        /** @var LoggerAwareInterface|ObjectProphecy $loggerAwareProphecy */
        $loggerAwareProphecy = $this->prophesize('Psr\\Log\\LoggerAwareInterface');

        /** @var LoggerAwareInterface $loggerAware */
        $loggerAware = $loggerAwareProphecy->reveal();

        $loggerAwareProphecy->setLogger($logger)->shouldBeCalledOnce();

        $previous = \call_user_func($initializer, $this->container->reveal(), $loggerAware);

        self::assertSame($loggerAware, $previous);
    }
}

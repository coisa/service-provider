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
use CoiSA\ServiceProvider\Extension\InitializerExtension;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class InitializerExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class InitializerExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function createInitializerCallable()
    {
        return function(ContainerInterface $container, $object) {
            // noop
        };
    }

    public function testConstructWithNotCallableArgumentWillThrowInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        new InitializerExtension(uniqid('test', true));
    }

    public function testInvokeWillCallInitializerCallableAndReturnPreviousInstance()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->prophesize(LoggerInterface::class)->reveal();

        $initializerCallable = function(
            ContainerInterface $container,
            LoggerAwareInterface $object
        ) use ($logger) {
            $object->setLogger($logger);
        };

        $initializer = new InitializerExtension($initializerCallable);

        /** @var LoggerAwareInterface|ObjectProphecy $loggerAwareProphecy */
        $loggerAwareProphecy = $this->prophesize(LoggerAwareInterface::class);

        /** @var LoggerAwareInterface $loggerAware */
        $loggerAware = $loggerAwareProphecy->reveal();

        $loggerAwareProphecy->setLogger($logger)->shouldBeCalledOnce();

        $previous = \call_user_func($initializer, $this->container->reveal(), $loggerAware);

        self::assertSame($loggerAware, $previous);
    }

    /**
     * @return InitializerExtension
     */
    protected function getExtension()
    {
        return new InitializerExtension([$this, 'createInitializerCallable']);
    }
}

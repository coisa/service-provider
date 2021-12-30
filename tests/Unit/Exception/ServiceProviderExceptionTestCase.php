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

namespace CoiSA\ServiceProvider\Test\Unit\Exception;

use CoiSA\Exception\ExceptionInterface;
use CoiSA\Exception\Throwable;
use CoiSA\ServiceProvider\Exception\ServiceProviderExceptionInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ServiceProviderExceptionTestCase.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Exception
 */
abstract class ServiceProviderExceptionTestCase extends TestCase
{
    public function testClassImplementsServiceProviderExceptionInterface()
    {
        self::assertInstanceOf(ServiceProviderExceptionInterface::class, $this->getException());
    }

    public function testClassImplementsExceptionInterface()
    {
        self::assertInstanceOf(ExceptionInterface::class, $this->getException());
    }

    public function testClassImplementsThrowableInterface()
    {
        self::assertInstanceOf(Throwable::class, $this->getException());
    }

    public function testClassImplementsThrowable()
    {
        self::assertInstanceOf(\Throwable::class, $this->getException());
    }

    /**
     * @return ServiceProviderExceptionInterface
     */
    abstract protected function getException();
}

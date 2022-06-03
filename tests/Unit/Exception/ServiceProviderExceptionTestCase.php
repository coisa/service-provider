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
    /**
     * @coversNothing
     */
    public function testClassImplementsServiceProviderExceptionInterface(): void
    {
        static::assertInstanceOf(ServiceProviderExceptionInterface::class, $this->getException());
    }

    /**
     * @coversNothing
     */
    public function testClassImplementsExceptionInterface(): void
    {
        static::assertInstanceOf(ExceptionInterface::class, $this->getException());
    }

    /**
     * @coversNothing
     */
    public function testClassImplementsThrowableInterface(): void
    {
        static::assertInstanceOf(Throwable::class, $this->getException());
    }

    /**
     * @coversNothing
     */
    public function testClassImplementsThrowable(): void
    {
        static::assertInstanceOf(\Throwable::class, $this->getException());
    }

    abstract protected function getException(): ServiceProviderExceptionInterface;
}

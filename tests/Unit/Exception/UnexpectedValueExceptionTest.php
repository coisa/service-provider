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

use CoiSA\ServiceProvider\Exception\UnexpectedValueException;

/**
 * Class UnexpectedValueExceptionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Exception
 *
 * @internal
 * @coversDefaultClass \CoiSA\ServiceProvider\Exception\UnexpectedValueException
 */
final class UnexpectedValueExceptionTest extends ServiceProviderExceptionTestCase
{
    /**
     * @covers ::forFactoryNotFound
     */
    public function testForFactoryNotFoundPreservesExceptionTypeAndContext(): void
    {
        $previous = new \RuntimeException('Previous failure');
        $exception = UnexpectedValueException::forFactoryNotFound('service.id', 42, $previous);

        static::assertInstanceOf(UnexpectedValueException::class, $exception);
        static::assertSame('Factory "service.id" was not found.', $exception->getMessage());
        static::assertSame(42, $exception->getCode());
        static::assertSame($previous, $exception->getPrevious());
    }

    /**
     * @covers ::forExtensionNotFound
     */
    public function testForExtensionNotFoundPreservesExceptionTypeAndContext(): void
    {
        $previous = new \RuntimeException('Previous failure');
        $exception = UnexpectedValueException::forExtensionNotFound('service.id', 42, $previous);

        static::assertInstanceOf(UnexpectedValueException::class, $exception);
        static::assertSame('Extension "service.id" was not found.', $exception->getMessage());
        static::assertSame(42, $exception->getCode());
        static::assertSame($previous, $exception->getPrevious());
    }

    protected function getException(): UnexpectedValueException
    {
        return new UnexpectedValueException();
    }
}

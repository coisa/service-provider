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

namespace CoiSA\ServiceProvider\Exception;

use CoiSA\Exception\Spl\InvalidArgumentException as CoiSAInvalidArgumentException;

/**
 * Class InvalidArgumentException.
 *
 * @package CoiSA\ServiceProvider\Exception
 */
final class InvalidArgumentException extends CoiSAInvalidArgumentException implements ServiceProviderExceptionInterface
{
    public static function forInvalidArgumentType(
        string $argumentName,
        string $expectedType,
        int $code = 0,
        \Throwable $previous = null
    ): CoiSAInvalidArgumentException {
        $message = sprintf(self::MESSAGE_INVALID_ARGUMENT_TYPE, $argumentName, $expectedType);

        return new self($message, $code, $previous);
    }
}

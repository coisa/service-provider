<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 *
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */
namespace CoiSA\ServiceProvider\Test\Unit\Exception;

use CoiSA\ServiceProvider\Exception\UnexpectedValueException;

/**
 * Class UnexpectedValueExceptionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Exception
 */
final class UnexpectedValueExceptionTest extends ServiceProviderExceptionTestCase
{
    protected function getException()
    {
        return new UnexpectedValueException();
    }
}

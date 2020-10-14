<?php

namespace CoiSA\ServiceProvider\Test\Unit\Exception;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;

/**
 * Class InvalidArgumentExceptionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Exception
 */
final class InvalidArgumentExceptionTest extends ServiceProviderExceptionTestCase
{
    protected function getException()
    {
        return new InvalidArgumentException();
    }
}

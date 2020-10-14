<?php

namespace CoiSA\ServiceProvider\Test\Unit\Exception;

use CoiSA\ServiceProvider\Exception\ReflectionException;

/**
 * Class ReflectionExceptionTest
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Exception
 */
final class ReflectionExceptionTest extends ServiceProviderExceptionTestCase
{
    protected function getException()
    {
        return new ReflectionException();
    }
}

<?php

namespace CoiSA\ServiceProvider\Test\Unit\Exception;

use CoiSA\ServiceProvider\Exception\ServiceProviderExceptionInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ServiceProviderExceptionTestCase
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Exception
 */
abstract class ServiceProviderExceptionTestCase extends TestCase
{
    /**
     * @return ServiceProviderExceptionInterface
     */
    abstract protected function getException();

    public function testClassImplementsServiceProviderExceptionInterface()
    {
        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Exception\\ServiceProviderExceptionInterface',
            $this->getException()
        );
    }

    public function testClassImplementsExceptionInterface()
    {
        self::assertInstanceOf('CoiSA\\Exception\\ExceptionInterface', $this->getException());
    }

    public function testClassImplementsThrowableInterface()
    {
        self::assertInstanceOf('CoiSA\\Exception\\Throwable', $this->getException());
    }

    public function testClassImplementsThrowable()
    {
        self::assertInstanceOf('Throwable', $this->getException());
    }
}

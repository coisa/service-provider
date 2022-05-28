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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Extension\AbstractExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class AbstractExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
abstract class AbstractExtensionTestCase extends TestCase
{
    use ProphecyTrait;

    public function testExtensionImplementsExtensionInterface(): void
    {
        static::assertInstanceOf(ServiceProviderExtensionInterface::class, $this->getExtension());
    }

    public function testExtensionExtendAbstractExtension(): void
    {
        static::assertInstanceOf(AbstractExtension::class, $this->getExtension());
    }

    /**
     * @return ServiceProviderExtensionInterface
     */
    abstract protected function getExtension();
}

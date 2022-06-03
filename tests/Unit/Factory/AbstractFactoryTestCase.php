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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Factory\AbstractFactory;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class AbstractFactoryTestCase.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
abstract class AbstractFactoryTestCase extends TestCase
{
    use ProphecyTrait;

    /**
     * @coversNothing
     */
    public function testFactoryImplementsFactoryInterface(): void
    {
        static::assertInstanceOf(ServiceProviderFactoryInterface::class, $this->getFactory());
    }

    /**
     * @coversNothing
     */
    public function testFactoryExtendAbstractFactory(): void
    {
        static::assertInstanceOf(AbstractFactory::class, $this->getFactory());
    }

    abstract protected function getFactory(): ServiceProviderFactoryInterface;
}

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

namespace CoiSA\ServiceProvider\Test\Unit\Factory;

use CoiSA\ServiceProvider\Factory\AbstractFactory;
use CoiSA\ServiceProvider\Factory\FactoryInterface;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractFactoryTestCase.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Factory
 */
abstract class AbstractFactoryTestCase extends TestCase
{
    public function testFactoryImplementsFactoryInterface()
    {
        self::assertInstanceOf(ServiceProviderFactoryInterface::class, $this->getFactory());
    }

    public function testFactoryExtendAbstractFactory()
    {
        self::assertInstanceOf(AbstractFactory::class, $this->getFactory());
    }

    /**
     * @return FactoryInterface
     */
    abstract protected function getFactory();
}

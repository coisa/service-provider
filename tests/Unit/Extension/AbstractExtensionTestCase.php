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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
abstract class AbstractExtensionTestCase extends TestCase
{
    public function testExtensionImplementsExtensionInterface()
    {
        self::assertInstanceOf(
            'CoiSA\\ServiceProvider\\Extension\\ServiceProviderExtensionInterface',
            $this->getExtension()
        );
    }

    public function testExtensionExtendAbstractExtension()
    {
        self::assertInstanceOf('CoiSA\\ServiceProvider\\Extension\\AbstractExtension', $this->getExtension());
    }

    /**
     * @return ServiceProviderExtensionInterface
     */
    abstract protected function getExtension();
}

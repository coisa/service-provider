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

namespace CoiSA\ServiceProvider\Test\Unit;

use CoiSA\ServiceProvider\ServiceProvider;

/**
 * Class ServiceProviderTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit
 *
 * @internal
 * @coversNothing
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * @return ServiceProvider
     */
    protected function createServiceProvider()
    {
        return new ServiceProvider();
    }
}

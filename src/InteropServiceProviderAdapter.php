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
namespace CoiSA\ServiceProvider;

use Interop\Container\ServiceProviderInterface as InteropServiceProvider;

/**
 * Class InteropServiceProviderAdapter.
 *
 * @package CoiSA\ServiceProvider
 */
final class InteropServiceProviderAdapter extends ServiceProvider
{
    /**
     * InteropServiceProviderAdapter constructor.
     *
     * @param InteropServiceProvider $serviceProvider
     */
    public function __construct(InteropServiceProvider $serviceProvider)
    {
        $this->factories  = $serviceProvider->getFactories();
        $this->extensions = $serviceProvider->getExtensions();
    }
}

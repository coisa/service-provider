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
namespace CoiSA\ServiceProvider\Adapter;

use CoiSA\ServiceProvider\LaminasConfigServiceProvider;

/**
 * Class AbstractLaminasConfigProviderAdapter.
 *
 * @package CoiSA\ServiceProvider\Adapter
 */
abstract class AbstractLaminasConfigProviderAdapter extends AbstractLazyLoadServiceProviderAdapter
{
    /**
     * @return callable Laminas ConfigProvider instance.
     */
    abstract protected function getConfigProvider();

    /**
     * {@inheritDoc}
     */
    protected function getLazyLoadServiceProvider()
    {
        $configProvider = $this->getConfigProvider();

        return new LaminasConfigServiceProvider($configProvider());
    }
}
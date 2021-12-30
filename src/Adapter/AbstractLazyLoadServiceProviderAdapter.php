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

use CoiSA\ServiceProvider\ServiceProvider;
use Interop\Container\ServiceProviderInterface;

/**
 * Class AbstractLazyLoadServiceProviderAdapter.
 *
 * @package CoiSA\ServiceProvider\Adapter
 */
abstract class AbstractLazyLoadServiceProviderAdapter extends ServiceProvider
{
    /**
     * @var ServiceProviderInterface
     */
    private $serviceProvider;

    /**
     * Initialize the adapter.
     */
    private function lazyLoadServiceProvider()
    {
        if ($this->serviceProvider) {
            return;
        }

        $this->serviceProvider = $this->getLazyLoadServiceProvider();

        $this->factories  = array_merge(
            $this->factories,
            $this->serviceProvider->getFactories()
        );

        $this->extensions = array_merge_recursive(
            $this->extensions,
            $this->serviceProvider->getExtensions()
        );
    }

    /**
     * @return {@inheritdoc}
     */
    public function getFactories()
    {
        $this->lazyLoadServiceProvider();

        return parent::getFactories();
    }

    /**
     * @return {@inheritdoc}
     */
    public function getExtensions()
    {
        $this->lazyLoadServiceProvider();

        return parent::getExtensions();
    }

    /**
     * @return ServiceProviderInterface
     */
    abstract protected function getLazyLoadServiceProvider();
}

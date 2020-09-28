<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\ServiceProvider;

use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ExtensionInterface;
use CoiSA\ServiceProvider\Factory\FactoryInterface;
use Interop\Container\ServiceProviderInterface;

/**
 * Class ServiceProviderAggregator
 *
 * @package CoiSA\ServiceProvider
 */
final class ServiceProviderAggregator implements ServiceProviderInterface
{
    /**
     * @var ServiceProviderInterface[]
     */
    private $serviceProviders = array();

    /**
     * @var FactoryInterface[]
     */
    private $factories = array();

    /**
     * @var ExtensionInterface[]
     */
    private $extensions = array();

    /**
     * ServiceProviderAggregator constructor.
     *
     * @param ServiceProviderInterface[] $serviceProviders
     */
    public function __construct(array $serviceProviders = array())
    {
        foreach ($serviceProviders as $serviceProvider) {
            $this->append($serviceProvider);
        }
    }

    /**
     * @param ServiceProviderInterface $serviceProvider
     *
     * @return self
     */
    public function prepend(ServiceProviderInterface $serviceProvider)
    {
        \array_unshift($this->serviceProviders, $serviceProvider);
        $this->resetCache();

        return $this;
    }

    /**
     * @param ServiceProviderInterface $serviceProvider
     *
     * @return self
     */
    public function append(ServiceProviderInterface $serviceProvider)
    {
        $this->serviceProviders[] = $serviceProvider;
        $this->resetCache();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFactories()
    {
        if (empty($this->factories)) {
            $this->factories = $this->resolveFactories();
        }

        return $this->factories;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions()
    {
        if (empty($this->extensions)) {
            $this->extensions = $this->resolveExtensions();
        }

        return $this->extensions;
    }

    /**
     * @return FactoryInterface[]
     */
    private function resolveFactories()
    {
        $factories = \array_map(
            function ($serviceProvider) {
                return $serviceProvider->getFactories();
            },
            $this->serviceProviders
        );

        return \call_user_func_array(
            'array_merge',
            \array_reverse($factories)
        );
    }

    /**
     * @return ExtensionInterface[]
     */
    private function resolveExtensions()
    {
        $serviceProvidersExtensions = \array_map(
            function ($serviceProvider) {
                return $serviceProvider->getExtensions();
            },
            $this->serviceProviders
        );

        $mergedExtensions = \call_user_func_array(
            '\array_merge_recursive',
            \array_reverse($serviceProvidersExtensions)
        );

        foreach ($mergedExtensions as $key => $closure) {
            if (\is_callable($closure)) {
                continue;
            }

            $mergedExtensions[$key] = \current($closure);

            while ($extension = \next($closure)) {
                $mergedExtensions[$key] = new ExtendExtension($mergedExtensions[$key], $extension);
            }
        }

        return $mergedExtensions;
    }

    /**
     * Reset resolved factories & extensions.
     */
    private function resetCache()
    {
        unset($this->factories, $this->extensions);
    }
}

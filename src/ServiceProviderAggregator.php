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

use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use Interop\Container\ServiceProviderInterface as InteropServiceProvider;

/**
 * Class ServiceProviderAggregator
 *
 * @package CoiSA\ServiceProvider
 */
final class ServiceProviderAggregator extends ServiceProvider implements \IteratorAggregate
{
    /**
     * @var InteropServiceProvider[]
     */
    private $serviceProviders = array();

    /**
     * ServiceProviderAggregator constructor.
     *
     * @param InteropServiceProvider[] $serviceProviders
     */
    public function __construct(array $serviceProviders = array())
    {
        foreach ($serviceProviders as $serviceProvider) {
            $this->append($serviceProvider);
        }
    }

    /**
     * @return InteropServiceProvider[]
     */
    public function getServiceProviders()
    {
        return $this->serviceProviders;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->serviceProviders);
    }

    /**
     * @param InteropServiceProvider $serviceProvider
     *
     * @return self
     */
    public function prepend(InteropServiceProvider $serviceProvider)
    {
        \array_unshift($this->serviceProviders, $serviceProvider);

        $this->setFactories($serviceProvider->getFactories(), false);
        $this->setExtensions($serviceProvider->getExtensions(), true);

        return $this;
    }

    /**
     * @param InteropServiceProvider $serviceProvider
     *
     * @return self
     */
    public function append(InteropServiceProvider $serviceProvider)
    {
        $this->serviceProviders[] = $serviceProvider;

        $this->setFactories($serviceProvider->getFactories(), true);
        $this->setExtensions($serviceProvider->getExtensions(), false);

        return $this;
    }

    /**
     * @param array $factories
     * @param bool  $overwrite
     */
    private function setFactories(array $factories, $overwrite = false)
    {
        foreach ($factories as $id => $factory) {
            if (false === $overwrite && \array_key_exists($id, $this->factories)) {
                continue;
            }

            $this->setFactory($id, $factory);
        }
    }

    /**
     * @param callable[]|ServiceProviderExtensionInterface[] $extensions
     * @param bool                            $prepend
     */
    private function setExtensions(array $extensions, $prepend = false)
    {
        foreach ($extensions as $id => $extension) {
            $this->extend($id, $extension, $prepend);
        }
    }
}

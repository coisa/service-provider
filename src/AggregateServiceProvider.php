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
 * Class AggregateServiceProvider.
 *
 * @package CoiSA\ServiceProvider
 */
final class AggregateServiceProvider extends ServiceProvider implements \IteratorAggregate
{
    /**
     * @var InteropServiceProvider[]
     */
    private $serviceProviders = array();

    /**
     * AggregateServiceProvider constructor.
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactories()
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getFactories();
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory($id)
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getFactory($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getExtensions();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension($id)
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getExtension($id);
    }

    /**
     * @return ServiceProvider
     */
    private function resolveServiceProvider()
    {
        $serviceProvider  = new ServiceProvider();

        list($factories, $extensions) = \array_reduce(
            $this->serviceProviders,
            function($carry, $serviceProvider) {
                return array(
                    \array_merge($carry[0], $serviceProvider->getFactories()),
                    \array_merge($carry[1], $serviceProvider->getExtensions()),
                );
            },
            array(array(), array())
        );

        $factories  = \array_merge($factories, $this->factories);
        $extensions = \array_merge($extensions, $this->extensions);

        foreach ($factories as $id => $factory) {
            $serviceProvider->setFactory($id, $factory);
        }

        foreach ($extensions as $id => $extension) {
            $serviceProvider->extend($id, $extension);
        }

        return $serviceProvider;
    }
}

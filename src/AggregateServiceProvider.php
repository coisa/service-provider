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

namespace CoiSA\ServiceProvider;

use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;
use Interop\Container\ServiceProviderInterface as InteropServiceProvider;

/**
 * Class AggregateServiceProvider.
 *
 * @package CoiSA\ServiceProvider
 */
class AggregateServiceProvider extends ServiceProvider implements \IteratorAggregate
{
    /**
     * @var InteropServiceProvider[]
     */
    private $serviceProviders = [];

    /**
     * AggregateServiceProvider constructor.
     *
     * @param InteropServiceProvider[] $serviceProviders
     */
    public function __construct(array $serviceProviders = [])
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

    public function prepend(InteropServiceProvider $serviceProvider): self
    {
        array_unshift($this->serviceProviders, $serviceProvider);

        return $this;
    }

    public function append(InteropServiceProvider $serviceProvider): self
    {
        $this->serviceProviders[] = $serviceProvider;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactories(): array
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getFactories();
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory(string $id): ?ServiceProviderFactoryInterface
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getFactory($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions(): array
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getExtensions();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension(string $id): ?ServiceProviderExtensionInterface
    {
        $serviceProvider = $this->resolveServiceProvider();

        return $serviceProvider->getExtension($id);
    }

    private function resolveServiceProvider(): ServiceProvider
    {
        $serviceProvider  = new ServiceProvider();

        [$factories, $extensions] = array_reduce(
            $this->serviceProviders,
            function ($carry, $serviceProvider) {
                return [
                    array_merge($carry[0], $serviceProvider->getFactories()),
                    array_merge($carry[1], $serviceProvider->getExtensions()),
                ];
            },
            [[], []]
        );

        $factories  = array_merge($factories, $this->factories);
        $extensions = array_merge($extensions, $this->extensions);

        foreach ($factories as $id => $factory) {
            $serviceProvider->setFactory($id, $factory);
        }

        foreach ($extensions as $id => $extension) {
            $serviceProvider->extend($id, $extension);
        }

        return $serviceProvider;
    }
}

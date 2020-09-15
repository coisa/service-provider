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

use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

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

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFactories()
    {
        $factories = \array_map(function ($serviceProvider) {
            return $serviceProvider->getFactories();
        }, $this->serviceProviders);

        return \call_user_func_array(
            'array_merge',
            \array_reverse($factories)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions()
    {
        $extensions = \array_map(function ($serviceProvider) {
            return $serviceProvider->getExtensions();
        }, $this->serviceProviders);

        $merged = \call_user_func_array(
            '\array_merge_recursive',
            \array_reverse($extensions)
        );

        foreach ($merged as $key => $closure) {
            if (\is_callable($closure)) {
                continue;
            }

            $merged[$key] = \array_reduce($closure, function ($extension, callable $current) {
                if (!$extension) {
                    return $current;
                }

                return function (ContainerInterface $container, $previous = null) use ($current, $extension) {
                    return \call_user_func($extension, $container, $current($container, $previous));
                };
            });
        }

        return $merged;
    }
}

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

/**
 * Class LaminasConfigServiceProvider
 *
 * @package CoiSA\LaminasConfigServiceProvider
 */
final class LaminasConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @var callable[]
     */
    private $factories = array();

    /**
     * @var callable[]
     */
    private $extensions = array();

    /**
     * LaminasConfigServiceProvider constructor.
     *
     * @param mixed[] $config
     */
    public function __construct(array $config)
    {
        $config['dependecies']['config'] = $config;

        foreach ($this->getDependencies($config, 'services') as $id => $service) {
            $this->factories[$id] = new Factory\ServiceFactory($service);
        }

        foreach ($this->getDependencies($config, 'factories') as $id => $factory) {
            $this->factories[$id] = new Factory\FactoryFactory($factory);
        }

        foreach ($this->getDependencies($config, 'invokables') as $id => $invokable) {
            $this->factories[$id] = new Factory\InvokableFactory($invokable);
        }

        foreach ($this->getDependencies($config, 'delegators') as $id => $delegators) {
            foreach ($delegators as $delegator) {
                $this->extensions[$id] = new Extension\DelegatorExtension($id, $delegator);
            }
        }

        foreach ($this->factories as $id => $factory) {
            foreach ($this->getDependencies($config, 'initializers') as $initializer) {
                $this->extensions[$id] = new Extension\InitializerExtension($initializer);
            }
        }

        foreach ($this->getDependencies($config, 'aliases') as $id => $alias) {
            $this->factories[$id] = new Factory\AliasFactory($alias);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getFactories()
    {
        return $this->factories;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param mixed[] $config
     * @param string  $type
     *
     * @return array
     */
    private function getDependencies($config, $type)
    {
        if (false === \array_key_exists($type, $config['dependencies'])) {
            return array();
        }

        return $config['dependencies'][$type];
    }
}

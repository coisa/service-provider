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

/**
 * Class LaminasConfigServiceProvider
 *
 * @package CoiSA\ServiceProvider
 */
final class LaminasConfigServiceProvider extends ServiceProvider
{
    /**
     * LaminasConfigServiceProvider constructor.
     *
     * @param mixed[] $config
     *
     * @throws Exception\ReflectionException
     */
    public function __construct(array $config)
    {
        $dependencies = \array_merge_recursive(array(
            'services'     => array(),
            'factories'    => array(),
            'invokables'   => array(),
            'delegators'   => array(),
            'initializers' => array(),
            'aliases'      => array(),
        ), $config['dependencies'] ?: array());

        $this->factories['config']  = new Factory\ServiceFactory($config);
        $this->extensions['config'] = new Extension\MergeConfigExtension($config);

        foreach ($dependencies['services'] as $id => $service) {
            $this->factories[$id] = new Factory\ServiceFactory($service);
        }

        foreach ($dependencies['factories'] as $id => $factory) {
            $this->factories[$id] = new Factory\FactoryFactory($factory);
        }

        foreach ($dependencies['invokables'] as $id => $invokable) {
            $this->factories[$id] = new Factory\InvokableFactory($invokable);
        }

        foreach ($dependencies['delegators'] as $id => $delegators) {
            foreach ($delegators as $delegator) {
                $this->extensions[$id] = new Extension\DelegatorExtension($id, $delegator);
            }
        }

        foreach ($this->factories as $id => $factory) {
            foreach ($dependencies['initializers'] as $initializer) {
                $this->extensions[$id] = new Extension\InitializerExtension($initializer);
            }
        }

        foreach ($dependencies['aliases'] as $id => $alias) {
            $this->factories[$id] = new Factory\AliasFactory($alias);
        }
    }
}

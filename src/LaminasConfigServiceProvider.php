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

/**
 * Class LaminasConfigServiceProvider.
 *
 * @package CoiSA\ServiceProvider
 *
 * @TODO Add "abstract_factories" support
 */
class LaminasConfigServiceProvider extends ServiceProvider
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
        $dependencies = array_merge_recursive(
            [
                'services'     => [],
                'factories'    => [],
                'invokables'   => [],
                'delegators'   => [],
                'initializers' => [],
                'aliases'      => [],
            ],
            \array_key_exists('dependencies', $config) ? $config['dependencies'] : []
        );

        $this->setFactory('config', new Factory\ServiceFactory($config));
        $this->extend('config', new Extension\MergeConfigExtension($config));

        foreach ($dependencies['services'] as $id => $service) {
            $this->setFactory($id, new Factory\ServiceFactory($service));
        }

        foreach ($dependencies['factories'] as $id => $factory) {
            $this->setFactory($id, $factory);
        }

        foreach ($dependencies['invokables'] as $id => $invokable) {
            $this->setFactory($id, new Factory\InvokableFactory($invokable));
        }

        foreach ($dependencies['delegators'] as $id => $delegators) {
            foreach ($delegators as $delegator) {
                $this->extend($id, new Extension\DelegatorExtension($id, $delegator));
            }
        }

        foreach ($this->factories as $id => $factory) {
            foreach ($dependencies['initializers'] as $initializer) {
                $this->extend($id, new Extension\InitializerExtension($initializer));
            }
        }

        foreach ($dependencies['aliases'] as $id => $alias) {
            $this->setFactory($id, new Factory\AliasFactory($alias));
        }
    }
}

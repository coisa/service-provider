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

use CoiSA\ServiceProvider\Extension\CallableExtension;
use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\AliasFactory;
use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;

/**
 * Class ServiceProvider
 *
 * @package CoiSA\ServiceProvider
 */
class ServiceProvider extends AbstractServiceProvider
{
    /**
     * @param string                                          $id
     * @param callable|ServiceProviderFactoryInterface|string $factory
     */
    public function setFactory($id, $factory)
    {
        if (!$factory instanceof ServiceProviderFactoryInterface) {
            $factory = new FactoryFactory($factory);
        }

        $this->factories[$id] = $factory;
    }

    /**
     * @param string $id
     *
     * @return ServiceProviderFactoryInterface
     */
    public function getFactory($id)
    {
        return $this->factories[$id];
    }

    /**
     * @param string $alias
     * @param string $id
     */
    public function setAlias($alias, $id)
    {
        $this->factories[$alias] = new AliasFactory($id);
    }

    /**
     * @param string                                     $id
     * @param callable|ServiceProviderExtensionInterface $extension
     * @param bool                                       $prepend
     */
    public function extend($id, $extension, $prepend = false)
    {
        if (!$extension instanceof ServiceProviderExtensionInterface) {
            $extension = new CallableExtension($extension);
        }

        if (\array_key_exists($id, $this->extensions)) {
            $current = $this->getExtension($id);

            $next      = false === $prepend ? $extension : $current;
            $extension = false === $prepend ? $current : $extension;

            $extension = new ExtendExtension($extension, $next);
        }

        $this->extensions[$id] = $extension;
    }

    /**
     * @param string $id
     *
     * @return ServiceProviderExtensionInterface
     */
    public function getExtension($id)
    {
        return $this->extensions[$id];
    }
}

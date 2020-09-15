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

use CoiSA\ServiceProvider\Factory\FactoryFactory;
use Interop\Container\ServiceProviderInterface;

/**
 * Class DynamicServiceProvider
 *
 * @package CoiSA\ServiceProvider
 */
final class DynamicServiceProvider implements ServiceProviderInterface
{
    /**
     * @var callable[]
     */
    private $factories;

    /**
     * @var callable[]
     */
    private $extensions;

    /**
     * DynamicServiceProvider constructor.
     *
     * @param array $factories
     * @param array $extensions
     */
    public function __construct(array $factories = array(), array $extensions = array())
    {
        $this->factories  = $factories;
        $this->extensions = $extensions;
    }

    /**
     * @param string          $id
     * @param callable|string $factory
     */
    public function addFactory($id, $factory)
    {
        $this->factories[$id] = new FactoryFactory($factory);
    }

    /**
     * @param string   $id
     * @param callable $extension
     */
    public function addExtension($id, $extension)
    {
        $this->extensions[$id] = $extension;
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
}

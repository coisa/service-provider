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

use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;

/**
 * Class AbstractServiceProvider.
 *
 * @package CoiSA\ServiceProvider
 */
abstract class AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * @var callable[]|ServiceProviderFactoryInterface[]
     */
    protected $factories = array();

    /**
     * @var callable[]|ServiceProviderExtensionInterface[]
     */
    protected $extensions = array();

    /**
     * {@inheritdoc}
     */
    public function getFactories()
    {
        return $this->factories;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}

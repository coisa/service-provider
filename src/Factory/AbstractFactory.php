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

namespace CoiSA\ServiceProvider\Factory;

use Psr\Container\ContainerInterface;

/**
 * Class AbstractFactory
 *
 * @package CoiSA\LaminasConfigServiceProvider\Factory
 */
abstract class AbstractFactory
{
    /**
     * @var callable
     */
    protected $factory;

    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container)
    {
        $factory = $this->factory;

        return $factory($container);
    }
}

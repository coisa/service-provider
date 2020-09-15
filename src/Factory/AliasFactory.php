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
 * Class ServiceFactory
 *
 * @package CoiSA\LaminasConfigServiceProvider\Factory
 */
final class AliasFactory extends AbstractFactory
{
    /**
     * AliasFactory constructor.
     *
     * @param string $service
     */
    public function __construct($service)
    {
        $this->factory = function (ContainerInterface $container) use ($service) {
            return $container->get($service);
        };
    }
}

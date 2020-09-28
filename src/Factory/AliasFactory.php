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

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceFactory
 *
 * @package CoiSA\ServiceProvider\Factory
 */
final class AliasFactory extends AbstractFactory
{
    /**
     * AliasFactory constructor.
     *
     * @param string $service
     *
     * @throws InvalidArgumentException
     */
    public function __construct($service)
    {
        if (false === \is_string($service)) {
            throw InvalidArgumentException::forInvalidArgumentType('service', 'string');
        }

        $this->factory = function (ContainerInterface $container) use ($service) {
            return $container->get($service);
        };
    }
}

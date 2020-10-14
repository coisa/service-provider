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

use CoiSA\ServiceProvider\Exception\ServiceProviderInvalidArgumentException;
use CoiSA\ServiceProvider\Exception\ServiceProviderReflectionException;
use Psr\Container\ContainerInterface;

/**
 * Class InvokableFactory
 *
 * @package CoiSA\ServiceProvider\Factory
 */
final class InvokableFactory extends AbstractFactory
{
    /**
     * InvokableFactory constructor.
     *
     * @param string $invokable
     *
     * @throws ServiceProviderReflectionException when given class was not found
     */
    public function __construct($invokable)
    {
        if (false === \is_string($invokable)) {
            throw ServiceProviderInvalidArgumentException::forInvalidArgumentType('invokable', 'string');
        }

        if (false === \class_exists($invokable)) {
            throw ServiceProviderReflectionException::forClassNotFound($invokable);
        }

        $this->factory = function (ContainerInterface $container) use ($invokable) {
            return new $invokable();
        };
    }
}

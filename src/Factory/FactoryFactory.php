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

use CoiSA\ServiceProvider\Exception\ReflectionException;

/**
 * Class FactoryFactory
 *
 * @package CoiSA\ServiceProvider\Factory
 */
final class FactoryFactory extends AbstractFactory
{
    /**
     * FactoryFactory constructor.
     *
     * @param callable|string $factory
     *
     * @throws ReflectionException
     */
    public function __construct($factory)
    {
        if (\is_string($factory) && false === \class_exists($factory)) {
            throw ReflectionException::forClassNotFound($factory);
        }

        if (\is_string($factory)) {
            $factory = new $factory();
        }

        $this->factory = new CallableFactory($factory);
    }
}

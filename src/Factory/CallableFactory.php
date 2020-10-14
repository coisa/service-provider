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

/**
 * Class CallableFactory
 *
 * @package CoiSA\ServiceProvider\Factory
 */
final class CallableFactory extends AbstractFactory
{
    /**
     * CallableFactory constructor.
     *
     * @param callable $factory
     *
     * @throws ServiceProviderInvalidArgumentException
     */
    public function __construct($factory)
    {
        if (false === \is_callable($factory)) {
            throw ServiceProviderInvalidArgumentException::forInvalidArgumentType('factory', 'callable');
        }

        $this->factory = $factory;
    }
}

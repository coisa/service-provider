<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 * @copyright Copyright (c) 2020-2021 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\ServiceProvider\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Class InitializerExtension.
 *
 * @package CoiSA\ServiceProvider\Extension
 */
final class InitializerExtension extends AbstractExtension
{
    /**
     * InitializerExtension constructor.
     *
     * @param callable $initializer
     *
     * @throws InvalidArgumentException
     */
    public function __construct($initializer)
    {
        if (false === \is_callable($initializer)) {
            throw InvalidArgumentException::forInvalidArgumentType('initializer', 'callable');
        }

        $this->extension = function(ContainerInterface $container, $previous = null) use ($initializer) {
            \call_user_func($initializer, $container, $previous);

            return $previous;
        };
    }
}

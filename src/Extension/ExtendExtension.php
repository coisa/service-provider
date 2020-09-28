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

namespace CoiSA\ServiceProvider\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Class ExtendExtension
 *
 * @package CoiSA\ServiceProvider\Extension
 */
final class ExtendExtension extends AbstractExtension
{
    /**
     * ExtendExtension constructor.
     *
     * @param callable|ExtensionInterface $extension
     * @param callable|ExtensionInterface $extend
     *
     * @throws InvalidArgumentException
     */
    public function __construct($extension, $extend)
    {
        if (false === \is_callable($extension) && !$extension instanceof ExtensionInterface) {
            throw InvalidArgumentException::forInvalidArgumentType(
                'extension',
                'callable|ExtensionInterface'
            );
        }

        if (false === \is_callable($extend) && !$extend instanceof ExtensionInterface) {
            throw InvalidArgumentException::forInvalidArgumentType(
                'extend',
                'callable|ExtensionInterface'
            );
        }

        $this->extension = function (ContainerInterface $container, $previous = null) use ($extension, $extend) {
            return $extend(
                $container,
                $extension($container, $previous)
            );
        };
    }
}

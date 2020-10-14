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
 * Class MergeConfigExtension
 *
 * @package CoiSA\ServiceProvider\Extension
 */
final class MergeConfigExtension extends AbstractExtension
{
    /**
     * MergeConfigExtension constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $self            = $this;
        $this->extension = function (ContainerInterface $container, $previous = null) use ($config, $self) {
            $previous = null === $previous ? array() : $previous;

            if (false === \is_array($previous)) {
                throw InvalidArgumentException::forInvalidArgumentType('previous', 'array');
            }

            return $self->merge($previous, $config);
        };
    }

    /**
     * @param array $previous
     * @param array $merge
     *
     * @return array
     *
     * @internal
     */
    public function merge(array $previous, array $config)
    {
        foreach ($config as $key => $value) {
            if (false === \array_key_exists($key, $previous)) {
                $previous[$key] = $value;

                continue;
            }

            if (\is_int($key)) {
                $previous[] = $value;

                continue;
            }

            if (\is_array($value) && \is_array($previous[$key])) {
                $previous[$key] = $this->merge($previous[$key], $value);

                continue;
            }

            $previous[$key] = $value;
        }

        return $previous;
    }
}

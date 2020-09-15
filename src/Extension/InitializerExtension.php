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

use Psr\Container\ContainerInterface;

/**
 * Class InitializerExtension
 *
 * @package CoiSA\LaminasConfigServiceProvider\Extension
 */
final class InitializerExtension extends AbstractExtension
{
    /**
     * InitializerExtension constructor.
     *
     * @param callable $initializer
     */
    public function __construct($initializer)
    {
        $this->extension = function (ContainerInterface $container, $previous = null) use ($initializer) {
            $initializer($container, $previous);

            return $previous;
        };
    }
}

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
 * Class ExtendExtension
 *
 * @package CoiSA\ServiceProvider\Extension
 */
final class ExtendExtension extends AbstractExtension
{
    /**
     * ExtendExtension constructor.
     *
     * @param ServiceProviderExtensionInterface $extension
     * @param ServiceProviderExtensionInterface $next
     */
    public function __construct(ServiceProviderExtensionInterface $extension, ServiceProviderExtensionInterface $next)
    {
        $this->extension = function (ContainerInterface $container, $previous = null) use ($extension, $next) {
            return $next(
                $container,
                $extension($container, $previous)
            );
        };
    }
}

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

use CoiSA\ServiceProvider\Factory\ServiceFactory;
use Psr\Container\ContainerInterface;

/**
 * Class InitializerExtension
 *
 * @package CoiSA\LaminasConfigServiceProvider\Extension
 */
final class DelegatorExtension extends AbstractExtension
{
    /**
     * InitializerExtension constructor.
     *
     * @param string   $id
     * @param callable $delegator
     */
    public function __construct($id, $delegator)
    {
        $this->extension = function (ContainerInterface $container, $previous = null) use ($id, $delegator) {
            return $delegator(
                $container,
                $id,
                new ServiceFactory($previous)
            );
        };
    }
}

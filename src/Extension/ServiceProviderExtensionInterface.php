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

use Psr\Container\ContainerInterface;

/**
 * Interface ServiceProviderExtensionInterface.
 *
 * @package CoiSA\ServiceProvider\Extension
 */
interface ServiceProviderExtensionInterface
{
    /**
     * @param ContainerInterface $container
     * @param null|mixed         $previous
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $previous = null);
}

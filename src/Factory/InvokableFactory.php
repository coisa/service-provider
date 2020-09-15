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

/**
 * Class InvokableFactory
 *
 * @package CoiSA\LaminasConfigServiceProvider\Factory
 */
final class InvokableFactory extends AbstractFactory
{
    /**
     * InvokableFactory constructor.
     *
     * @param string $invokable
     */
    public function __construct($invokable)
    {
        $this->factory = new $invokable();
    }
}

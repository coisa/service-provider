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

/**
 * Class AbstractExtension
 *
 * @package CoiSA\LaminasConfigServiceProvider\Factory
 */
abstract class AbstractExtension
{
    /**
     * @var callable
     */
    protected $extension;

    /**
     * @return callable
     */
    public function __invoke()
    {
        return $this->extension;
    }
}

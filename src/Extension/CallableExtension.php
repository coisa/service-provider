<?php

declare(strict_types=1);

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 * @copyright Copyright (c) 2020-2022 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\ServiceProvider\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;

/**
 * Class CallableExtension.
 *
 * @package CoiSA\ServiceProvider\Extension
 */
final class CallableExtension extends AbstractExtension
{
    /**
     * CallableExtension constructor.
     *
     * @param callable $extension
     *
     * @throws InvalidArgumentException
     */
    public function __construct($extension)
    {
        if (false === \is_callable($extension)) {
            throw InvalidArgumentException::forInvalidArgumentType('extension', 'callable');
        }

        $this->extension = $extension;
    }
}

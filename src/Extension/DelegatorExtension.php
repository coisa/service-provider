<?php

/**
 * This file is part of coisa/service-provider.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/service-provider
 *
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */
namespace CoiSA\ServiceProvider\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Factory\ServiceFactory;
use Psr\Container\ContainerInterface;

/**
 * Class DelegatorExtension.
 *
 * @package CoiSA\ServiceProvider\Extension
 */
final class DelegatorExtension extends AbstractExtension
{
    /**
     * DelegatorExtension constructor.
     *
     * @param string   $id
     * @param callable $delegator
     *
     * @throws InvalidArgumentException
     */
    public function __construct($id, $delegator)
    {
        if (false === \is_string($id)) {
            throw InvalidArgumentException::forInvalidArgumentType('id', 'string');
        }

        if (false === \is_callable($delegator)) {
            throw InvalidArgumentException::forInvalidArgumentType('delegator', 'callable');
        }

        $this->extension = function(ContainerInterface $container, $previous = null) use ($id, $delegator) {
            $previousFactory = new ServiceFactory($previous);

            return \call_user_func($delegator, $container, $id, $previousFactory);
        };
    }
}

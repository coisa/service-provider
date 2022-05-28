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

namespace CoiSA\ServiceProvider;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Exception\ReflectionException;
use CoiSA\ServiceProvider\Exception\UnexpectedValueException;
use CoiSA\ServiceProvider\Extension\CallableExtension;
use CoiSA\ServiceProvider\Extension\ExtendExtension;
use CoiSA\ServiceProvider\Extension\ServiceProviderExtensionInterface;
use CoiSA\ServiceProvider\Factory\AliasFactory;
use CoiSA\ServiceProvider\Factory\FactoryFactory;
use CoiSA\ServiceProvider\Factory\ServiceProviderFactoryInterface;

/**
 * Class ServiceProvider.
 *
 * @package CoiSA\ServiceProvider
 */
class ServiceProvider extends AbstractServiceProvider
{
    /**
     * @param callable|ServiceProviderFactoryInterface|string $factory
     *
     * @throws ReflectionException      When the string factory class name does not exist
     * @throws InvalidArgumentException When the factory is not callable
     */
    public function setFactory(string $id, $factory): void
    {
        if (!$factory instanceof ServiceProviderFactoryInterface) {
            $factory = new FactoryFactory($factory);
        }

        $this->factories[$id] = $factory;
    }

    /**
     * @throws UnexpectedValueException Not found factory
     */
    public function getFactory(string $id): ?ServiceProviderFactoryInterface
    {
        if (false === \array_key_exists($id, $this->factories)) {
            throw UnexpectedValueException::forFactoryNotFound($id);
        }

        return $this->factories[$id];
    }

    public function setAlias(string $alias, string $id): void
    {
        $this->factories[$alias] = new AliasFactory($id);
    }

    /**
     * @param callable|ServiceProviderExtensionInterface $extension
     *
     * @throws InvalidArgumentException When the extension is not callable
     */
    public function extend(string $id, $extension, bool $prepend = false): void
    {
        if (!$extension instanceof ServiceProviderExtensionInterface) {
            $extension = new CallableExtension($extension);
        }

        if (\array_key_exists($id, $this->extensions)) {
            $current = $this->getExtension($id);

            $next      = false === $prepend ? $extension : $current;
            $extension = false === $prepend ? $current : $extension;

            $extension = new ExtendExtension($extension, $next);
        }

        $this->extensions[$id] = $extension;
    }

    /**
     * @throws UnexpectedValueException Not found extension
     */
    public function getExtension(string $id): ?ServiceProviderExtensionInterface
    {
        if (false === \array_key_exists($id, $this->extensions)) {
            throw UnexpectedValueException::forExtensionNotFound($id);
        }

        return $this->extensions[$id];
    }
}

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

namespace CoiSA\ServiceProvider\Adapter;

use Interop\Container\ServiceProviderInterface;
use Interop\Container\ServiceProviderInterface as InteropServiceProvider;

/**
 * Class InteropServiceProviderAdapter.
 *
 * @package CoiSA\ServiceProvider\Adapter
 */
final class InteropServiceProviderAdapter extends AbstractLazyLoadServiceProviderAdapter
{
    /**
     * @var InteropServiceProvider
     */
    private $serviceProvider;

    /**
     * InteropServiceProviderAdapter constructor.
     */
    public function __construct(InteropServiceProvider $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLazyLoadServiceProvider(): ServiceProviderInterface
    {
        return $this->serviceProvider;
    }
}

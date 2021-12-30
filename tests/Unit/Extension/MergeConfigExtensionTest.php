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

namespace CoiSA\ServiceProvider\Test\Unit\Extension;

use CoiSA\ServiceProvider\Exception\InvalidArgumentException;
use CoiSA\ServiceProvider\Extension\MergeConfigExtension;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class MergeConfigExtensionTest.
 *
 * @package CoiSA\ServiceProvider\Test\Unit\Extension
 */
final class MergeConfigExtensionTest extends AbstractExtensionTestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function provideNotArrayValues()
    {
        return [
            [true],
            [false],
            [uniqid('test', true)],
            [mt_rand(1, 100)],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provideNotArrayValues
     *
     * @param mixed $previous
     */
    public function testInvokeWithNotArrayPreviousWillReturnInvalidArgumentException($previous)
    {
        $this->expectException(InvalidArgumentException::class);

        \call_user_func($this->getExtension(), $this->container->reveal(), $previous);
    }

    public function providePreviousConfigExpectedValue()
    {
        return [
            'merge-integer-and-string-keys' => [
                [
                    'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => [
                        'a',
                        1 => 'b',
                        'c',
                    ],
                ],
                [
                    'baz',
                    4 => [
                        'd' => 'd',
                    ],
                ],
                [
                    0     => 'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => [
                        'a',
                        1 => 'b',
                        'c',
                    ],
                    5     => 'baz',
                    6     => [
                        'd' => 'd',
                    ],
                ],
            ],
            'merge-arrays-recursively' => [
                [
                    'foo' => [
                        'baz',
                    ],
                ],
                [
                    'foo' => [
                        'baz',
                    ],
                ],
                [
                    'foo' => [
                        0 => 'baz',
                        1 => 'baz',
                    ],
                ],
            ],
            'replace-string-keys' => [
                [
                    'foo' => 'bar',
                    'bar' => [],
                ],
                [
                    'foo' => 'baz',
                    'bar' => 'bat',
                ],
                [
                    'foo' => 'baz',
                    'bar' => 'bat',
                ],
            ],
            'merge-with-null' => [
                [
                    'foo' => null,
                    null  => 'rod',
                    'cat' => 'bar',
                    'god' => 'rad',
                ],
                [
                    'foo' => 'baz',
                    null  => 'zad',
                    'god' => null,
                    'dad' => 'bad',
                ],
                [
                    'foo' => 'baz',
                    null  => 'zad',
                    'cat' => 'bar',
                    'god' => null,
                    'dad' => 'bad',
                ],
            ],
        ];
    }

    /**
     * @dataProvider providePreviousConfigExpectedValue
     *
     * @param array $previous
     * @param array $config
     * @param array $expected
     */
    public function testInvokeWillMergeConfigToPrevious(array $previous, array $config, array $expected)
    {
        $extension = new MergeConfigExtension($config);

        self::assertEquals($expected, $extension($this->container->reveal(), $previous));
    }

    /**
     * @return MergeConfigExtension
     */
    protected function getExtension()
    {
        return new MergeConfigExtension([]);
    }
}

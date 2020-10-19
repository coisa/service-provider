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
namespace CoiSA\ServiceProvider\Test\Unit\Extension;

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

    public function setUp()
    {
        $this->container = $this->prophesize('Psr\\Container\\ContainerInterface');
    }

    public function provideNotArrayValues()
    {
        return array(
            array(true),
            array(false),
            array(\uniqid('test', true)),
            array(\mt_rand(1, 100)),
            array(new \stdClass()),
        );
    }

    /**
     * @dataProvider provideNotArrayValues
     * @expectedException \CoiSA\ServiceProvider\Exception\InvalidArgumentException
     *
     * @param mixed $previous
     */
    public function testInvokeWithNotArrayPreviousWillReturnInvalidArgumentException($previous)
    {
        \call_user_func($this->getExtension(), $this->container->reveal(), $previous);
    }

    public function providePreviousConfigExpectedValue()
    {
        return array(
            'merge-integer-and-string-keys' => array(
                array(
                    'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => array(
                        'a',
                        1 => 'b',
                        'c',
                    ),
                ),
                array(
                    'baz',
                    4 => array(
                        'd' => 'd',
                    ),
                ),
                array(
                    0     => 'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => array(
                        'a',
                        1 => 'b',
                        'c',
                    ),
                    5     => 'baz',
                    6     => array(
                        'd' => 'd',
                    ),
                ),
            ),
            'merge-arrays-recursively' => array(
                array(
                    'foo' => array(
                        'baz',
                    ),
                ),
                array(
                    'foo' => array(
                        'baz',
                    ),
                ),
                array(
                    'foo' => array(
                        0 => 'baz',
                        1 => 'baz',
                    ),
                ),
            ),
            'replace-string-keys' => array(
                array(
                    'foo' => 'bar',
                    'bar' => array(),
                ),
                array(
                    'foo' => 'baz',
                    'bar' => 'bat',
                ),
                array(
                    'foo' => 'baz',
                    'bar' => 'bat',
                ),
            ),
            'merge-with-null' => array(
                array(
                    'foo' => null,
                    null  => 'rod',
                    'cat' => 'bar',
                    'god' => 'rad',
                ),
                array(
                    'foo' => 'baz',
                    null  => 'zad',
                    'god' => null,
                    'dad' => 'bad',
                ),
                array(
                    'foo' => 'baz',
                    null  => 'zad',
                    'cat' => 'bar',
                    'god' => null,
                    'dad' => 'bad',
                ),
            ),
        );
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
        return new MergeConfigExtension(array());
    }
}

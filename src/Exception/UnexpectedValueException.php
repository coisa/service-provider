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

namespace CoiSA\ServiceProvider\Exception;

/**
 * Class UnexpectedValueException.
 *
 * @package CoiSA\ServiceProvider\Exception
 */
final class UnexpectedValueException extends \CoiSA\Exception\Spl\UnexpectedValueException implements
    ServiceProviderExceptionInterface
{
    /** @const string */
    const MESSAGE_FACTORY_NOT_FOUND = 'Factory "%s" was not found.';

    /** @const string */
    const MESSAGE_EXTENSION_NOT_FOUND = 'Extension "%s" was not found.';

    /**
     * @param string          $id
     * @param int             $code
     * @param null|\Exception $previous
     *
     * @return UnexpectedValueException
     */
    public static function forFactoryNotFound($id, $code = 0, $previous = null)
    {
        $message = sprintf(
            self::MESSAGE_FACTORY_NOT_FOUND,
            $id
        );

        return self::create($message, $code, $previous);
    }

    /**
     * @param string          $id
     * @param int             $code
     * @param null|\Exception $previous
     *
     * @return UnexpectedValueException
     */
    public static function forExtensionNotFound($id, $code = 0, $previous = null)
    {
        $message = sprintf(
            self::MESSAGE_EXTENSION_NOT_FOUND,
            $id
        );

        return self::create($message, $code, $previous);
    }
}

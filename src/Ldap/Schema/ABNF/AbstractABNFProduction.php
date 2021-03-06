<?php
/**
 * This file is part of php-ldap.
 *
 * php-ldap is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * php-ldap is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with php-ldap.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @license GNU General Public License, version 3
 * @author Eric Spreen
 * @copyright 2014 Eric Spreen
 */

namespace Ldap\Schema\ABNF;

use Ldap\Exception\Schema\ABNFException;

/**
 * Abstract helper class whose implemententations check conformance to
 * an ABNF production.
 *
 * @package Ldap\Schema\ABNF
 */
abstract class AbstractABNFProduction
{
    /**
     * Message for not supplying a string
     */
    const MSG_NOT_STRING = "The given value cannot conform to the ABNF production '%s' because it is not a string.";

    /**
     * Default message for producing exceptions.
     */
    const MSG_DEFAULT = "The assertion for production '%s' failed.";

    /**
     * Name of the ABNF production.
     *
     * @var string
     */
    static protected $name;
    
    /**
     * Gives the name of the ABNF rule.
     *
     * @return string
     */
    static public function name()
    {
        return static::$name;
    }
    
    /**
     * Assert that `$value` conforms to the ABNF production in
     * question.
     *
     * This method should be implemented.
     *
     * @param string $value
     * @throws ABNFException If the assertion fails
     */
    abstract static protected function _assert($value);

    /**
     * Assert that `$value` conforms to the ABNF production in
     * question.
     *
     * This method proxies to the specific {@link self::_assert()}
     * method to do the actual checking.
     *
     * @param string $value
     * @throws ABNFException If the assertion fails or the value is
     * not a string.
     */
    final static public function assert($value)
    {
        self::assertString($value);
        static::_assert($value);
    }
    
    /**
     * Assert that `$value` is indeed a string.
     *
     * @param string $value
     * @throws ABNFException If the assertion fails
     */
    final static public function assertString($value)
    {
        if (!is_string($value))
            throw new ABNFException(sprintf(self::MSG_NOT_STRING, static::name()));
    }

    /**
     * Produces an appropriate exception.
     *
     * @param string $msg Custom message with one %s placeholder for the production name. 
     * @return ABNFException
     */
    static public function getException($msg = null)
    {
        if (empty($msg))
            $msg = static::MSG_DEFAULT;

        $msg = sprintf($msg, static::name());
        return new ABNFException(static::name(), $msg);
    }
    
}

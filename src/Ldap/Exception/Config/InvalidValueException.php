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

namespace Ldap\Exception\Config;

use Ldap\Exception\Exception;

/**
 * Exception for when an invalid configuration value is given.
 *
 * @package Ldap\Config
 */
class InvalidValueException
extends InvalidConfigException
{
    /**
     * Configuration object that raised the exception.
     * @var object
     */
    public $config;

    /**
     * Create a new exception.
     *
     * @param string $key
     * @param mixed $value The invalid value that was provided
     * @param string $expectedType The type that was expected
     * @param object $config The configuration object that raised the exception
     */
    public function __construct($key, $value, $expectedType, $config)
    {
        $valString = is_object($value) ? get_class($value) : (string) $value;
        $message = sprintf("Invalid value for configuration key '%s' provided.  A %s was expected.  Given: (%s) %s.", $key, $expectedType, gettype($value), $valString);
        
        parent::__construct($message);

        $this->config = $config;
    }
}

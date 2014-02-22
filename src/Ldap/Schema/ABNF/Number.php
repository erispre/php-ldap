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
 * Check conformance to the RFC4512 number production.
 *
 * @package Ldap\Schema\ABNF
 */
class Number
extends AbstractABNFProduction
{
    static protected $name = 'number';

    static protected function _assert($value)
    {
        if (!preg_match('/^(?:[0-9]|[1-9][0-9]+)$/', $value))
            throw self::getException();
    }
}
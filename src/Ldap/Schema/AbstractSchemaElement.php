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

namespace Ldap\Schema;

use Ldap\Exception\Schema\SchemaSyntaxException;

/**
 * Abstract class with code common to all schema elements.
 *
 * This comes down (as per RFC4512) to the OID and a description.  Yet
 * this class also provides means to check for the most common ABNF
 * productions defined in RFC4512, such as keystring, oid, kind, etc.
 *
 * @package Ldap\Schema;
 * @see http://www.rfc-editor.org/rfc/rfc4512.txt RFC4512
 */
abstract class AbstractSchemaElement
{
    /**
     * Object Identifier
     *
     * Encoded as a numericoid (RFC4512).
     * @var string
     */
    protected $oid;

    /**
     * Description
     *
     * Encoded as a UTF-8 string.  Note that the quotes from a
     * qdstring are removed and quotes and parentheses are not escaped
     * in this representation, as opposed to in a dstring.
     *
     * @var string
     */
    protected $description;

    /**
     * Set the OID.
     *
     * Protected, since this should be done by a constructor or other
     * internal methods.  Once set, it should never change.
     *
     * @param string $oid
     * @return $this
     */
    protected function setOid($oid)
    {
        $this->assertNumericOid($oid);

        $this->oid = $oid;
        return $this;
    }

    /**
     * Get the OID.
     *
     * @return string Guaranteed to be in numericoid format.
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set the description of this element.
     *
     * Note, the argument should be a regular UTF-8 string.  Quotes
     * and backslashes should **NOT** be escaped.
     *
     * @param string $description UTF-8 encoded description.
     * @return $this
     * @todo Enforce UTF-8 encoding
     * @throws SchemaSyntaxException If the argument is not a valid string.
     */
    public function setDescription($description)
    {
        if (!is_string($description)) {
            $msg = sprintf("A description should be a UTF-8 encoded string; %s given.", gettype($description));
            throw new SchemaSyntaxException($msg);
        }

        $this->description = $description;
        return $this;
    }

    /**
     * Get the description of the element.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Assert that the parameter is a numericoid.
     *
     * @param string $oid
     * @throws SchemaSyntaxException If the assertion fails
     */
    public function assertNumericOid($oid)
    {
        if (!is_string($oid)) {
            $msg = sprintf("A numericoid must be a string; %s given.", gettype($oid));
            throw new SchemaSyntaxException($msg);
        }

        $numbers = explode('.', $oid);
        foreach ($numbers as $number) {
            try {
                $this->assertNumber($number);
            } catch (SchemaSyntaxException $e) {
                $msg = sprintf("The string '%s' does not match a numericoid. %s", $oid, $e->getMessage());
                throw new SchemaSyntaxException($msg);
            }
        }
    }

    /**
     * Assert that the parameter is a number as per RFC4512.
     *
     * @param string $number
     * @throws SchemaSyntaxException If the subject is not a number string.
     */
    public function assertNumber($number)
    {
        if (!is_string($number)) {
            $msg = sprintf("A number must be a string; %s given.", gettype($number));
            throw new SchemaSyntaxException($msg);
        }
        
        if (!preg_match('/^[0-9]|[1-9][0-9]*$/', $number)) {
            $msg = sprintf("The string '%s' is not a number as per RFC4512.", $number);
            throw new SchemaSyntaxException($msg);
        }
    }

    /**
     * Assert that the parameter is a keystring as per RFC4512.
     *
     * @param string $keystring
     * @throws SchemaSyntaxException If the parameter is not a keystring.
     */
    public function assertKeyString($keystring)
    {
        if (!is_string($keystring)) {
            $msg = sprintf("A keystring must be a string; %s given.", gettype($keystring));
            throw new SchemaSyntaxException($msg);
        }
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9\\-]*$/', $keystring)) {
            $msg = sprintf("The string '%s' does not match the keystring production as per RFC4512.", $keystring);
            throw new SchemaSyntaxException($msg);
        }
    }

    /**
     * Assert that the parameter is a qdescrs as per RFC4512.
     *
     * @param string $qdescrs
     * @throws SchemaSyntaxException If the parameter is not a qdescrs string.
     */
    public function assertQdescrs($qdescrs)
    {
        if (!is_string($qdescrs)) {
            $msg = sprintf("A qdescrs production must be a string; %s given.", gettype($qdescrs));
            throw new SchemaSyntaxException($msg);
        }
        try {
            // Can be a qdescr, or...
            $this->assertQdescr($qdescr);
        } catch (SchemaSyntaxException $e) {
            // Or a qdescrlist surrounded by parentheses and whitespace.
            if (strpos('(', $qdescr) !== 0 || strpos(')', $qdescr, 1) !== strlen($qdescr) - 1) {
                $msg = sprintf("The string '%s' is not a qdescr and is not surrounded by parentheses.", $qdescr);
                throw new SchemaSyntaxException($msg);
            }
            $qdescrlist = trim(substr($qdescr, 1, -1), ' ');
            $this->assertQdescrlist($qdescrlist);
        }
    }

    /**
     * Assert that the parameter is a qdescr as per RFC4512.
     *
     * This comes down to asserting that it is a keystring with single quotes.
     *
     * @param string $qdescr
     * @throws SchemaSyntaxException If the parameter is not a qdescr string.
     */
    public function assertQdescr($qdescr)
    {
        if (!is_string($qdescr)) {
            $msg = sprintf("A qdescr should be a string; %s given.", gettype($qdescr));
            throw new SchemaSyntaxException($msg);
        }

        if (strpos($qdescr, "'") !== 0 || strpos($qdescr, "'", 1) !== strlen($qdescr) - 1) {
            $msg = "A qdescr should be surrounded by single quotes.";
            throw new SchemaSyntaxException($msg);
        }
        $this->assertKeyString(substr($qdescr, 1, -1));
    }

    /**
     * Assert that a string is a qdescrlist as per RFC4512.
     *
     * @param string $qdescrlist
     * @throws SchemaSyntaxException If $qdescrlist does not match the qdescrlist production.
     */
    public function assertQdescrlist($qdescrlist)
    {
        if (!is_string($qdescrlist)) {
            $msg = sprintf("A qdescrlist should be a string; %s given.", gettype($qdescrlist));
            throw new SchemaSyntaxException($msg);
        }

        $qdescrs = explode(' ', $qdescrlist);
        foreach ($qdescrs as $qdescr) {
            if (empty($qdesr)) continue;
            $this->assertQdescr($qdesr);
        }
    }

    /**
     * Convert a qdescrs string to an array of descriptors.
     *
     * @param string $qdescrs
     * @return string[]
     */
    public function qdescrs2descr($qdescrs)
    {
        $this->assertQdescrs($qdescrs);

        try {
            $this->assertQdescr($qdescrs);
            $return = array(substr($qdescr, 1,-1)); // Strip quotes
        } catch (SchemaSyntaxException $e) {
            // Not a qdescr.  Therefore it is a list.
            $list = trim(substr($qdescrs, 1, -1), ' ');
            $return = preg_split("@\\'? *\\'?@", $list);
        }
        return $return;
    }
}

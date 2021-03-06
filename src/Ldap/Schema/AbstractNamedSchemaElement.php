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

/**
 * An abstract schema element with short names (descriptors).
 *
 * @package Ldap\Schema
 */
abstract class AbstractNamedSchemaElement
extends AbstractSchemaElement
{
    /**
     * Short names (descriptors) of the element.
     *
     * @var string[]
     */
    protected $descriptors = array();

    /**
     * Add a descriptor to the list.
     *
     * @param string $descr
     * @return $this
     */
    public function addDescriptor($descr)
    {
        $this->assertKeystring($descr);

        if (!in_array($descr, $this->descriptors)) {
            $this->descriptors[] = strtolower($descr);
        }
        return $this;
    }

    /**
     * Remove a descriptor from the list.
     *
     * @param string $descr
     * @return $this
     */
    public function removeDescriptor($descr)
    {
        while(($key = array_search($descr, $this->descriptors)) !== false) {
            unset($this->descriptors[$key]);
        }
        return $this;
    }

    /**
     * Set the descriptor list.
     *
     * @param string[]|string $descriptors Array of valid descriptors or a string that adheres to the qdescrs production of RFC4512.
     * @return $this
     */
    public function setDescriptors($descriptors)
    {
        if (is_string($descriptors)) {
            $descriptors = $this->qdescrs2descr($descriptors);
        }
        if (is_array($descriptors)) {
            $this->descriptors = array();
            foreach ($descriptors as $descr) {
                $this->addDescriptor($descr);
            }
        } else {
            throw new SchemaSyntaxException("An array of descriptors or a qdescrs string should be given.");
        }
        return $this;
    }
}

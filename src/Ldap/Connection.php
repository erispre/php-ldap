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

namespace Ldap;

use Ldap\Exception\ConnectionException;
use Ldap\Config\ConnectionConfig;

/**
 * Represents an LDAP connection.
 *
 * @package Ldap
 */
class Connection
{
    /**
     * LDAP resource
     * @var resource
     */
    protected $resource;

    /**
     * Used configuration
     * @var ConnectionConfig
     */
    protected $config;

    /**
     * Construct a new connection
     *
     * @param string|null $hostname May be a LDAP URL for OpenLDAP 2.*
     * @param int $port Port to connect on
     */
    public function __construct(ConnectionConfig $config)
    {
        $this->config = $config;

        $resource = ldap_connect($config->getHostname(), $config->getPort());
        if (!$resource) {
            throw new ConnectionException("Could not establish a connection to host $hostname on port $port.", ConnectionException::CODE_INIT_FAIL);
        }
        
        $this->resource = $resource;

        if (!ldap_set_option($this->resource, LDAP_OPT_PROTOCOL_VERSION, 3)) {
            throw new ConnectionException("Unable to use LDAPv3.", ConnectionException::CODE_OPT_FAIL);
        }
        try {
            $this->config->getBind()->bind($this);
        } catch (Exception $e) {
            ldap_close($this->resource);
            $msg = sprintf("Error performing an initial bind: %s", $e->getMessage());
            throw new ConnectionExvception($msg, ConnectionException::CODE_BIND_FAIL, $e);
        }
    }

    /**
     * Get the raw connection resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}

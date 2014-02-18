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

use Ldap\Exception\Exception;

/**
 * The LDAP manager class.
 *
 * This class manages a set of connections and known schemas.  It
 * allows you to search the DIT and is therefore the class that should
 * be used for most applications.
 *
 * @package Ldap
 */
class Manager
{
    /**
     * Configuration object
     * @var Config\Config
     */
    protected $config;

    /**
     * Connections
     * @var Connection[]
     */
    protected $connections = array();

    /**
     * Mappings from root DNs to connection names
     * @var array
     */
    protected $rootMappings = array();

    /**
     * The compiled schema
     * @var Schema
     */
    protected $schema;

    /**
     * Construct a new manager.
     *
     * This will only make sure that we have enough information to
     * establish connections and initialize the schema.  It will
     * **not** actually create a connection.
     *
     * @param array|Config\Config $config
     */
    public function __construct($config)
    {
        if (is_array($config)) {
            $config = new Config\Config($config);
        }
        if (!($config instanceof Config\Config)) {
            throw new Exception("The configuration parameter should be a configuration array or an instance of Ldap\Config\Config.");
        }
        $this->config = $config;
    }

    /**
     * Retrieves a connection to a server.
     *
     * @param string $name Name of the server
     * @return Connection
     * @throws Exception If the server is not found
     */
    public function getConnection($name)
    {
        if (!is_string($name))
            throw new \InvalidArgumentException("The name parameter should be a string.");

        if (!array_key_exists($name, $this->connections)) {
            $config = $this->config->getConnection($name);
            $this->connections[$name] = new Connection($config);
            $this->rootMappings[$config->getRoot()] = $name;
        }
        return $this->connections[$name];
    }

}

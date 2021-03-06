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

namespace Ldap\Config;

use Ldap\Exception\Config\MissingConfigKeyException;
use Ldap\Exception\Config\InvalidValueException;

/**
 * Connection configuration class
 *
 * @package Ldap\Config
 */
class ConnectionConfig
{
    /**
     * Hostname to connect to
     * @var string
     */
    protected $hostname;

    /**
     * Port to connect to
     * @var int
     */
    protected $port = 389;

    /**
     * Root DN of the server
     * @var string
     */
    protected $root;

    /**
     * Initial bind configuration
     * @var BindConfig
     */
    protected $bind;

    /**
     * Create a new connection configuration.
     *
     * The array `$config` needs the following **required** keys:
     * - (string) `hostname`: The hostname of the server to connect to.  When using OpenLDAP, this may be a URL.
     * - (string) `root`: The DN that is considered to be the root of this server.
     *
     * The following keys are *optional*:
     * - (integer) `port`: The port to connect to (default 389).
     * - (array|BindConfig) `bind`: Initial bind configuration (default anonymous)
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach (array('hostname', 'root') as $key) {
            if (!array_key_exists($key, $config))
                throw new MissingConfigKeyException($key);
        }

        $this->setHostname($config['hostname']);
        $this->setRoot($config['root']);
        if (isset($config['port'])) $this->setPort($config['port']);
        $bind = isset($config['bind']) ? $config['bind'] : new BindConfig(array('dn' => null, 'password' => null));
        $this->setBind($bind);
    }

    /**
     * Set the hostname to connect to.
     *
     * @param string $hostname
     * @return $this
     */
    public function setHostname($hostname)
    {
        if (!is_string($hostname))
            throw new InvalidValueException('hostname', $hostname, 'string', $this);

        $this->hostname = $hostname;
        return $this;
    }

    /**
     * Get the hostname to connect to.
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set the port to connect to.
     *
     * @param int $port
     * @return $this
     */
    public function setPort($port)
    {
        if (!is_int($port) || $port <= 0)
            throw new InvalidValueException('port', $port, 'positive integer', $this);

        $this->port = $port;

        return $this;
    }

    /**
     * Get the port to connect to.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the root DN of this connection
     *
     * @param string $root
     * @return $this
     */
    public function setRoot($root)
    {
        if (!is_string($root))
            throw new InvalidValueException('root', $root, 'string', $this);

        $this->root = $root;
        return $this;
    }

    /**
     * Get the root DN of this connection
     *
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set the bind configuration
     *
     * @param array|BindConfig $bind
     * @return $this
     */
    public function setBind($bind)
    {
        if (is_array($bind)) {
            $bind = new BindConfig($bind);
        }
        if (!($bind instanceof BindConfig)) {
            throw new InvalidValueException('bind', $bind, 'array or \Ldap\Config\BindConfig', $this);
        }

        $this->bind = $bind;
        return $this;
    }

    /**
     * Get the bind configuration
     *
     * @return BindConfig
     */
    public function getBind()
    {
        return $this->bind;
    }
}

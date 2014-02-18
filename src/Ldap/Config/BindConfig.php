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

use Ldap\Connection;
use Ldap\Exception\Exception;
use Ldap\Exception\Config\MissingConfigKeyException;
use Ldap\Exception\Config\InvalidValueException;

/**
 * Configuration for binding to a server
 *
 * @package Ldap\Config
 */
class BindConfig
{
    /**
     * DN to bind to
     * @var string
     */
    protected $dn;

    /**
     * Password to use
     * @var string
     */
    protected $password;

    /**
     * Construct a new bind configuration
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach (array('dn', 'password') as $key) {
            if (!array_key_exists($key, $config))
                throw new MissingConfigKeyException($key);
        }
        $this->setDn($config['dn']);
        $this->setPassword($config['password']);
    }

    /**
     * Bind a connection
     *
     * @param Connection $connection
     * @return $this
     * @throws Exception If the bind failed
     */
    public function bind(Connection $connection)
    {
        $res = $connection->getResource();
        $success = @ldap_bind($res, $this->dn, $this->password);
        if (!$success) {
            $error = sprintf("Could not bind to DN '%s': %s (%d).", $this->dn, ldap_error($res), ldap_errno($res));
            throw new Exception($error);
        }
        return $this;
    }

    /**
     * Set the DN to bind to.
     *
     * @param null|string $dn
     * @return $this
     */
    public function setDn($dn)
    {
        if ($dn !== null && !is_string($dn))
            throw new InvalidValueException('dn', $dn, 'string', $this);

        $this->dn = $dn;
    }

    /**
     * Get the DN to bind to
     *
     * @return string
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * Set the password to bind with.
     *
     * @param null|string $password
     * @return $this
     */
    public function setPassword($password)
    {
        if ($password !== null && !is_string($password))
            throw new InvalidValueException('password', $password, 'string', $this);
        
        $this->password = $password;
        return $this;
    }

    /**
     * Get the password to bind with
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
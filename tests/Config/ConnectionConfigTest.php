<?php

use Ldap\Config\ConnectionConfig;

class ConnectionConfigTest
extends PHPUnit_Framework_TestCase
{
    public function configProvider()
    {
        return array(
            array(array(
                'hostname' => 'babylon.fmf.nl',
                'root' => 'dc=fmf,dc=nl',
            )),
            array(array(
                'hostname' => 'babylon.fmf.nl',
                'root' => 'dc=fmf,dc=nl',
                'bind' => array(
                    'dn' => 'cn=admin,dc=fmf,dc=nl',
                    'password' => 'blaat',
                ),
            )),
            array(array(
                'hostname' => 'example.com',
                'root' => 'dc=example,dc=com',
                'port' => 1,
            )),
        );
    }
    
    /**
     * @dataProvider configProvider
     */
    public function testConstruct($data)
    {
        $config = new ConnectionConfig($data);

        $this->assertEquals($data['hostname'], $config->getHostname());
        $this->assertEquals($data['root'], $config->getRoot());
        $this->assertEquals(array_key_exists('port', $data) ? $data['port'] : 389, $config->getPort());

        return $config;
    }

    public function missingKeyProvider()
    {
        return array(
            array(array(
                'hostname' => 'babylon.fmf.nl',
                // Ommitting root
            )),
            array(array(
                // Ommitting hostname
                'root' => 'dc=example,dc=com',
            )),
            array(array(
                // Ommitting root and hostname
            )),
        );
    }

    /**
     * @dataProvider missingKeyProvider
     * @expectedException Ldap\Exception\Config\MissingConfigKeyException
     */
    public function testMissingKeyExceptions($data)
    {
        $config = new ConnectionConfig($data);
    }

    public function invalidValueProvider()
    {
        return array(
            array(array(
                'hostname' => 'example.com',
                'root' => 1,
            )),
            array(array(
                'hostname' => 1,
                'root' => 'dc=example,dc=com',
            )),
            array(array(
                'hostname' => 'example.com',
                'root' => 'dc=example,dc=com',
                'port' => '389',
            )),
            array(array(
                'hostname' => 'example.com',
                'root' => 'dc=example,dc=com',
                'bind' => 'notAnArray',
            )),
        );
    }

    /**
     * @dataProvider invalidValueProvider
     * @expectedException Ldap\Exception\Config\InvalidValueException
     */
    public function testInvalidValueExceptions($data)
    {
        $config = new ConnectionConfig($data);
    }
}
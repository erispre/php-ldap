<?php

use Ldap\Config\BindConfig;

class BindConfigTest
extends PHPUnit_Framework_TestCase
{
    public function configProvider()
    {
        return array(
            array(array(
                'dn' => 'cn=user,dc=example,dc=com',
                'password' => 'foobar',
            )),
        );
    }

    /**
     * @dataProvider configProvider
     */
    public function testConstruct($data)
    {
        $config = new BindConfig($data);

        $this->assertEquals($data['dn'], $config->getDn());
        $this->assertEquals($data['password'], $config->getPassword());
    }

    public function missingKeyProvider()
    {
        // Remove one key from each valid data set
        $data = array();
        foreach ($this->configProvider() as $validData) {
            foreach (array_keys($validData[0]) as $key) {
                $dataSet = $validData[0];
                unset($dataSet[$key]);
                $data[] = array($dataSet);
            }
        }
        return $data;
    }

    /**
     * @dataProvider missingKeyProvider
     * @expectedException Ldap\Exception\Config\MissingConfigKeyException
     */
    public function testMissingKeyExceptions($data)
    {
        $config = new BindConfig($data);
    }

    public function invalidValueProvider()
    {
        return array(
            array(array(
                'dn' => 1,
                'password' => 'foobar',
            )),
            array(array(
                'dn' => 'cn=user,dc=example,dc=com',
                'password' => 1,
            )),
            array(array(
                'dn' => 1,
                'password' => true,
            )),
        );
    }

    /**
     * @dataProvider invalidValueProvider
     * @expectedException Ldap\Exception\Config\InvalidValueException
     */
    public function testInvalidValueExceptions($data)
    {
        $config = new BindConfig($data);
    }
}
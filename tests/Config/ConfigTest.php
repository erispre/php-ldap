<?php

use Ldap\Config\Config;

class ConfigTest
extends PHPUnit_Framework_TestCase
{
    public function configProvider()
    {
        return array(
            array(array(
                'schemas' => array( /* ... */ ),
                'connections' => array( /* ... */ ),
            )),
        );
    }

    /**
     * @dataProvider configProvider
     */
    public function testConstruct($data)
    {
        $config = new Config($data);
    }
}
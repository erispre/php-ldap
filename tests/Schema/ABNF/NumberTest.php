<?php

use Ldap\Schema\ABNF\Number;
use Ldap\Exception\Schema\ABNFException;

class NumberTest
extends PHPUnit_Framework_TestCase
{
    public function testSucceed()
    {
        Number::assert('10');
        Number::assert('11');
        Number::assert('1');
    }

    public function faultyProvider()
    {
        return array(
            array('01'),
            array('a'),
            array('01a'),
            array(' '),
            array(' 10'),
            array('10 '),
            array(10),
            array(null),
            array(''),
        );
    }

    /**
     * @dataProvider faultyProvider
     * @expectedException Ldap\Exception\Schema\ABNFException
     */
    public function testFail($data)
    {
        Number::assert($data);
    }
}

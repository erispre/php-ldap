<?php

use Ldap\Schema\ABNF\Numericoid;
use Ldap\Exception\Schema\ABNFException;

class NumericoidTest
extends PHPUnit_Framework_TestCase
{
    public function succeedProvider()
    {
        return array(
            array('1'),
            array('1.2.3.4.5'),
        );
    }

    /**
     * @dataProvider succeedProvider
     */
    public function testSucceed($data)
    {
        try {
            Numericoid::assert($data);
        } catch (ABNFException $e) {
            $this->fail('An ABNFException was thrown: '.$e->getMessage());
        }
    }

    public function failProvider()
    {
        return array(
            array('.'),
            array('alpha'),
            array('1.a'),
            array('a.1'),
        );
    }

    /**
     * @dataProvider failProvider
     * @expectedException Ldap\Exception\Schema\ABNFException
     */
    public function testFail($data)
    {
        Numericoid::assert($data);
    }
}

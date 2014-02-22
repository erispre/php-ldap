<?php

use Ldap\Schema\ABNF\Qdescrlist;
use Ldap\Exception\Schema\ABNFException;

class QdescrlistTest
extends PHPUnit_Framework_TestCase
{
    public function successProvider()
    {
        return array(
            array("'one'"),
            array("'one' 'two-2'"),
            array("'one'    'two-2'"),
            array("'one-2-three'"),
            array("'one' 'two'  'three'"),
        );
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($data)
    {
        try {
            Qdescrlist::assert($data);
        } catch (ABNFException $e) {
            $this->fail("An ABNFException was thrown: ".$e->getMessage());
        }
    }
    
    public function failProvider()
    {
        return array(
            array('"wrong-quotes"'),
            array("'invalid descr'"),
            array("no quotes"),
            array("no-quotes"),
            array("'quotes' missing"),
            array("  'started-with-space'"),
        );
    }

    /**
     * @dataProvider failProvider
     * @expectedException Ldap\Exception\Schema\ABNFException
     */
    public function testFail($data)
    {
        Qdescrlist::assert($data);
    }

}
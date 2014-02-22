<?php

use Ldap\Schema\ABNF\Qdescr;
use Ldap\Exception\Schema\ABNFException;

class QdescrTest
extends PHPUnit_Framework_TestCase
{
    public function successProvider()
    {
        return array(
            array("'descr'"),
            array("'a123'"),
            array("'a-a123-1-123ab'"),
        );
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($data)
    {
        Qdescr::assert($data);
    }

    public function failProvider()
    {
        return array(
            array("descr"),
            array("'123'"),
            array("'-a'"),
            array("'a$'"),
            array("''"),
        );
    }

    /**
     * @dataProvider failProvider
     * @expectedException Ldap\Exception\Schema\ABNFException
     */
    public function testFail($data)
    {
        Qdescr::assert($data);
    }
}
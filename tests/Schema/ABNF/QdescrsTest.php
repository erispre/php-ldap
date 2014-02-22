<?php

use Ldap\Schema\ABNF\Qdescrs;
use Ldap\Exception\Schema\ABNFException;

class QdescrsTest
extends PHPUnit_Framework_TestCase
{
    public function successProvider()
    {
        return array(
            array("'qdescr'"),
            array("'qdescr-2-abc123'"),
            array("('qdescr' 'qdescr-2'  'qdescr-3')"),
            array("('single-qdescr')"),
        );
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($data)
    {
        try {
            Qdescrs::assert($data);
        } catch (ABNFException $e) {
            $this->fail("An ABNFException was thrown: ".$e->getMessage());
        }
    }

    public function failProvider()
    {
        return array(
            array("not-quoted"),
            array("'-invalid-qdescr'"),
            array("(unqouted list)"),
            array("('unquoted' item)"),
            array("'qdescr' ('qdescr' 'list')"),
        );
    }

    /**
     * @dataProvider failProvider
     * @expectedException Ldap\Exception\Schema\ABNFException
     */
    public function testFail($data)
    {
        Qdescrs::assert($data);
    }
}
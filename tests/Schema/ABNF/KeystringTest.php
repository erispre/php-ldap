<?php

use Ldap\Schema\ABNF\Keystring;
use Ldap\Exception\Schema\ABNFException;

class KeystringTest
extends PHPUnit_Framework_TestCase
{
    public function succeedProvider()
    {
        return array(
            array('alpha'),
            array('ALPHA'),
            array('mIxEdcASe'),
            array('a123-1-123ab1'),
            array('a-'),
        );
    }

    /**
     * @dataProvider succeedProvider
     */
    public function testSucceed($data)
    {
        try {
            Keystring::assert($data);
        } catch (ABNFException $e) {
            $this->fail('An ABNFException was thrown: '.$e->getMessage());
        }
    }

    public function failProvider()
    {
        return array(
            array('1'),
            array('1a'),
            array('1-a'),
            array('with whitespace'),
            array(' whsp'),
            array('whsp '),
            array('-a'),
            array('$a'),
        );
    }

    /**
     * @dataProvider failProvider
     * @expectedException Ldap\Exception\Schema\ABNFException
     */
    public function testFail($data)
    {
        Keystring::assert($data);
    }
}

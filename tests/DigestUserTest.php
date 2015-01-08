<?php

use Intervention\Httpauth\DigestUser;

class DigestUserTest extends PHPUnit_Framework_Testcase
{
    public function testDigestUserCreation()
    {
        $user = new DigestUser;
        $this->assertInstanceOf('\Intervention\Httpauth\DigestUser', $user);
    }

    /**
     * Test ivValid when the request has not yet provided Digest headers
     */
    public function testIsValidWhenNoDigestPayload()
    {
    	$user = new DigestUser;
    	$result = $user->isValid(null, null, null, function($username){
    		if(!$username)
    			return;
    		return md5(sprintf('%s:%s:%s', $username, 'My Realm', 's3cr3t'));
    	});
    	$this->assertFalse($result);
    }

    public function testIsValidWhenCallbackReturnsValidHash()
    {
    	$ha1 = md5("Mufasa:My Realm:s3cr3t");
    	$ha2 = md5("GET:/dir/index");
    	$nonce = 'dcd98b7102dd2f0e8b11d0f600bfb0c093';
    	$nc = '00000001';
    	$cnonce = '0a4f113b';
    	$qop = 'auth';
    	$response = md5(sprintf('%s:%s:%s:%s:%s:%s', $ha1, $nonce, $nc, $cnonce, $qop, $ha2));
    	$_SERVER['PHP_AUTH_DIGEST'] = 'username="Mufasa",
                      realm="My Realm",
                      nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093",
                      uri="/dir/index",
                      qop=auth,
                      nc=00000001,
                      cnonce="0a4f113b",
                      response="' . $response . '",
                      opaque="5ccc069c403ebaf9f0171e9517f40e41"';
    	$user = new DigestUser;
    	$result = $user->isValid(null, null, null, function($username){
    		if(!$username)
    			return;
    		return md5(sprintf('%s:%s:%s', $username, 'My Realm', 's3cr3t'));
    	});
    	$this->assertTrue($result);
    }
}

<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Base testcase class
 *
 * @author TrungHieu
 */
class TestCase extends \PHPUnit_Framework_TestCase
{

	/**
	 * The user with proper privilege
	 *
	 * @var \HieuLe\WordpressXmlrpcClient\WordpressClient
	 */
	protected static $client;

	/**
	 * The user without proper privilege
	 * 
	 * @var \HieuLe\WordpressXmlrpcClient\WordpressClient
	 */
	protected static $guestClient;

	public static function setUpBeforeClass()
	{
		static::$client = new WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_USER', 'WP_PASSWORD');
		static::$guestClient = new WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_GUEST', 'WP_PASSWORD');
	}

	public static function tearDownAfterClass()
	{
		static::$client = null;
		static::$guestClient = null;
	}

}

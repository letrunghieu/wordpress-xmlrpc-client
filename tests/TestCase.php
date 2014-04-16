<?php

namespace HieuLe\WordpressXmlrpcClientTest;
use HieuLe\WordpressXmlrpcClient\WordpressClient;

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
	protected $client;

	/**
	 * The user without proper privilege
	 * 
	 * @var \HieuLe\WordpressXmlrpcClient\WordpressClient
	 */
	protected $guestClient;

	public function setUp()
	{
		$this->client		 = new WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_USER', 'WP_PASSWORD');
		$this->guestClient	 = new WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_GUEST', 'WP_PASSWORD');
	}

	public function tearDown()
	{
		$this->client		 = null;
		$this->guestClient	 = null;
	}

}

<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Test user API
 * 
 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users
 *
 * @author TrungHieu
 */
class WordpressClientUsersComponentTest extends TestCase
{

	/**
	 * @vcr users/test-get-users-blogs-vcr.yml
	 */
	public function testGetUsersBlogs()
	{
		$result = $this->client->getUsersBlogs();
		$this->assertGreaterThan(0, count($result));
		$this->assertArrayHasKey('blogid', head($result));
		$this->assertArrayHasKey('blogName', head($result));
		$this->assertArrayHasKey('url', head($result));
		$this->assertArrayHasKey('xmlrpc', head($result));
		$this->assertArrayHasKey('isAdmin', head($result));
	}

}

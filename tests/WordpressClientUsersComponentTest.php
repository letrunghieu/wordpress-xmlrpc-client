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

	/**
	 * @vcr users/test-get-user-no-privilege-vcr.yml
	 */
	public function testGetUserNoPrivilege()
	{
		$user = $this->guestClient->getUser(1);
		$this->assertFalse($user);
		$this->assertSame('xmlrpc: Sorry, you cannot edit users. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr users/test-get-user-not-exist-vcr.yml
	 */
	public function testGetUserNotExist()
	{
		$user = $this->client->getUser(1000);
		$this->assertFalse($user);
		$this->assertSame('xmlrpc: Invalid user ID (404)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr users/test-get-user-vcr.yml
	 */
	public function testGetUser()
	{
		$user = $this->client->getUser(1);
		$this->assertArrayHasKey('user_id', $user);
		$this->assertArrayHasKey('username', $user);
		$this->assertArrayHasKey('first_name', $user);
		$this->assertArrayHasKey('last_name', $user);
		$this->assertArrayHasKey('bio', $user);
		$this->assertArrayHasKey('email', $user);
		$this->assertArrayHasKey('nickname', $user);
		$this->assertArrayHasKey('nicename', $user);
		$this->assertArrayHasKey('url', $user);
		$this->assertArrayHasKey('display_name', $user);
		$this->assertArrayHasKey('registered', $user);
		$this->assertArrayHasKey('roles', $user);

		$user = $this->client->getUser(1, array('user_id', 'email'));
		$this->assertArrayHasKey('user_id', $user);
		$this->assertArrayNotHasKey('username', $user);
		$this->assertArrayNotHasKey('first_name', $user);
		$this->assertArrayNotHasKey('last_name', $user);
		$this->assertArrayNotHasKey('bio', $user);
		$this->assertArrayHasKey('email', $user);
		$this->assertArrayNotHasKey('nickname', $user);
		$this->assertArrayNotHasKey('nicename', $user);
		$this->assertArrayNotHasKey('url', $user);
		$this->assertArrayNotHasKey('display_name', $user);
		$this->assertArrayNotHasKey('registered', $user);
		$this->assertArrayNotHasKey('roles', $user);
	}

}

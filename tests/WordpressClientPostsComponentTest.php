<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Test posts API
 * 
 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts
 *
 * @author TrungHieu
 */
class WordpressClientPostsComponentTest extends TestCase
{
	/**
	 * @vcr posts/test-get-post-return-ok-vcr.yml
	 */
	public function testGetPostReturnOk()
	{
		$post = $this->client->getPost(219);
		$this->assertArrayHasKey('post_title', $post);
		$this->assertArrayHasKey('post_date', $post);
		$this->assertSame('The post number six', $post['post_title']);
	}
	
	/**
	 * @vcr posts/test-get-post-with-fields-return-ok-vcr.yml
	 */
	public function testGetPostWithFieldsReturnOk()
	{
		$post = $this->client->getPost(219, array('post_title', 'post_status'));
		$this->assertArrayHasKey('post_title', $post);
		$this->assertArrayNotHasKey('post_date', $post);
	}
	
	/**
	 * @vcr posts/test-get-post-error-not-have-permission-vcr.yml
	 */
	public function testGetPostErrorNotHavePermission()
	{
		$post = $this->guestClient->getPost(219, array('post_title', 'post_status'));
		$this->assertFalse($post);
		$this->assertEquals('xmlrpc: Sorry, you cannot edit this post. (401)', $this->guestClient->getErrorMessage());
	}
	
	/**
	 * @vcr posts/test-get-post-error-invalid-post-id-vcr.yml
	 */
	public function testGetPostErrorInvalidPostId()
	{
		$post = $this->client->getPost(10000);
		$this->assertFalse($post);
		$this->assertEquals('xmlrpc: Invalid post ID. (404)', $this->client->getErrorMessage());
	}
}

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
	
	/**
	 * @vcr posts/test-get-posts-with-default-config-vcr.yml
	 */
	public function testGetPostsWithDefaultConfig()
	{
		$posts = $this->client->getPosts();
		$this->assertCount(10, $posts);
		$this->assertArrayHasKey('post_title', $posts[0]);
	}

	/**
	 * @vcr posts/test-get-posts-with-filters-vcr.yml
	 */
	public function testGetPostsWithFilters()
	{
		$posts = $this->client->getPosts(array('number' => 5));
		$this->assertCount(5, $posts);
		$this->assertArrayHasKey('post_title', $posts[0]);
	}

	/**
	 * @vcr posts/test-get-posts-with-fields-vcr.yml
	 */
	public function testGetPostsWithFields()
	{
		$posts = $this->client->getPosts(array(), array('post_id', 'post_data'));
		$this->assertCount(10, $posts);
		$this->assertArrayNotHasKey('post_title', $posts[0]);
	}
	
	public function testGetPostReturnEmpty()
	{
		$posts = $this->guestClient->getPosts();
		$this->assertFalse($posts);
		$this->assertEquals('xmlrpc: Sorry, you are not allowed to edit posts in this post type (401)', $this->guestClient->getErrorMessage());
	}
}

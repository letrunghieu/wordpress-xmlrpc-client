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

	/**
	 * @vcr posts/test-get-posts-return-empty-vcr.yml
	 */
	public function testGetPostReturnEmpty()
	{
		$posts = $this->guestClient->getPosts();
		$this->assertFalse($posts);
		$this->assertEquals('xmlrpc: Sorry, you are not allowed to edit posts in this post type (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr posts/test-new-post-minimal-info-vcr.yml
	 */
	public function testNewPostMinimalInfo()
	{
		$postId = (int) $this->client->newPost('Lorem ipsum', 'This is a demo post');
		$this->assertGreaterThan(0, $postId);

		$post = $this->client->getPost($postId);
		$this->assertSame('Lorem ipsum', $post['post_title']);
	}

	/**
	 * @vcr posts/test-new-post-with-category-and-thumbnail-vcr.yml
	 */
	public function testNewPostWithCategoryAndThumbnail()
	{
		$postId = (int) $this->client->newPost('Lorem ipsum', 'This is a demo post', array(20, 26), 229);
		$this->assertGreaterThan(0, $postId);

		$post = $this->client->getPost($postId);
		$this->assertSame('Lorem ipsum', $post['post_title']);
		$this->assertEquals(229, $post['post_thumbnail']['attachment_id']);
	}

	/**
	 * @vcr posts/test-new-post-with-advanced-fields-vcr.yml
	 */
	public function testNewPostWithAdvancedFields()
	{
		$postId = (int) $this->client->newPost('Lorem ipsum advanced', 'This is a demo post', array(), null, array('custom_fields' => array(array('key' => 'foo', 'value' => 'bar'))));
		$this->assertGreaterThan(0, $postId);

		$post = $this->client->getPost($postId);
		$this->assertSame('Lorem ipsum advanced', $post['post_title']);
		$this->assertCount(1, $post['custom_fields']);
		$this->assertSame('foo', $post['custom_fields'][0]['key']);
		$this->assertSame('bar', $post['custom_fields'][0]['value']);
	}

	/**
	 * @vcr posts/test-new-post-no-privilege-vcr.yml
	 */
	public function testNewPostNoPrivilege()
	{
		$postId = $this->guestClient->newPost('', '');
		$this->assertFalse($postId);
		$this->assertSame('xmlrpc: Sorry, you are not allowed to post on this site. (401)', $this->guestClient->getErrorMessage());
	}
	
	/**
	 * @vcr posts/test-new-post-invalid-term-vcr.yml
	 */
	public function testNewPostInvalidTerm()
	{
		$postId = $this->client->newPost('', '', array(2000, 2001));
		$this->assertFalse($postId);
		$this->assertSame('xmlrpc: Invalid term ID (403)', $this->client->getErrorMessage());
	}
	
	/**
	 * @vcr posts/test-new-post-invalid-thumbnail-vcr.yml
	 */
	public function testNewPostInvalidThumbnail()
	{
		$postId = $this->client->newPost('', '', array(), 9999);
		$this->assertFalse($postId);
		$this->assertSame('xmlrpc: Invalid attachment ID. (404)', $this->client->getErrorMessage());
	}

}

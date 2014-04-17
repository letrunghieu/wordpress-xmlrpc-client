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

	/**
	 * @vcr posts/test-edit-post-title-and-content-vcr.yml
	 */
	public function testEditPostTitleAndContent()
	{
		$result = $this->client->editPost(233, array('post_title' => 'Lorem Ipsum (edited)', 'post_content' => 'Muahahaha!'));
		$this->assertNotSame(false, $result);
		$this->assertTrue($result);

		$post = $this->client->getPost(233);
		$this->assertSame('Lorem Ipsum (edited)', $post['post_title']);
		$this->assertSame('Muahahaha!', $post['post_content']);
	}

	/**
	 * @vcr posts/test-edit-post-with-other-info-change-vcr.yml
	 */
	public function testEditPostWithOtherInfoChange()
	{
		$result = $this->client->editPost(233, array(
			'post_title' => 'Lorem Ipsum (edited)', 
			'post_content' => 'Muahahaha!', 
			'terms' => array(
				'category' => array(20, 26),
			), 
			'post_thumbnail' => 229, 
			'custom_fields' => array(array('key' => 'foo', 'value' => 'bar'))));
		$this->assertTrue($result);

		$post		 = $this->client->getPost(233);
		$categories	 = array();
		foreach ($post['terms'] as $t)
		{
			if ($t['taxonomy'] == 'category')
			{
				$categories[] = $t['term_id'];
			}
		}
		$this->assertSame('foo', $post['custom_fields'][0]['key']);
		$this->assertSame('bar', $post['custom_fields'][0]['value']);
		$this->assertEquals(229, $post['post_thumbnail']['attachment_id']);
		$this->assertTrue(in_array(20, $categories));
		$this->assertTrue(in_array(26, $categories));
	}

	/**
	 * @vcr posts/test-edit-post-with-invalid-id-vcr.yml
	 */
	public function testEditPostWithInvalidId()
	{
		$result = $this->client->editPost(1000, array('post_title' => 'Lorem Ipsum (edited)', 'post_content' => 'Muahahaha!'));
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Invalid post ID. (404)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr posts/test-edit-post-no-privilege-vcr.yml
	 */
	public function testEditPostNoPrivilege()
	{
		$result = $this->guestClient->editPost(233, array());
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Sorry, you are not allowed to edit this post. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr posts/test-delete-post-vcr.yml
	 */
	public function testDeletePost()
	{
		$result = $this->client->deletePost(234);
		$this->assertTrue($result);
	}

	/**
	 * @vcr posts/test-delete-post-with-invalid-id-vcr.yml
	 */
	public function testDeletePostWithInvalidId()
	{
		$result = $this->client->deletePost(1000);
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Invalid post ID. (404)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr posts/test-delete-post-no-privilege-vcr.yml
	 */
	public function testDeletePostNoPrivilege()
	{
		$result = $this->guestClient->deletePost(234);
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Sorry, you are not allowed to delete this post. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr posts/test-get-post-type-vcr.yml
	 */
	public function testGetPostType()
	{
		$postType = $this->client->getPostType('post');
		$this->assertArrayHasKey('name', $postType);
		$this->assertSame('Posts', $postType['label']);
	}

	/**
	 * @vcr posts/test-get-post-type-no-privilege-vcr.yml
	 */
	public function testGetPostTypeNoPrivilege()
	{
		$postType = $this->guestClient->getPostType('post');
		$this->assertFalse($postType);
		$this->assertSame('xmlrpc: Sorry, you are not allowed to edit this post type. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr posts/test-get-post-type-invalid-name-vcr.yml
	 */
	public function testGetPostTypeInvalidName()
	{
		$postType = $this->client->getPostType('post_foo');
		$this->assertFalse($postType);
		$this->assertSame('xmlrpc: Invalid post type (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr posts/test-get-post-types-vcr.yml
	 */
	public function testGetPostTypes()
	{
		$postTypes = $this->client->getPostTypes();
		$this->assertNotEmpty($postTypes);
		$this->assertArrayHasKey('post', $postTypes);
		$this->assertArrayHasKey('page', $postTypes);
		$this->assertArrayHasKey('wpcf7_contact_form', $postTypes);
	}

	/**
	 * @vcr posts/test-get-post-types-no-privilege-vcr.yml
	 */
	public function testGetPostTypesNoPrivilege()
	{
		$postTypes = $this->guestClient->getPostTypes();
		$this->assertEmpty($postTypes);
	}

	/**
	 * @vcr posts/test-get-post-formats-vcr.yml
	 */
	public function testGetPostFormats()
	{
		$postFormats = $this->client->getPostFormats();
		$this->assertArrayHasKey('standard', $postFormats);
		$this->assertArrayHasKey('video', $postFormats);
		$this->assertSame('Link', $postFormats['link']);
	}

	/**
	 * @vcr posts/test-get-post-formats-no-privilege-vcr.yml
	 */
	public function testGetPostFormatsNoPrivilege()
	{
		$postFormats = $this->guestClient->getPostFormats();
		$this->assertFalse($postFormats);
		$this->assertSame('xmlrpc: You are not allowed access to details about this site. (403)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr posts/test-get-post-status-list-vcr.yml
	 */
	public function testGetPostStatusList()
	{
		$statuses = $this->client->getPostStatusList();
		$this->assertCount(4, $statuses);
		$this->assertArrayHasKey('publish', $statuses);
		$this->assertSame('Pending Review', $statuses['pending']);
	}

	/**
	 * @vcr posts/test-get-post-status-list-no-privilege-vcr.yml
	 */
	public function testGetPostStatusListNoPrivilege()
	{
		$statuses = $this->guestClient->getPostStatusList();
		$this->assertFalse($statuses);
		$this->assertSame('xmlrpc: You are not allowed access to details about this site. (403)', $this->guestClient->getErrorMessage());
	}

}

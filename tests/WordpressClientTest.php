<?php

//namespace HieuLe\WordpressXmlrpcClient;

/**
 * Description of WordpressClientTest
 *
 * @author TrungHieu
 */
class WordpressClientTest extends \PHPUnit_Framework_TestCase
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
		$this->client		 = new HieuLe\WordpressXmlrpcClient\WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_USER', 'WP_PASSWORD');
		$this->guestClient	 = new HieuLe\WordpressXmlrpcClient\WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_GUEST', 'WP_PASSWORD');
	}

	public function tearDown()
	{
		$this->client		 = null;
		$this->guestClient	 = null;
	}

	/**
	 * @vcr posts/test-get-posts-with-default-config-vcr.yml
	 */
	public function testGetPostsWithDefaultConfig()
	{
		$posts = $this->client->getPosts();
		$this->assertGreaterThan(0, count($posts));
		$this->assertArrayHasKey('post_id', $posts[0]);
		$this->assertArrayHasKey('post_title', $posts[0]);
		$this->assertArrayHasKey('post_date', $posts[0]);
		$this->assertArrayHasKey('post_date_gmt', $posts[0]);
		$this->assertArrayHasKey('post_modified', $posts[0]);
		$this->assertArrayHasKey('post_modified_gmt', $posts[0]);
		$this->assertArrayHasKey('post_status', $posts[0]);
		$this->assertArrayHasKey('post_type', $posts[0]);
		$this->assertArrayHasKey('post_format', $posts[0]);
		$this->assertArrayHasKey('post_name', $posts[0]);
		$this->assertArrayHasKey('post_author', $posts[0]);
		$this->assertArrayHasKey('post_password', $posts[0]);
		$this->assertArrayHasKey('post_excerpt', $posts[0]);
		$this->assertArrayHasKey('post_content', $posts[0]);
		$this->assertArrayHasKey('post_parent', $posts[0]);
		$this->assertArrayHasKey('post_mime_type', $posts[0]);
		$this->assertArrayHasKey('link', $posts[0]);
		$this->assertArrayHasKey('guid', $posts[0]);
		$this->assertArrayHasKey('menu_order', $posts[0]);
		$this->assertArrayHasKey('comment_status', $posts[0]);
		$this->assertArrayHasKey('ping_status', $posts[0]);
		$this->assertArrayHasKey('sticky', $posts[0]);
		$this->assertArrayHasKey('post_thumbnail', $posts[0]);
		$this->assertArrayHasKey('terms', $posts[0]);
		$this->assertArrayHasKey('custom_fields', $posts[0]);
	}

	/**
	 * @vcr posts/test-get-posts-with-filters-vcr.yml
	 */
	public function testGetPostsWithFilters()
	{
		$posts = $this->client->getPosts(array('number' => 5));
		$this->assertLessThanOrEqual(5, count($posts));
		$this->assertGreaterThan(0, count($posts));
		$this->assertArrayHasKey('post_id', $posts[0]);
		$this->assertArrayHasKey('post_title', $posts[0]);
		$this->assertArrayHasKey('post_date', $posts[0]);
		$this->assertArrayHasKey('post_date_gmt', $posts[0]);
		$this->assertArrayHasKey('post_modified', $posts[0]);
		$this->assertArrayHasKey('post_modified_gmt', $posts[0]);
		$this->assertArrayHasKey('post_status', $posts[0]);
		$this->assertArrayHasKey('post_type', $posts[0]);
		$this->assertArrayHasKey('post_format', $posts[0]);
		$this->assertArrayHasKey('post_name', $posts[0]);
		$this->assertArrayHasKey('post_author', $posts[0]);
		$this->assertArrayHasKey('post_password', $posts[0]);
		$this->assertArrayHasKey('post_excerpt', $posts[0]);
		$this->assertArrayHasKey('post_content', $posts[0]);
		$this->assertArrayHasKey('post_parent', $posts[0]);
		$this->assertArrayHasKey('post_mime_type', $posts[0]);
		$this->assertArrayHasKey('link', $posts[0]);
		$this->assertArrayHasKey('guid', $posts[0]);
		$this->assertArrayHasKey('menu_order', $posts[0]);
		$this->assertArrayHasKey('comment_status', $posts[0]);
		$this->assertArrayHasKey('ping_status', $posts[0]);
		$this->assertArrayHasKey('sticky', $posts[0]);
		$this->assertArrayHasKey('post_thumbnail', $posts[0]);
		$this->assertArrayHasKey('terms', $posts[0]);
		$this->assertArrayHasKey('custom_fields', $posts[0]);
	}

	/**
	 * @vcr posts/test-get-posts-with-fields-vcr.yml
	 */
	public function testGetPostsWithFields()
	{
		$posts = $this->client->getPosts(array(), array('post_id', 'post_date'));
		$this->assertGreaterThan(0, count($posts));
		$this->assertArrayHasKey('post_id', $posts[0]);
		$this->assertArrayNotHasKey('post_title', $posts[0]);
		$this->assertArrayHasKey('post_date', $posts[0]);
		$this->assertArrayNotHasKey('post_date_gmt', $posts[0]);
		$this->assertArrayNotHasKey('post_modified', $posts[0]);
		$this->assertArrayNotHasKey('post_modified_gmt', $posts[0]);
		$this->assertArrayNotHasKey('post_status', $posts[0]);
		$this->assertArrayNotHasKey('post_type', $posts[0]);
		$this->assertArrayNotHasKey('post_format', $posts[0]);
		$this->assertArrayNotHasKey('post_name', $posts[0]);
		$this->assertArrayNotHasKey('post_author', $posts[0]);
		$this->assertArrayNotHasKey('post_password', $posts[0]);
		$this->assertArrayNotHasKey('post_excerpt', $posts[0]);
		$this->assertArrayNotHasKey('post_content', $posts[0]);
		$this->assertArrayNotHasKey('post_parent', $posts[0]);
		$this->assertArrayNotHasKey('post_mime_type', $posts[0]);
		$this->assertArrayNotHasKey('link', $posts[0]);
		$this->assertArrayNotHasKey('guid', $posts[0]);
		$this->assertArrayNotHasKey('menu_order', $posts[0]);
		$this->assertArrayNotHasKey('comment_status', $posts[0]);
		$this->assertArrayNotHasKey('ping_status', $posts[0]);
		$this->assertArrayNotHasKey('sticky', $posts[0]);
		$this->assertArrayNotHasKey('post_thumbnail', $posts[0]);
		$this->assertArrayNotHasKey('terms', $posts[0]);
		$this->assertArrayNotHasKey('custom_fields', $posts[0]);
	}

	/**
	 * @vcr posts/test-get-posts-return-empty-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage Sorry, you are not allowed to edit posts in this post type
	 */
	public function testGetPostReturnEmpty()
	{
		$posts = $this->guestClient->getPosts();
	}

	/**
	 * @vcr posts/test-get-post-return-ok-vcr.yml
	 */
	public function testGetPostReturnOk()
	{
		$posts	 = $this->client->getPosts(array('number' => 1));
		$post	 = $this->client->getPost($posts[0]['post_id']);
		$this->assertArrayHasKey('post_id', $post);
		$this->assertArrayHasKey('post_title', $post);
		$this->assertArrayHasKey('post_date', $post);
		$this->assertArrayHasKey('post_date_gmt', $post);
		$this->assertArrayHasKey('post_modified', $post);
		$this->assertArrayHasKey('post_modified_gmt', $post);
		$this->assertArrayHasKey('post_status', $post);
		$this->assertArrayHasKey('post_type', $post);
		$this->assertArrayHasKey('post_format', $post);
		$this->assertArrayHasKey('post_name', $post);
		$this->assertArrayHasKey('post_author', $post);
		$this->assertArrayHasKey('post_password', $post);
		$this->assertArrayHasKey('post_excerpt', $post);
		$this->assertArrayHasKey('post_content', $post);
		$this->assertArrayHasKey('post_parent', $post);
		$this->assertArrayHasKey('post_mime_type', $post);
		$this->assertArrayHasKey('link', $post);
		$this->assertArrayHasKey('guid', $post);
		$this->assertArrayHasKey('menu_order', $post);
		$this->assertArrayHasKey('comment_status', $post);
		$this->assertArrayHasKey('ping_status', $post);
		$this->assertArrayHasKey('sticky', $post);
		$this->assertArrayHasKey('post_thumbnail', $post);
		$this->assertArrayHasKey('terms', $post);
		$this->assertArrayHasKey('custom_fields', $post);
	}

	/**
	 * @vcr posts/test-get-post-with-fields-return-ok-vcr.yml
	 */
	public function testGetPostWithFieldsReturnOk()
	{
		$posts	 = $this->client->getPosts(array('number' => 1));
		$post	 = $this->client->getPost($posts[0]['post_id'], array('post_title', 'post_status'));
		$this->assertArrayHasKey('post_id', $post);
		$this->assertArrayHasKey('post_title', $post);
		$this->assertArrayNotHasKey('post_date', $post);
		$this->assertArrayNotHasKey('post_date_gmt', $post);
		$this->assertArrayNotHasKey('post_modified', $post);
		$this->assertArrayNotHasKey('post_modified_gmt', $post);
		$this->assertArrayHasKey('post_status', $post);
		$this->assertArrayNotHasKey('post_type', $post);
		$this->assertArrayNotHasKey('post_format', $post);
		$this->assertArrayNotHasKey('post_name', $post);
		$this->assertArrayNotHasKey('post_author', $post);
		$this->assertArrayNotHasKey('post_password', $post);
		$this->assertArrayNotHasKey('post_excerpt', $post);
		$this->assertArrayNotHasKey('post_content', $post);
		$this->assertArrayNotHasKey('post_parent', $post);
		$this->assertArrayNotHasKey('post_mime_type', $post);
		$this->assertArrayNotHasKey('link', $post);
		$this->assertArrayNotHasKey('guid', $post);
		$this->assertArrayNotHasKey('menu_order', $post);
		$this->assertArrayNotHasKey('comment_status', $post);
		$this->assertArrayNotHasKey('ping_status', $post);
		$this->assertArrayNotHasKey('sticky', $post);
		$this->assertArrayNotHasKey('post_thumbnail', $post);
		$this->assertArrayNotHasKey('terms', $post);
		$this->assertArrayNotHasKey('custom_fields', $post);
	}

	/**
	 * @vcr posts/test-get-post-error-not-have-permission-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage Sorry, you cannot edit this post.
	 */
	public function testGetPostErrorNotHavePermission()
	{
		$post = $this->guestClient->getPost(219, array('post_title', 'post_status'));
	}

	/**
	 * @vcr posts/test-get-post-error-invalid-post-id-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid post ID.
	 */
	public function testGetPostErrorInvalidPostId()
	{
		$post = $this->client->getPost(10000);
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
	 * @vcr posts/test-new-post-with-advanced-fields-vcr.yml
	 */
	public function testNewPostWithAdvancedFields()
	{
		$postId = (int) $this->client->newPost('Lorem ipsum advanced', 'This is a demo post', array('custom_fields' => array(array('key' => 'foo', 'value' => 'bar'))));
		$this->assertGreaterThan(0, $postId);

		$post	 = $this->client->getPost($postId);
		$this->assertSame('Lorem ipsum advanced', $post['post_title']);
		$this->assertGreaterThanOrEqual(1, count($post['custom_fields']));
		$ok		 = false;
		foreach ($post['custom_fields'] as $field)
		{
			if ($field['key'] == 'foo' && $field['value'] == 'bar')
			{
				$ok = true;
				break;
			}
		}
		if (!$ok)
		{
			$this->fail('No custom fields');
		}
	}

	/**
	 * @vcr posts/test-new-post-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage Sorry, you are not allowed to post on this site.
	 */
	public function testNewPostNoPrivilege()
	{
		$postId = $this->guestClient->newPost('', '');
	}

	/**
	 * @vcr posts/test-new-post-invalid-term-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid term ID
	 */
	public function testNewPostInvalidTerm()
	{
		$postId = $this->client->newPost('Foo title', '', array('terms' => array('category' => array(2000, 2001))));
	}

	/**
	 * @vcr posts/test-new-post-invalid-thumbnail-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid attachment ID.
	 */
	public function testNewPostInvalidThumbnail()
	{
		$postId = $this->client->newPost('', '', array('post_thumbnail' => 9999));
	}

	/**
	 * @vcr posts/test-edit-post-title-and-content-vcr.yml
	 */
	public function testEditPostTitleAndContent()
	{
		$postId	 = $this->client->newPost('This is original title', 'This is original body');
		$result	 = $this->client->editPost($postId, array('post_title' => 'Lorem Ipsum (edited)', 'post_content' => 'Muahahaha!'));
		$this->assertTrue($result);

		$post = $this->client->getPost($postId);
		$this->assertSame('Lorem Ipsum (edited)', $post['post_title']);
		$this->assertSame('Muahahaha!', $post['post_content']);
	}

	/**
	 * @vcr posts/test-edit-post-with-other-info-change-vcr.yml
	 */
	public function testEditPostWithOtherInfoChange()
	{
		$postId	 = $this->client->newPost('This is original title 2', 'This is original body 2');
		$result	 = $this->client->editPost($postId, array(
			'post_title'	 => 'Lorem Ipsum (edited)',
			'post_content'	 => 'Muahahaha!',
			'custom_fields'	 => array(array('key' => 'foo', 'value' => 'bar'))));
		$this->assertTrue($result);

		$post	 = $this->client->getPost($postId);
		$this->assertSame('Lorem Ipsum (edited)', $post['post_title']);
		$this->assertSame('Muahahaha!', $post['post_content']);
		$ok		 = false;
		foreach ($post['custom_fields'] as $field)
		{
			if ($field['key'] == 'foo' && $field['value'] == 'bar')
			{
				$ok = true;
				break;
			}
		}
		if (!$ok)
		{
			$this->fail('No custom fields');
		}
	}

	/**
	 * @vcr posts/test-edit-post-with-invalid-id-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid post ID.
	 */
	public function testEditPostWithInvalidId()
	{
		$result = $this->client->editPost(1000, array('post_title' => 'Lorem Ipsum (edited)', 'post_content' => 'Muahahaha!'));
	}

	/**
	 * @vcr posts/test-edit-post-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage Sorry, you are not allowed to edit this post.
	 */
	public function testEditPostNoPrivilege()
	{
		$result = $this->guestClient->editPost(233, array());
	}

	/**
	 * @vcr posts/test-delete-post-vcr.yml
	 */
	public function testDeletePost()
	{
		$postId = $this->client->newPost('Created to delete', '');
		$result = $this->client->deletePost($postId);
		$this->assertTrue($result);
		$post = $this->client->getPost($postId);
		$this->assertSame('trash', $post['post_status']);
	}

	/**
	 * @vcr posts/test-delete-post-with-invalid-id-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid post ID.
	 */
	public function testDeletePostWithInvalidId()
	{
		$result = $this->client->deletePost(1000);
	}

	/**
	 * @vcr posts/test-delete-post-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage Sorry, you are not allowed to delete this post.
	 */
	public function testDeletePostNoPrivilege()
	{
		$result = $this->guestClient->deletePost(234);
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
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage Sorry, you are not allowed to edit this post type.
	 */
	public function testGetPostTypeNoPrivilege()
	{
		$postType = $this->guestClient->getPostType('post');
	}

	/**
	 * @vcr posts/test-get-post-type-invalid-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid post type
	 */
	public function testGetPostTypeInvalidName()
	{
		$postType = $this->client->getPostType('post_foo');
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
		$this->assertSame('Link', $postFormats['link']);
	}

	/**
	 * @vcr posts/test-get-post-formats-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage You are not allowed access to details about this site.
	 */
	public function testGetPostFormatsNoPrivilege()
	{
		$postFormats = $this->guestClient->getPostFormats();
	}

	/**
	 * @vcr posts/test-get-post-status-list-vcr.yml
	 */
	public function testGetPostStatusList()
	{
		$statuses = $this->client->getPostStatusList();
		$this->assertGreaterThan(0, count($statuses));
		$this->assertArrayHasKey('publish', $statuses);
		$this->assertSame('Pending Review', $statuses['pending']);
	}

	/**
	 * @vcr posts/test-get-post-status-list-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage You are not allowed access to details about this site.
	 */
	public function testGetPostStatusListNoPrivilege()
	{
		$statuses = $this->guestClient->getPostStatusList();
	}

}

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

	#
	# Test posts API

	#

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
		$postId	 = $this->client->newPost('Created to delete', '');
		$result	 = $this->client->deletePost($postId);
		$this->assertTrue($result);
		$post	 = $this->client->getPost($postId);
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

	#
	# Test taxonomies API

	#
	
	/**
	 * @vcr taxonomies/test-get-taxonomy-vcr.yml
	 */
	public function testGetTaxonomy()
	{
		$taxonomy = $this->client->getTaxonomy('category');
		$this->assertArrayHasKey('name', $taxonomy);
		$this->assertArrayHasKey('label', $taxonomy);
		$this->assertArrayHasKey('hierarchical', $taxonomy);
		$this->assertArrayHasKey('public', $taxonomy);
		$this->assertArrayHasKey('show_ui', $taxonomy);
		$this->assertArrayHasKey('_builtin', $taxonomy);
		$this->assertArrayHasKey('labels', $taxonomy);
		$this->assertArrayHasKey('cap', $taxonomy);
		$this->assertArrayHasKey('object_type', $taxonomy);
	}

	/**
	 * @vcr taxonomies/test-get-taxonomy-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You are not allowed to assign terms in this taxonomy.
	 */
	public function testGetTaxonomyNoPrivilege()
	{
		$taxonomy = $this->guestClient->getTaxonomy('category');
	}

	/**
	 * @vcr taxonomies/test-get-taxonomy-invalid-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid taxonomy
	 */
	public function testGetTaxonomyInvalidName()
	{
		$taxonomy = $this->client->getTaxonomy('foo');
	}

	/**
	 * @vcr taxonomies/test-get-taxonomies-vcr.yml
	 */
	public function testGetTaxonomies()
	{
		$taxonomies = $this->client->getTaxonomies();
		$this->assertGreaterThan(0, count($taxonomies));
		$this->assertArrayHasKey('name', $taxonomies[0]);
		$this->assertArrayHasKey('label', $taxonomies[0]);
		$this->assertArrayHasKey('hierarchical', $taxonomies[0]);
		$this->assertArrayHasKey('public', $taxonomies[0]);
		$this->assertArrayHasKey('show_ui', $taxonomies[0]);
		$this->assertArrayHasKey('_builtin', $taxonomies[0]);
		$this->assertArrayHasKey('labels', $taxonomies[0]);
		$this->assertArrayHasKey('cap', $taxonomies[0]);
		$this->assertArrayHasKey('object_type', $taxonomies[0]);
	}

	/**
	 * @cvr taxonomies/test-get-taxonomies-no-privilege-vcr.yml
	 */
	public function testGetTaxonomiesNoPrivilege()
	{
		$taxonomies = $this->guestClient->getTaxonomies();
		$this->assertEmpty($taxonomies);
	}

	/**
	 * @vcr taxonomies/test-get-terms-vcr.yml
	 */
	public function testGetTerms()
	{
		$terms = $this->client->getTerms('category');
		$this->assertGreaterThan(0, count($terms));
		$this->assertArrayHasKey('term_id', $terms[0]);
		$this->assertArrayHasKey('name', $terms[0]);
		$this->assertArrayHasKey('slug', $terms[0]);
		$this->assertArrayHasKey('term_group', $terms[0]);
		$this->assertArrayHasKey('term_taxonomy_id', $terms[0]);
		$this->assertArrayHasKey('taxonomy', $terms[0]);
		$this->assertArrayHasKey('description', $terms[0]);
		$this->assertArrayHasKey('parent', $terms[0]);
		$this->assertArrayHasKey('count', $terms[0]);
	}

	/**
	 * @vcr taxonomies/test-get-terms-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You are not allowed to assign terms in this taxonomy.
	 */
	public function testGetTermsNoPrivilege()
	{
		$terms = $this->guestClient->getTerms('post_tag');
	}

	/**
	 * @vcr taxonomies/test-get-terms-invalid-taxonomy-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid taxonomy
	 */
	public function testGetTermsInvalidTaxonomyName()
	{
		$terms = $this->client->getTerms('foo');
	}

	/**
	 * @vcr taxonomies/test-get-term-vcr.yml
	 */
	public function testGetTerm()
	{
		$terms	 = $this->client->getTerms('category', array('number' => 1));
		$term	 = $this->client->getTerm($terms[0]['term_id'], 'category');
		$this->assertArrayHasKey('term_id', $term);
		$this->assertArrayHasKey('name', $term);
		$this->assertArrayHasKey('slug', $term);
		$this->assertArrayHasKey('term_group', $term);
		$this->assertArrayHasKey('term_taxonomy_id', $term);
		$this->assertArrayHasKey('taxonomy', $term);
		$this->assertArrayHasKey('description', $term);
		$this->assertArrayHasKey('parent', $term);
		$this->assertArrayHasKey('count', $term);
	}

	/**
	 * @vcr taxonomies/test-get-term-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You are not allowed to assign terms in this taxonomy.
	 */
	public function testGetTermNoPrivilege()
	{
		$term = $this->guestClient->getTerm(23, 'category');
	}

	/**
	 * @vcr taxonomies/test-get-term-invalid-taxonomy-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid taxonomy
	 */
	public function testGetTermInvalidTaxonomyName()
	{
		$term = $this->client->getTerm(1000, 'foo');
	}

	/**
	 * @vcr taxonomies/test-get-term-invalid-term-id-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid term ID
	 */
	public function testGetTermInvalidTermId()
	{
		$term = $this->client->getTerm(1000, 'category');
	}

	/**
	 * @vcr taxonomies/test-new-term-vcr.yml
	 */
	public function testNewTerm()
	{
		$termId = (int) $this->client->newTerm('Category Lorem Ipsum', 'category');
		$this->assertGreaterThan(0, $termId);

		$term = $this->client->getTerm($termId, 'category');
		$this->assertSame('Category Lorem Ipsum', $term['name']);
	}

	/**
	 * @vcr taxonomies/test-new-term-with-more-info-vcr.yml
	 */
	public function testNewTermWithMoreInfo()
	{
		$termId = (int) $this->client->newTerm('Category Lorem', 'category', 'cat-lorem', 'Lorem Ipsum');
		$this->assertGreaterThan(0, $termId);

		$term = $this->client->getTerm($termId, 'category');
		$this->assertSame('Category Lorem', $term['name']);
		$this->assertSame('cat-lorem', $term['slug']);
		$this->assertSame('Lorem Ipsum', $term['description']);
	}

	/**
	 * @vcr taxonomies/test-new-term-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You are not allowed to create terms in this taxonomy.
	 */
	public function testNewTermNoPrivilege()
	{
		$termId = $this->guestClient->newTerm('foo', 'category');
	}

	/**
	 * @vcr taxonomies/test-new-term-invalid-taxonomy-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid taxonomy
	 */
	public function testNewTermInvalidTaxonomyName()
	{
		$termId = $this->client->newTerm('foo', 'category-foo');
	}

	/**
	 * @vcr taxonomies/test-new-term-empty-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage The term name cannot be empty.
	 */
	public function testNewTermEmptyName()
	{
		$termId = $this->client->newTerm('', 'category');
	}

	/**
	 * @vcr taxonomies/test-new-term-no-hierachical-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage This taxonomy is not hierarchical.
	 */
	public function testNewTermNoHierachical()
	{
		$tagId	 = $this->client->newTerm('Tag bar', 'post_tag');
		$termId	 = $this->client->newTerm('Tag Foo', 'post_tag', null, null, $tagId);
	}

	/**
	 * @vcr taxonomies/test-new-term-invalid-parent-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Parent term does not exist.
	 */
	public function testNewTermInvalidParent()
	{
		$termId = $this->client->newTerm('Tag Foo', 'category', null, null, 999);
	}

	/**
	 * @vcr taxonomies/test-edit-term-vcr.yml
	 */
	public function testEditTerm()
	{
		$termId	 = $this->client->newTerm('Created to delete', 'category');
		$this->assertGreaterThan(0, (int) $termId);
		$result	 = $this->client->EditTerm($termId, 'category', array('name' => 'Category Lorem 2',));
		$this->assertTrue($result);

		$term = $this->client->getTerm($termId, 'category');
		$this->assertSame('Category Lorem 2', $term['name']);
	}

	/**
	 * @vcr taxonomies/test-edit-term-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You are not allowed to edit terms in this taxonomy.
	 */
	public function testEditTermNoPrivilege()
	{
		$terms	 = $this->client->getTerms('category', array('number' => 1));
		$this->assertNotEmpty($terms);
		$result	 = $this->guestClient->EditTerm($terms[0]['term_id'], 'category');
	}

	/**
	 * @vcr taxonomies/test-edit-term-invalid-taxonomy-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid taxonomy
	 */
	public function testEditTermInvalidTaxonomyName()
	{
		$termId = $this->client->EditTerm(47, 'category-foo');
	}

	/**
	 * @vcr taxonomies/test-edit-term-empty-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage The term name cannot be empty.
	 */
	public function testEditTermEmptyName()
	{
		$terms	 = $this->client->getTerms('category', array('number' => 1));
		$this->assertNotEmpty($terms);
		$result	 = $this->client->EditTerm($terms[0]['term_id'], 'category', array('name' => ''));
	}

	/**
	 * @vcr taxonomies/test-edit-term-invalid-parent-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Parent term does not exist.
	 */
	public function testEditTermInvalidParent()
	{
		$terms	 = $this->client->getTerms('category', array('number' => 1));
		$this->assertNotEmpty($terms);
		$result	 = $this->client->EditTerm($terms[0]['term_id'], 'category', array('parent' => 999));
	}

	/**
	 * @vcr taxonomies/test-edit-term-not-exist-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid term ID
	 */
	public function testEditTermNotExist()
	{
		$termId = $this->client->EditTerm(444, 'category');
	}

	/**
	 * @vcr taxonomies/test-delete-term-vcr.yml
	 */
	public function testDeleteTerm()
	{
		$termId	 = $this->client->newTerm('Deleted term', 'category');
		$result	 = $this->client->deleteTerm($termId, 'category');
		$this->assertTrue($result);
	}

	/**
	 * @vcr taxonomies/test-delete-term-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You are not allowed to delete terms in this taxonomy.
	 */
	public function testDeleteTermNoPrivilege()
	{
		$terms	 = $this->client->getTerms('category', array('number' => 1));
		$this->assertNotEmpty($terms);
		$termId	 = $this->guestClient->DeleteTerm($terms[0]['term_id'], 'category');
	}

	/**
	 * @vcr taxonomies/test-delete-term-invalid-taxonomy-name-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage Invalid taxonomy
	 */
	public function testDeleteTermInvalidTaxonomyName()
	{
		$result = $this->client->deleteTerm(28, 'category-foo');
	}

	/**
	 * @vcr taxonomies/test-delete-term-not-exist-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid term ID
	 */
	public function testDeleteTermNotExist()
	{
		$termId = $this->client->deleteTerm(444, 'category');
	}

	#
	# Test media API

	#
	
	/**
	 * @vcr media/test-upload-file-vcr.yml
	 */
	public function testUploadFile()
	{
		$content		 = file_get_contents("tests/image.jpg");
		$mime			 = mime_content_type("tests/image.jpg");
		$file			 = $this->client->uploadFile('foo image.jpg', $mime, $content);
		$this->assertArrayHasKey('id', $file);
		$this->assertArrayHasKey('file', $file);
		$this->assertArrayHasKey('url', $file);
		$this->assertArrayHasKey('type', $file);
	}

	/**
	 * @vcr media/test-upload-file-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 401
	 * @expectedExceptionMessage You do not have permission to upload files.
	 */
	public function testUploadFileNoPrivilege()
	{
		$file = $this->guestClient->uploadFile('Foo', 'image/jpeg', 'file_content');
	}

	/**
	 * @vcr media/test-upload-file-error-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 500
	 * @expectedExceptionMessage Could not write file Foo (Invalid file type)
	 */
	public function testUploadFileError()
	{
		$file = $this->client->uploadFile('Foo', 'bar', '');
	}

	/**
	 * @vcr media/test-get-media-item-vcr.yml
	 */
	public function testGetMediaItem()
	{
		$content = file_get_contents("tests/image.jpg");
		$mime	 = mime_content_type("tests/image.jpg");
		$file	 = $this->client->uploadFile('foo image 1.jpg', $mime, $content);
		$media	 = $this->client->getMediaItem($file['id']);
		$this->assertArrayHasKey('attachment_id', $media);
		$this->assertArrayHasKey('date_created_gmt', $media);
		$this->assertArrayHasKey('parent', $media);
		$this->assertArrayHasKey('link', $media);
		$this->assertArrayHasKey('title', $media);
		$this->assertArrayHasKey('caption', $media);
		$this->assertArrayHasKey('description', $media);
		$this->assertArrayHasKey('metadata', $media);
		$this->assertArrayHasKey('thumbnail', $media);
	}

	/**
	 * @vcr media/test-get-media-item-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 403
	 * @expectedExceptionMessage You do not have permission to upload files.
	 */
	public function testGetMediaItemNoPrivilege()
	{
		$media = $this->guestClient->getMediaItem(229);
	}

	/**
	 * @vcr media/test-get-media-item-no-exist-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Invalid attachment ID.
	 */
	public function testGetMediaItemNoExist()
	{
		$media = $this->client->getMediaItem(999);
	}

	/**
	 * @vcr media/test-get-media-library-vcr.yml
	 */
	public function testGetMediaLibrary()
	{
		$content = file_get_contents("tests/image.jpg");
		$mime	 = mime_content_type("tests/image.jpg");
		$file	 = $this->client->uploadFile('foo image 2.jpg', $mime, $content);
		$medias	 = $this->client->getMediaLibrary();
		$this->assertNotEmpty($medias);
		$this->assertArrayHasKey('attachment_id', $medias[0]);
		$this->assertArrayHasKey('date_created_gmt', $medias[0]);
		$this->assertArrayHasKey('parent', $medias[0]);
		$this->assertArrayHasKey('link', $medias[0]);
		$this->assertArrayHasKey('title', $medias[0]);
		$this->assertArrayHasKey('caption', $medias[0]);
		$this->assertArrayHasKey('description', $medias[0]);
		$this->assertArrayHasKey('metadata', $medias[0]);
		$this->assertArrayHasKey('thumbnail', $medias[0]);
	}

	/**
	 * @vcr media/test-get-media-library-with-filter-vcr.yml
	 */
	public function testGetMediaLibraryWithFilter()
	{
		$medias = $this->client->getMediaLibrary(array('number' => 5));
		$this->assertLessThanOrEqual(5, count($medias));
	}

	/**
	 * @vcr media/test-get-media-library-no-privilege-vcr.yml
	 * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
	 * @expectedException 401
	 * @expectedExceptionMessage You do not have permission to upload files.
	 */
	public function testGetMediaLibraryNoPrivilege()
	{
		$medias = $this->guestClient->getMediaLibrary();
	}

}

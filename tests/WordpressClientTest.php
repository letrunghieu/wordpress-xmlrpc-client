<?php
namespace HieuLe\WordpressXmlrpcClient;

/**
 * Description of WordpressClientTest
 *
 * @author TrungHieu
 */
class WordpressClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 *
	 * @var WordpressClient
	 */
	protected $client;

	public function setUp()
	{
		$this->client = new WordpressClient('http://WP_DOMAIN/xmlrpc.php', 'WP_USER', 'WP_PASSWORD');
	}

	public function tearDown()
	{
		$this->client = NULL;
	}

	/**
	 * @vcr get-post-full-test-vcr.yml
	 */
	public function testGetPostWithInformation()
	{
		$post = $this->client->getPost(219);
		$this->assertArrayHasKey('post_title', $post);
		$this->assertArrayHasKey('post_date', $post);
		$this->assertSame('The post number six', $post['post_title']);
	}

	/**
	 * @vcr get-post-selected-test-vcr.yml
	 */
	public function testGetPostWithSelectedInformation()
	{
		$post = $this->client->getPost(219, array('post_title', 'post_status'));
		$this->assertArrayHasKey('post_title', $post);
		$this->assertArrayNotHasKey('post_date', $post);
	}
	
	/**
	 * @vcr get-posts-default-config-test-vcr.yml
	 */
	public function testGetPostsWithDefaultConfig()
	{
		$posts = $this->client->getPosts();
		$this->assertCount(10, $posts);
		$this->assertArrayHasKey('post_title', $posts[0]);
	}
	
	/**
	 * @vcr get-posts-filtered-test-vcr.yml
	 */
	public function testGetPostsWithFilters()
	{
		$posts = $this->client->getPosts(array('number' => 5));
		$this->assertCount(5, $posts);
		$this->assertArrayHasKey('post_title', $posts[0]);
	}
	
	/**
	 * @vcr get-posts-with-fields-test-vcr.yml
	 */
	public function testGetPostsWithFields()
	{
		$posts = $this->client->getPosts(array(), array('post_id', 'post_data'));
		$this->assertCount(10, $posts);
		$this->assertArrayNotHasKey('post_title', $posts[0]);
	}
	
	
	/**
	 * @vcr new-post-minimal-info-test-vcr.yml
	 */
	public function testNewPostMinimalInfo()
	{
		$postId = (int) $this->client->newPost('Lorem ipsum', 'This is a demo post');
		$this->assertGreaterThan(0, $postId);

		$post = $this->client->getPost($postId);
		$this->assertSame('Lorem ipsum', $post['post_title']);
	}

	/**
	 * @vcr new-post-with-category-and-thumbnail.yml
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
	 * @vcr new-post-with-advanced-fields-test-vcr.yaml
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
	 * @vcr edit-post-title-and-content-test-vcr.yml
	 */
	public function testEditPostTitleAndContent()
	{
		$result = $this->client->editPost(233, 'Lorem Ipsum (edited)', 'Muahahaha!');
		$this->assertNotSame(false, $result);
		$this->assertTrue($result);
		
		$post = $this->client->getPost(233);
		$this->assertSame('Lorem Ipsum (edited)', $post['post_title']);
		$this->assertSame('Muahahaha!', $post['post_content']);
	}
	
	/**
	 * @vcr edit-post-with-other-info-change-test-vcr.yml
	 */
	public function testEditPostWithOtherInfoChange()
	{
		$result = $this->client->editPost(233, 'Lorem Ipsum (edited)', 'Muahahaha!', array(20, 26), 229, array('custom_fields' => array(array('key' => 'foo', 'value' => 'bar'))));
		$this->assertTrue($result);
		
		$post = $this->client->getPost(233);
		$categories = array();
		foreach($post['terms'] as $t)
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
	 * @vcr edit-post-with-invalid-id-test-vcr.yml
	 */
	public function testEditPostWithInvalidId()
	{
		$result = $this->client->editPost(1000, 'Foo', '');
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Invalid post ID. (404)', $this->client->getErrorMessage());
	}
	
	/**
	 * @vcr delete-post-test-vcr.yml
	 */
	public function testDeletePost()
	{
		$result = $this->client->deletePost(232);
		$this->assertTrue($result);
	}
	
	/**
	 * @vcr delete-post-with-invalid-id-test-vcr.yml
	 */
	public function testDeletePostWithInvalidId()
	{
		$result = $this->client->deletePost(1000);
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Invalid post ID. (404)', $this->client->getErrorMessage());
	}
	
}

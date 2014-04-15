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
	
}

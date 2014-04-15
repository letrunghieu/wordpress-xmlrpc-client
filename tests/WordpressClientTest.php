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
	
}

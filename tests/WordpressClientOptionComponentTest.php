<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Test options API 
 * 
 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Options
 *
 * @author TrungHieu
 */
class WordpressClientOptionComponentTest extends TestCase
{
	/**
	 * @vcr options/test-get-options-vcr.yml
	 */
	public function testGetOptions()
	{
		$options = $this->client->getOptions();
		$this->assertNotEmpty($options);
		$this->assertArrayHasKey('desc', head($options));
		$this->assertArrayHasKey('readonly', head($options));
		$this->assertArrayHasKey('value', head($options));
	}
	
	/**
	 * @vcr options/test-get-options-with-filters-vcr.yml
	 */
	public function testGetOptionsWithFilter()
	{
		$options = $this->client->getOptions(array('thumbnail_size_w', 'thumbnail_size_h'));
		$this->assertArrayHasKey('desc', head($options));
		$this->assertArrayHasKey('readonly', head($options));
		$this->assertArrayHasKey('value', head($options));
		$this->assertArrayHasKey('thumbnail_size_w', $options);
		$this->assertArrayHasKey('thumbnail_size_h', $options);
	}
}

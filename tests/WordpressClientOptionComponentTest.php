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

	/**
	 * @vcr options/test-set-options-vcr.yml
	 */
	public function testSetOptions()
	{
		$result = $this->client->setOptions(array('thumbnail_size_w' => 1000));
		$this->assertSame(1000, $result['thumbnail_size_w']['value']);
	}

	/**
	 * @vcr options/test-set-options-no-privilege-vcr.yml
	 */
	public function testSetOptionsNoPrivilege()
	{
		$result = $this->guestClient->setOptions(array('thumbnail_size_w' => 1000));
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: You are not allowed to update options. (403)', $this->guestClient->getErrorMessage());
	}

}

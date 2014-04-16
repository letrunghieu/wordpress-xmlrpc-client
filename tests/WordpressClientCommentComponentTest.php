<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Description of WordpressClientCommentComponentTest
 *
 * @author TrungHieu
 */
class WordpressClientCommentComponentTest extends TestCase
{
	/**
	 * @vcr comments/test-get-comment-count-vcr.yml
	 */
	public function testGetCommentCount()
	{
		$count = $this->client->getCommentCount(1);
		$this->assertNotSame(false, $count);
		$this->assertArrayHasKey('approved', $count);
		$this->assertArrayHasKey('awaiting_moderation', $count);
		$this->assertArrayHasKey('spam', $count);
		$this->assertArrayHasKey('total_comments', $count);
	}
	
	/**
	 * @vcr comments/test-get-comment-count-no-privilege-vcr.yml
	 */
	public function testGetCommentCountNoPrivilege()
	{
		$count = $this->guestClient->getCommentCount(1);
		$this->assertFalse($count);
		$this->assertSame('xmlrpc: You are not allowed access to details about comments. (403)', $this->guestClient->getErrorMessage());
	}
}

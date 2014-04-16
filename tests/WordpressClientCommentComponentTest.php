<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Test comment API
 * 
 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments
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
	
	/**
	 * @vcr comments/test-get-comment-vcr.yml
	 */
	public function testGetComment()
	{
		$comment = $this->client->getComment(1);
		$this->assertArrayHasKey('comment_id', $comment);
		$this->assertArrayHasKey('parent', $comment);
		$this->assertArrayHasKey('user_id', $comment);
		$this->assertArrayHasKey('date_created_gmt', $comment);
		$this->assertArrayHasKey('status', $comment);
		$this->assertArrayHasKey('content', $comment);
		$this->assertArrayHasKey('link', $comment);
		$this->assertArrayHasKey('post_id', $comment);
		$this->assertArrayHasKey('post_title', $comment);
		$this->assertArrayHasKey('author', $comment);
		$this->assertArrayHasKey('author_url', $comment);
		$this->assertArrayHasKey('author_email', $comment);
		$this->assertArrayHasKey('author_ip', $comment);
		$this->assertArrayHasKey('type', $comment);
	}
	
	/**
	 * @vcr comments/test-get-comment-no-privilege-vcr.yml
	 */
	public function testGetCommentNoPrivilege()
	{
		$comment = $this->guestClient->getComment(1);
		$this->assertFalse($comment);
		$this->assertSame('xmlrpc: You are not allowed to moderate comments on this site. (403)', $this->guestClient->getErrorMessage());
	}
	
	/**
	 * @vcr comments/test-get-comment-not-exist-vcr.yml
	 */
	public function testGetCommentNotExist()
	{
		$comment = $this->client->getComment(1000);
		$this->assertFalse($comment);
		$this->assertSame('xmlrpc: Invalid comment ID. (404)', $this->client->getErrorMessage());
	}
}

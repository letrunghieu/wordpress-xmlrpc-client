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

	/**
	 * @vcr comments/test-get-comment-no-filter-vcr.yml
	 */
	public function testGetCommentsNoFilter()
	{
		$comments = $this->client->getComments();
		$this->assertGreaterThan(0, count($comments));
		$this->assertArrayHasKey('comment_id', $comments[0]);
		$this->assertArrayHasKey('parent', $comments[0]);
		$this->assertArrayHasKey('user_id', $comments[0]);
		$this->assertArrayHasKey('date_created_gmt', $comments[0]);
		$this->assertArrayHasKey('status', $comments[0]);
		$this->assertArrayHasKey('content', $comments[0]);
		$this->assertArrayHasKey('link', $comments[0]);
		$this->assertArrayHasKey('post_id', $comments[0]);
		$this->assertArrayHasKey('post_title', $comments[0]);
		$this->assertArrayHasKey('author', $comments[0]);
		$this->assertArrayHasKey('author_url', $comments[0]);
		$this->assertArrayHasKey('author_email', $comments[0]);
		$this->assertArrayHasKey('author_ip', $comments[0]);
		$this->assertArrayHasKey('type', $comments[0]);
	}

	/**
	 * @vcr comments/test-get-commnents-no-privilege-vcr.yml
	 */
	public function testGetCommentsNoPrivilege()
	{
		$comments = $this->guestClient->getComments();
		$this->assertFalse($comments);
		$this->assertSame('xmlrpc: Sorry, you cannot edit comments. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr comments/test-get-comments-with-filter-vcr.yml
	 */
	public function testGetCommentsWithFilter()
	{
		$comments = $this->client->getComments(array('post_id' => 223));
		$this->assertCount(0, $comments);
	}

	/**
	 * @vcr comments/test-new-comment-vcr.yml
	 */
	public function testNewComment()
	{
		$commentId = $this->client->newComment(1, array('content' => 'Lorem ipsum'));
		$this->assertGreaterThan(0, (int) $commentId);
	}

	/**
	 * @vcr comments/test-new-conmment-no-post-exist-vcr.yml
	 */
	public function testNewCommentNoPostExist()
	{
		$commentId = $this->client->newComment(1000, array('content' => 'First comment'));
		$this->assertFalse($commentId);
		$this->assertSame('xmlrpc: Invalid post ID. (404)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr comments/test-edit-comment-vcr.yml
	 */
	public function testEditComment()
	{
		$commentId	 = $this->client->newComment(1, array('content' => 'A comment to be edit'));
		$this->assertGreaterThan(0, (int) $commentId);
		$result		 = $this->client->editComment($commentId, array('content' => 'I have editted this comment!'));
		$this->assertTrue($result);
		$comment	 = $this->client->getComment($commentId);
		$this->assertSame('I have editted this comment!', $comment['content']);
	}

	/**
	 * @vcr comments/test-edit-comment-not-exist-vcr.yml
	 */
	public function testEditCommentNotExist()
	{
		$result = $this->client->editComment(1000, array('content' => 'I have editted this comment!'));
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Invalid comment ID. (404)', $this->client->getErrorMessage());
	}
	
	/**
	 * @vcr comments/test-edit-comment-no-privilege-vcr.yml
	 */
	public function testEditCommentNoPrivilege()
	{
		$result = $this->guestClient->editComment(1, array('content' => 'I have editted this comment!'));
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: You are not allowed to moderate comments on this site. (403)', $this->guestClient->getErrorMessage());
	}
	
	
	/**
	 * @vcr comments/test-delete-comment-vcr.yml
	 */
	public function testDeleteComment()
	{
		$commentId	 = $this->client->newComment(1, array('content' => 'A comment to be edit'));
		$this->assertGreaterThan(0, (int) $commentId);
		$result		 = $this->client->deleteComment($commentId);
		$this->assertTrue($result);
		$comment	 = $this->client->getComment($commentId);
		$this->assertSame('trash', $comment['status']);
	}

	/**
	 * @vcr comments/test-delete-comment-not-exist-vcr.yml
	 */
	public function testDeleteCommentNotExist()
	{
		$result = $this->client->deleteComment(1000);
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: Invalid comment ID. (404)', $this->client->getErrorMessage());
	}
	
	/**
	 * @vcr comments/test-delete-comment-no-privilege-vcr.yml
	 */
	public function testDeleteCommentNoPrivilege()
	{
		$result = $this->guestClient->deleteComment(1);
		$this->assertFalse($result);
		$this->assertSame('xmlrpc: You are not allowed to moderate comments on this site. (403)', $this->guestClient->getErrorMessage());
	}

}

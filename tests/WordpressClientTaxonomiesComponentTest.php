<?php

namespace HieuLe\WordpressXmlrpcClientTest;

/**
 * Test taxonomy API
 * 
 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies
 *
 * @author TrungHieu
 */
class WordpressClientTaxonomiesComponentTest extends TestCase
{

	/**
	 * @vcr taxonomies/test-get-taxonomy-vcr.yml
	 */
	public function testGetTaxonomy()
	{
		$taxonomy = $this->client->getTaxonomy('category');
		$this->assertSame('Categories', $taxonomy['label']);
	}

	/**
	 * @vcr taxonomies/test-get-taxonomy-no-privilege-vcr.yml
	 */
	public function testGetTaxonomyNoPrivilege()
	{
		$taxonomy = $this->guestClient->getTaxonomy('category');
		$this->assertFalse($taxonomy);
		$this->assertSame('xmlrpc: You are not allowed to assign terms in this taxonomy. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-get-taxonomy-invalid-name-vcr.yml
	 */
	public function testGetTaxonomyInvalidName()
	{
		$taxonomy = $this->client->getTaxonomy('foo');
		$this->assertFalse($taxonomy);
		$this->assertSame('xmlrpc: Invalid taxonomy (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-get-taxonomies-vcr.yml
	 */
	public function testGetTaxonomies()
	{
		$taxonomies = $this->client->getTaxonomies();
		$this->assertCount(3, $taxonomies);
		$this->assertArrayHasKey('name', $taxonomies[0]);
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
	 * @vcr taxonomies/test-get-term-vcr.yml
	 */
	public function testGetTerm()
	{
		$term = $this->client->getTerm(23, 'category');
		$this->assertSame('Location', $term['name']);
		$this->assertSame('location', $term['slug']);
	}
	
	
	/**
	 * @vcr taxonomies/test-get-term-no-privilege-vcr.yml
	 */
	public function testGetTermNoPrivilege()
	{
		$term = $this->guestClient->getTerm(23, 'category');
		$this->assertFalse($term);
		$this->assertSame('xmlrpc: You are not allowed to assign terms in this taxonomy. (401)', $this->guestClient->getErrorMessage());
	}
	
	/**
	 * @vcr taxonomies/test-get-term-invalid-taxonomy-name-vcr.yml
	 */
	public function testGetTermInvalidTaxonomyName()
	{
		$term = $this->client->getTerm(1000, 'foo');
		$this->assertFalse($term);
		$this->assertSame('xmlrpc: Invalid taxonomy (403)', $this->client->getErrorMessage());
	}
	
	/**
	 * @vcr taxonomies/test-get-term-invalid-term-id-vcr.yml
	 */
	public function testGetTermInvalidTermId()
	{
		$term = $this->client->getTerm(1000, 'category');
		$this->assertFalse($term);
		$this->assertSame('xmlrpc: Invalid term ID (404)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-get-terms-vcr.yml
	 */
	public function testGetTerms()
	{
		$terms = $this->client->getTerms('post_tag');
		$this->assertCount(4, $terms);
	}
	
	/**
	 * @vcr taxonomies/test-get-terms-no-privilege-vcr.yml
	 */
	public function testGetTermsNoPrivilege()
	{
		$terms = $this->guestClient->getTerms('post_tag');
		$this->assertFalse($terms);
		$this->assertSame('xmlrpc: You are not allowed to assign terms in this taxonomy. (401)', $this->guestClient->getErrorMessage());
	}
	
	/**
	 * @vcr taxonomies/test-get-terms-invalid-taxonomy-name-vcr.yml
	 */
	public function testGetTermsInvalidTaxonomyName()
	{
		$terms = $this->client->getTerms('foo');
		$this->assertFalse($terms);
		$this->assertSame('xmlrpc: Invalid taxonomy (403)', $this->client->getErrorMessage());
	}

}

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

	/**
	 * @vcr taxonomies/test-new-term-vcr.yml
	 */
	public function testNewTerm()
	{
		$termId = (int) $this->client->newTerm('Category Lorem', 'category');
		$this->assertGreaterThan(0, $termId);

		$term = $this->client->getTerm($termId, 'category');
		$this->assertSame('Category Lorem', $term['name']);
	}

	/**
	 * @vcr taxonomies/test-new-term-with-more-info-vcr.yml
	 */
	public function testNewTermWithMoreInfo()
	{
		$termId = (int) $this->client->newTerm('Category Lorem', 'category', 'cate-lorem-2', 'Lorem Ipsum', 3);
		$this->assertGreaterThan(0, $termId);

		$term = $this->client->getTerm($termId, 'category');
		$this->assertSame('Category Lorem', $term['name']);
		$this->assertSame('cate-lorem-2', $term['slug']);
		$this->assertSame('Lorem Ipsum', $term['description']);
		$this->assertSame('3', $term['parent']);
	}

	/**
	 * @vcr taxonomies/test-new-term-no-privilege-vcr.yml
	 */
	public function testNewTermNoPrivilege()
	{
		$termId = $this->guestClient->newTerm('foo', 'category');
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: You are not allowed to create terms in this taxonomy. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-new-term-invalid-taxonomy-name-vcr.yml
	 */
	public function testNewTermInvalidTaxonomyName()
	{
		$termId = $this->client->newTerm('foo', 'category-foo');
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: Invalid taxonomy (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-new-term-empty-name-vcr.yml
	 */
	public function testNewTermEmptyName()
	{
		$termId = $this->client->newTerm('', 'category');
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: The term name cannot be empty. (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-new-term-no-hierachical-vcr.yml
	 */
	public function testNewTermNoHierachical()
	{
		$termId = $this->client->newTerm('Tag Foo', 'post_tag', null, null, 16);
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: This taxonomy is not hierarchical. (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-new-term-invalid-parent-vcr.yml
	 */
	public function testNewTermInvalidParent()
	{
		$termId = $this->client->newTerm('Tag Foo', 'category', null, null, 999);
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: Parent term does not exist. (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-edit-term-vcr.yml
	 */
	public function testEditTerm()
	{
		$termId = $this->client->EditTerm(47, 'category', array('name' => 'Category Lorem',));
		$this->assertTrue($termId);

		$term = $this->client->getTerm(47, 'category');
		$this->assertSame('Category Lorem', $term['name']);
	}

	/**
	 * @vcr taxonomies/test-edit-term-with-more-info-vcr.yml
	 */
	public function testEditTermWithMoreInfo()
	{
		$termId = $this->client->EditTerm(47, 'category', array('name' => 'Category Lorem', 'slug' => 'cate-lorem-3', 'description' => 'Lorem Ipsum', 'parent' => 3));
		$this->assertTrue($termId);

		$term = $this->client->getTerm(47, 'category');
		$this->assertSame('Category Lorem', $term['name']);
		$this->assertSame('cate-lorem-3', $term['slug']);
		$this->assertSame('Lorem Ipsum', $term['description']);
		$this->assertSame('3', $term['parent']);
	}

	/**
	 * @vcr taxonomies/test-edit-term-no-privilege-vcr.yml
	 */
	public function testEditTermNoPrivilege()
	{
		$termId = $this->guestClient->EditTerm(47, 'category');
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: You are not allowed to edit terms in this taxonomy. (401)', $this->guestClient->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-edit-term-invalid-taxonomy-name-vcr.yml
	 */
	public function testEditTermInvalidTaxonomyName()
	{
		$termId = $this->client->EditTerm(47, 'category-foo');
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: Invalid taxonomy (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-edit-term-empty-name-vcr.yml
	 */
	public function testEditTermEmptyName()
	{
		$termId = $this->client->EditTerm(47, 'category', array('name' => ''));
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: The term name cannot be empty. (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-edit-term-no-hierachical-vcr.yml
	 */
	public function testEditTermNoHierachical()
	{
		$termId = $this->client->EditTerm(18, 'post_tag', array('parent' => 16));
		$this->assertFalse($termId);
		$this->assertSame("xmlrpc: This taxonomy is not hierarchical so you can't set a parent. (403)", $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-edit-term-invalid-parent-vcr.yml
	 */
	public function testEditTermInvalidParent()
	{
		$termId = $this->client->EditTerm(47, 'category', array('parent' => 999));
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: Parent term does not exist. (403)', $this->client->getErrorMessage());
	}

	/**
	 * @vcr taxonomies/test-edit-term-not-exist-vcr.yml
	 */
	public function testEditTermNotExist()
	{
		$termId = $this->client->EditTerm(444, 'category');
		$this->assertFalse($termId);
		$this->assertSame('xmlrpc: Invalid term ID (404)', $this->client->getErrorMessage());
	}

}

<?php

//namespace HieuLe\WordpressXmlrpcClient;

/**
 * Description of WordpressClientTest
 *
 * @author TrungHieu
 */
class WordpressClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The user with proper privilege
     *
     * @var \HieuLe\WordpressXmlrpcClient\WordpressClient
     */
    protected $client;

    /**
     * The user without proper privilege
     * 
     * @var \HieuLe\WordpressXmlrpcClient\WordpressClient
     */
    protected $guestClient;
    private static $_endpoint      = 'http://WP_DOMAIN/xmlrpc.php';
    private static $_adminLogin    = 'WP_USER';
    private static $_adminPassword = 'WP_PASSWORD';
    private static $_guestLogin    = 'WP_GUEST';
    private static $_guestPassword = 'WP_PASSWORD';

    public static function setUpBeforeClass()
    {
        $testConfig = \Symfony\Component\Yaml\Yaml::parse('tests/xmlrpc.yml');
        if ($testConfig['endpoint'] && $testConfig['admin_login'] && $testConfig['admin_password'] && $testConfig['guest_login'] && $testConfig['guest_password'])
        {
            static::$_endpoint = $testConfig['endpoint'];
            static::$_adminLogin = $testConfig['admin_login'];
            static::$_adminPassword = $testConfig['admin_password'];
            static::$_guestLogin = $testConfig['guest_login'];
            static::$_guestPassword = $testConfig['guest_password'];
        }
    }

    public function setUp()
    {
        $this->client      = new HieuLe\WordpressXmlrpcClient\WordpressClient(static::$_endpoint, static::$_adminLogin, static::$_adminPassword);
        $this->guestClient = new HieuLe\WordpressXmlrpcClient\WordpressClient(static::$_endpoint, static::$_guestLogin, static::$_guestPassword);
    }

    public function tearDown()
    {
        $this->client      = null;
        $this->guestClient = null;
    }

    /**
     * @vcr test-login-failed-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Incorrect username or password.
     */
    public function testLoginFailed()
    {
        $client = new HieuLe\WordpressXmlrpcClient\WordpressClient(static::$_endpoint, 'admin', '');
        $client->getPosts();
    }

    /**
     * @expectedException \HieuLe\WordpressXmlrpcClient\Exception\NetworkException
     */
    public function testNetworkError()
    {
        $client = new HieuLe\WordpressXmlrpcClient\WordpressClient('xxx.domain', '', '');
        $client->getPosts();
    }

    #
    # Test posts API

    #

	/**
     * @vcr posts/test-get-posts-with-default-config-vcr.yml
     */
    public function testGetPostsWithDefaultConfig()
    {
        $posts = $this->client->getPosts();
        $this->assertGreaterThan(0, count($posts));
        $this->assertArrayHasKey('post_id', $posts[0]);
        $this->assertArrayHasKey('post_title', $posts[0]);
        $this->assertArrayHasKey('post_date', $posts[0]);
        $this->assertArrayHasKey('post_date_gmt', $posts[0]);
        $this->assertArrayHasKey('post_modified', $posts[0]);
        $this->assertArrayHasKey('post_modified_gmt', $posts[0]);
        $this->assertArrayHasKey('post_status', $posts[0]);
        $this->assertArrayHasKey('post_type', $posts[0]);
        $this->assertArrayHasKey('post_format', $posts[0]);
        $this->assertArrayHasKey('post_name', $posts[0]);
        $this->assertArrayHasKey('post_author', $posts[0]);
        $this->assertArrayHasKey('post_password', $posts[0]);
        $this->assertArrayHasKey('post_excerpt', $posts[0]);
        $this->assertArrayHasKey('post_content', $posts[0]);
        $this->assertArrayHasKey('post_parent', $posts[0]);
        $this->assertArrayHasKey('post_mime_type', $posts[0]);
        $this->assertArrayHasKey('link', $posts[0]);
        $this->assertArrayHasKey('guid', $posts[0]);
        $this->assertArrayHasKey('menu_order', $posts[0]);
        $this->assertArrayHasKey('comment_status', $posts[0]);
        $this->assertArrayHasKey('ping_status', $posts[0]);
        $this->assertArrayHasKey('sticky', $posts[0]);
        $this->assertArrayHasKey('post_thumbnail', $posts[0]);
        $this->assertArrayHasKey('terms', $posts[0]);
        $this->assertArrayHasKey('custom_fields', $posts[0]);
    }

    /**
     * @vcr posts/test-get-posts-with-filters-vcr.yml
     */
    public function testGetPostsWithFilters()
    {
        $posts = $this->client->getPosts(array('number' => 5));
        $this->assertLessThanOrEqual(5, count($posts));
        $this->assertGreaterThan(0, count($posts));
        $this->assertArrayHasKey('post_id', $posts[0]);
        $this->assertArrayHasKey('post_title', $posts[0]);
        $this->assertArrayHasKey('post_date', $posts[0]);
        $this->assertArrayHasKey('post_date_gmt', $posts[0]);
        $this->assertArrayHasKey('post_modified', $posts[0]);
        $this->assertArrayHasKey('post_modified_gmt', $posts[0]);
        $this->assertArrayHasKey('post_status', $posts[0]);
        $this->assertArrayHasKey('post_type', $posts[0]);
        $this->assertArrayHasKey('post_format', $posts[0]);
        $this->assertArrayHasKey('post_name', $posts[0]);
        $this->assertArrayHasKey('post_author', $posts[0]);
        $this->assertArrayHasKey('post_password', $posts[0]);
        $this->assertArrayHasKey('post_excerpt', $posts[0]);
        $this->assertArrayHasKey('post_content', $posts[0]);
        $this->assertArrayHasKey('post_parent', $posts[0]);
        $this->assertArrayHasKey('post_mime_type', $posts[0]);
        $this->assertArrayHasKey('link', $posts[0]);
        $this->assertArrayHasKey('guid', $posts[0]);
        $this->assertArrayHasKey('menu_order', $posts[0]);
        $this->assertArrayHasKey('comment_status', $posts[0]);
        $this->assertArrayHasKey('ping_status', $posts[0]);
        $this->assertArrayHasKey('sticky', $posts[0]);
        $this->assertArrayHasKey('post_thumbnail', $posts[0]);
        $this->assertArrayHasKey('terms', $posts[0]);
        $this->assertArrayHasKey('custom_fields', $posts[0]);
    }

    /**
     * @vcr posts/test-get-posts-with-fields-vcr.yml
     */
    public function testGetPostsWithFields()
    {
        $posts = $this->client->getPosts(array(), array('post_id', 'post_date'));
        $this->assertGreaterThan(0, count($posts));
        $this->assertArrayHasKey('post_id', $posts[0]);
        $this->assertArrayNotHasKey('post_title', $posts[0]);
        $this->assertArrayHasKey('post_date', $posts[0]);
        $this->assertArrayNotHasKey('post_date_gmt', $posts[0]);
        $this->assertArrayNotHasKey('post_modified', $posts[0]);
        $this->assertArrayNotHasKey('post_modified_gmt', $posts[0]);
        $this->assertArrayNotHasKey('post_status', $posts[0]);
        $this->assertArrayNotHasKey('post_type', $posts[0]);
        $this->assertArrayNotHasKey('post_format', $posts[0]);
        $this->assertArrayNotHasKey('post_name', $posts[0]);
        $this->assertArrayNotHasKey('post_author', $posts[0]);
        $this->assertArrayNotHasKey('post_password', $posts[0]);
        $this->assertArrayNotHasKey('post_excerpt', $posts[0]);
        $this->assertArrayNotHasKey('post_content', $posts[0]);
        $this->assertArrayNotHasKey('post_parent', $posts[0]);
        $this->assertArrayNotHasKey('post_mime_type', $posts[0]);
        $this->assertArrayNotHasKey('link', $posts[0]);
        $this->assertArrayNotHasKey('guid', $posts[0]);
        $this->assertArrayNotHasKey('menu_order', $posts[0]);
        $this->assertArrayNotHasKey('comment_status', $posts[0]);
        $this->assertArrayNotHasKey('ping_status', $posts[0]);
        $this->assertArrayNotHasKey('sticky', $posts[0]);
        $this->assertArrayNotHasKey('post_thumbnail', $posts[0]);
        $this->assertArrayNotHasKey('terms', $posts[0]);
        $this->assertArrayNotHasKey('custom_fields', $posts[0]);
    }

    /**
     * @vcr posts/test-get-posts-return-empty-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you are not allowed to edit posts in this post type
     */
    public function testGetPostReturnEmpty()
    {
        $posts = $this->guestClient->getPosts();
    }

    /**
     * @vcr posts/test-get-post-return-ok-vcr.yml
     */
    public function testGetPostReturnOk()
    {
        $posts = $this->client->getPosts(array('number' => 1));
        $post  = $this->client->getPost($posts[0]['post_id']);
        $this->assertArrayHasKey('post_id', $post);
        $this->assertArrayHasKey('post_title', $post);
        $this->assertArrayHasKey('post_date', $post);
        $this->assertArrayHasKey('post_date_gmt', $post);
        $this->assertArrayHasKey('post_modified', $post);
        $this->assertArrayHasKey('post_modified_gmt', $post);
        $this->assertArrayHasKey('post_status', $post);
        $this->assertArrayHasKey('post_type', $post);
        $this->assertArrayHasKey('post_format', $post);
        $this->assertArrayHasKey('post_name', $post);
        $this->assertArrayHasKey('post_author', $post);
        $this->assertArrayHasKey('post_password', $post);
        $this->assertArrayHasKey('post_excerpt', $post);
        $this->assertArrayHasKey('post_content', $post);
        $this->assertArrayHasKey('post_parent', $post);
        $this->assertArrayHasKey('post_mime_type', $post);
        $this->assertArrayHasKey('link', $post);
        $this->assertArrayHasKey('guid', $post);
        $this->assertArrayHasKey('menu_order', $post);
        $this->assertArrayHasKey('comment_status', $post);
        $this->assertArrayHasKey('ping_status', $post);
        $this->assertArrayHasKey('sticky', $post);
        $this->assertArrayHasKey('post_thumbnail', $post);
        $this->assertArrayHasKey('terms', $post);
        $this->assertArrayHasKey('custom_fields', $post);
    }

    /**
     * @vcr posts/test-get-post-with-fields-return-ok-vcr.yml
     */
    public function testGetPostWithFieldsReturnOk()
    {
        $posts = $this->client->getPosts(array('number' => 1));
        $post  = $this->client->getPost($posts[0]['post_id'], array('post_title', 'post_status'));
        $this->assertArrayHasKey('post_id', $post);
        $this->assertArrayHasKey('post_title', $post);
        $this->assertArrayNotHasKey('post_date', $post);
        $this->assertArrayNotHasKey('post_date_gmt', $post);
        $this->assertArrayNotHasKey('post_modified', $post);
        $this->assertArrayNotHasKey('post_modified_gmt', $post);
        $this->assertArrayHasKey('post_status', $post);
        $this->assertArrayNotHasKey('post_type', $post);
        $this->assertArrayNotHasKey('post_format', $post);
        $this->assertArrayNotHasKey('post_name', $post);
        $this->assertArrayNotHasKey('post_author', $post);
        $this->assertArrayNotHasKey('post_password', $post);
        $this->assertArrayNotHasKey('post_excerpt', $post);
        $this->assertArrayNotHasKey('post_content', $post);
        $this->assertArrayNotHasKey('post_parent', $post);
        $this->assertArrayNotHasKey('post_mime_type', $post);
        $this->assertArrayNotHasKey('link', $post);
        $this->assertArrayNotHasKey('guid', $post);
        $this->assertArrayNotHasKey('menu_order', $post);
        $this->assertArrayNotHasKey('comment_status', $post);
        $this->assertArrayNotHasKey('ping_status', $post);
        $this->assertArrayNotHasKey('sticky', $post);
        $this->assertArrayNotHasKey('post_thumbnail', $post);
        $this->assertArrayNotHasKey('terms', $post);
        $this->assertArrayNotHasKey('custom_fields', $post);
    }

    /**
     * @vcr posts/test-get-post-error-not-have-permission-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you cannot edit this post.
     */
    public function testGetPostErrorNotHavePermission()
    {
        $post = $this->guestClient->getPost(219, array('post_title', 'post_status'));
    }

    /**
     * @vcr posts/test-get-post-error-invalid-post-id-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid post ID.
     */
    public function testGetPostErrorInvalidPostId()
    {
        $post = $this->client->getPost(-1);
    }

    /**
     * @vcr posts/test-new-post-minimal-info-vcr.yml
     */
    public function testNewPostMinimalInfo()
    {
        $postId = (int) $this->client->newPost('Lorem ipsum', 'This is a demo post');
        $this->assertGreaterThan(0, $postId);

        $post = $this->client->getPost($postId);
        $this->assertSame('Lorem ipsum', $post['post_title']);
    }

    /**
     * @vcr posts/test-new-post-with-advanced-fields-vcr.yml
     */
    public function testNewPostWithAdvancedFields()
    {
        $postDate = new DateTime('20140101T00:00:00+07:00');
        $postId   = (int) $this->client->newPost('Lorem ipsum advanced', 'This is a demo post', array('post_date' => $postDate, 'custom_fields' => array(array('key' => 'foo', 'value' => 'bar'))));
        $this->assertGreaterThan(0, $postId);

        $post = $this->client->getPost($postId);
        $this->assertSame('Lorem ipsum advanced', $post['post_title']);
        $this->assertGreaterThanOrEqual(1, count($post['custom_fields']));
        $this->assertSame($postDate->format('Ymd\TH:i:s'), $post['post_date']->scalar);
        $this->assertSame($postDate->getTimestamp(), $post['post_date_gmt']->timestamp);
        $ok   = false;
        foreach ($post['custom_fields'] as $field)
        {
            if ($field['key'] == 'foo' && $field['value'] == 'bar')
            {
                $ok = true;
                break;
            }
        }
        if (!$ok)
        {
            $this->fail('No custom fields');
        }
    }

    /**
     * @vcr posts/test-new-post-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you are not allowed to post on this site.
     */
    public function testNewPostNoPrivilege()
    {
        $postId = $this->guestClient->newPost('', '');
    }

    /**
     * @vcr posts/test-new-post-invalid-term-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid term ID
     */
    public function testNewPostInvalidTerm()
    {
        $postId = $this->client->newPost('Foo title', '', array('terms' => array('category' => array(2000, 2001))));
    }

    /**
     * @vcr posts/test-new-post-invalid-thumbnail-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid attachment ID.
     */
    public function testNewPostInvalidThumbnail()
    {
        $postId = $this->client->newPost('', '', array('post_thumbnail' => 9999));
    }

    /**
     * @vcr posts/test-edit-post-title-and-content-vcr.yml
     */
    public function testEditPostTitleAndContent()
    {
        $postId = $this->client->newPost('This is original title', 'This is original body');
        $result = $this->client->editPost($postId, array('post_title' => 'Lorem Ipsum (edited)', 'post_content' => 'Muahahaha!'));
        $this->assertTrue($result);

        $post = $this->client->getPost($postId);
        $this->assertSame('Lorem Ipsum (edited)', $post['post_title']);
        $this->assertSame('Muahahaha!', $post['post_content']);
    }

    /**
     * @vcr posts/test-edit-post-with-other-info-change-vcr.yml
     */
    public function testEditPostWithOtherInfoChange()
    {
        $postId = $this->client->newPost('This is original title 2', 'This is original body 2');
        $result = $this->client->editPost($postId, array(
            'post_title'    => 'Lorem Ipsum (edited)',
            'post_content'  => 'Muahahaha!',
            'custom_fields' => array(array('key' => 'foo', 'value' => 'bar'))));
        $this->assertTrue($result);

        $post = $this->client->getPost($postId);
        $this->assertSame('Lorem Ipsum (edited)', $post['post_title']);
        $this->assertSame('Muahahaha!', $post['post_content']);
        $ok   = false;
        foreach ($post['custom_fields'] as $field)
        {
            if ($field['key'] == 'foo' && $field['value'] == 'bar')
            {
                $ok = true;
                break;
            }
        }
        if (!$ok)
        {
            $this->fail('No custom fields');
        }
    }

    /**
     * @vcr posts/test-edit-post-with-invalid-id-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid post ID.
     */
    public function testEditPostWithInvalidId()
    {
        $result = $this->client->editPost(-1, array('post_title' => 'Lorem Ipsum (edited)', 'post_content' => 'Muahahaha!'));
    }

    /**
     * @vcr posts/test-edit-post-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you are not allowed to edit this post.
     */
    public function testEditPostNoPrivilege()
    {
        $result = $this->guestClient->editPost(233, array());
    }

    /**
     * @vcr posts/test-delete-post-vcr.yml
     */
    public function testDeletePost()
    {
        $postId = $this->client->newPost('Created to delete', '');
        $result = $this->client->deletePost($postId);
        $this->assertTrue($result);
        $post   = $this->client->getPost($postId);
        $this->assertSame('trash', $post['post_status']);
    }

    /**
     * @vcr posts/test-delete-post-with-invalid-id-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid post ID.
     */
    public function testDeletePostWithInvalidId()
    {
        $result = $this->client->deletePost(-1);
    }

    /**
     * @vcr posts/test-delete-post-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you are not allowed to delete this post.
     */
    public function testDeletePostNoPrivilege()
    {
        $result = $this->guestClient->deletePost(234);
    }

    /**
     * @vcr posts/test-get-post-type-vcr.yml
     */
    public function testGetPostType()
    {
        $postType = $this->client->getPostType('post');
        $this->assertArrayHasKey('name', $postType);
        $this->assertSame('Posts', $postType['label']);
    }

    /**
     * @vcr posts/test-get-post-type-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you are not allowed to edit this post type.
     */
    public function testGetPostTypeNoPrivilege()
    {
        $postType = $this->guestClient->getPostType('post');
    }

    /**
     * @vcr posts/test-get-post-type-invalid-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid post type
     */
    public function testGetPostTypeInvalidName()
    {
        $postType = $this->client->getPostType('post_foo');
    }

    /**
     * @vcr posts/test-get-post-types-vcr.yml
     */
    public function testGetPostTypes()
    {
        $postTypes = $this->client->getPostTypes();
        $this->assertNotEmpty($postTypes);
        $this->assertArrayHasKey('post', $postTypes);
        $this->assertArrayHasKey('page', $postTypes);
    }

    /**
     * @vcr posts/test-get-post-types-no-privilege-vcr.yml
     */
    public function testGetPostTypesNoPrivilege()
    {
        $postTypes = $this->guestClient->getPostTypes();
        $this->assertEmpty($postTypes);
    }

    /**
     * @vcr posts/test-get-post-formats-vcr.yml
     */
    public function testGetPostFormats()
    {
        $postFormats = $this->client->getPostFormats();
        $this->assertArrayHasKey('standard', $postFormats);
        $this->assertSame('Link', $postFormats['link']);
    }

    /**
     * @vcr posts/test-get-post-formats-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed access to details about this site.
     */
    public function testGetPostFormatsNoPrivilege()
    {
        $postFormats = $this->guestClient->getPostFormats();
    }

    /**
     * @vcr posts/test-get-post-status-list-vcr.yml
     */
    public function testGetPostStatusList()
    {
        $statuses = $this->client->getPostStatusList();
        $this->assertGreaterThan(0, count($statuses));
        $this->assertArrayHasKey('publish', $statuses);
        $this->assertSame('Pending Review', $statuses['pending']);
    }

    /**
     * @vcr posts/test-get-post-status-list-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed access to details about this site.
     */
    public function testGetPostStatusListNoPrivilege()
    {
        $statuses = $this->guestClient->getPostStatusList();
    }

    #
    # Test taxonomies API

    #
	
	/**
     * @vcr taxonomies/test-get-taxonomy-vcr.yml
     */
    public function testGetTaxonomy()
    {
        $taxonomy = $this->client->getTaxonomy('category');
        $this->assertArrayHasKey('name', $taxonomy);
        $this->assertArrayHasKey('label', $taxonomy);
        $this->assertArrayHasKey('hierarchical', $taxonomy);
        $this->assertArrayHasKey('public', $taxonomy);
        $this->assertArrayHasKey('show_ui', $taxonomy);
        $this->assertArrayHasKey('_builtin', $taxonomy);
        $this->assertArrayHasKey('labels', $taxonomy);
        $this->assertArrayHasKey('cap', $taxonomy);
        $this->assertArrayHasKey('object_type', $taxonomy);
    }

    /**
     * @vcr taxonomies/test-get-taxonomy-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You are not allowed to assign terms in this taxonomy.
     */
    public function testGetTaxonomyNoPrivilege()
    {
        $taxonomy = $this->guestClient->getTaxonomy('category');
    }

    /**
     * @vcr taxonomies/test-get-taxonomy-invalid-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid taxonomy
     */
    public function testGetTaxonomyInvalidName()
    {
        $taxonomy = $this->client->getTaxonomy('foo');
    }

    /**
     * @vcr taxonomies/test-get-taxonomies-vcr.yml
     */
    public function testGetTaxonomies()
    {
        $taxonomies = $this->client->getTaxonomies();
        $this->assertGreaterThan(0, count($taxonomies));
        $this->assertArrayHasKey('name', $taxonomies[0]);
        $this->assertArrayHasKey('label', $taxonomies[0]);
        $this->assertArrayHasKey('hierarchical', $taxonomies[0]);
        $this->assertArrayHasKey('public', $taxonomies[0]);
        $this->assertArrayHasKey('show_ui', $taxonomies[0]);
        $this->assertArrayHasKey('_builtin', $taxonomies[0]);
        $this->assertArrayHasKey('labels', $taxonomies[0]);
        $this->assertArrayHasKey('cap', $taxonomies[0]);
        $this->assertArrayHasKey('object_type', $taxonomies[0]);
    }

    /**
     * @vcr taxonomies/test-get-taxonomies-no-privilege-vcr.yml
     */
    public function testGetTaxonomiesNoPrivilege()
    {
        $taxonomies = $this->guestClient->getTaxonomies();
        $this->assertEmpty($taxonomies);
    }

    /**
     * @vcr taxonomies/test-get-terms-vcr.yml
     */
    public function testGetTerms()
    {
        $terms = $this->client->getTerms('category');
        $this->assertGreaterThan(0, count($terms));
        $this->assertArrayHasKey('term_id', $terms[0]);
        $this->assertArrayHasKey('name', $terms[0]);
        $this->assertArrayHasKey('slug', $terms[0]);
        $this->assertArrayHasKey('term_group', $terms[0]);
        $this->assertArrayHasKey('term_taxonomy_id', $terms[0]);
        $this->assertArrayHasKey('taxonomy', $terms[0]);
        $this->assertArrayHasKey('description', $terms[0]);
        $this->assertArrayHasKey('parent', $terms[0]);
        $this->assertArrayHasKey('count', $terms[0]);
    }

    /**
     * @vcr taxonomies/test-get-terms-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You are not allowed to assign terms in this taxonomy.
     */
    public function testGetTermsNoPrivilege()
    {
        $terms = $this->guestClient->getTerms('post_tag');
    }

    /**
     * @vcr taxonomies/test-get-terms-invalid-taxonomy-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid taxonomy
     */
    public function testGetTermsInvalidTaxonomyName()
    {
        $terms = $this->client->getTerms('foo');
    }

    /**
     * @vcr taxonomies/test-get-term-vcr.yml
     */
    public function testGetTerm()
    {
        $terms = $this->client->getTerms('category', array('number' => 1));
        $term  = $this->client->getTerm($terms[0]['term_id'], 'category');
        $this->assertArrayHasKey('term_id', $term);
        $this->assertArrayHasKey('name', $term);
        $this->assertArrayHasKey('slug', $term);
        $this->assertArrayHasKey('term_group', $term);
        $this->assertArrayHasKey('term_taxonomy_id', $term);
        $this->assertArrayHasKey('taxonomy', $term);
        $this->assertArrayHasKey('description', $term);
        $this->assertArrayHasKey('parent', $term);
        $this->assertArrayHasKey('count', $term);
    }

    /**
     * @vcr taxonomies/test-get-term-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You are not allowed to assign terms in this taxonomy.
     */
    public function testGetTermNoPrivilege()
    {
        $term = $this->guestClient->getTerm(23, 'category');
    }

    /**
     * @vcr taxonomies/test-get-term-invalid-taxonomy-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid taxonomy
     */
    public function testGetTermInvalidTaxonomyName()
    {
        $term = $this->client->getTerm(-1, 'foo');
    }

    /**
     * @vcr taxonomies/test-get-term-invalid-term-id-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid term ID
     */
    public function testGetTermInvalidTermId()
    {
        $term = $this->client->getTerm(-1, 'category');
    }

    /**
     * @vcr taxonomies/test-new-term-vcr.yml
     */
    public function testNewTerm()
    {
        $termId = (int) $this->client->newTerm('Category Lorem Ipsum', 'category');
        $this->assertGreaterThan(0, $termId);

        $term = $this->client->getTerm($termId, 'category');
        $this->assertSame('Category Lorem Ipsum', $term['name']);
    }

    /**
     * @vcr taxonomies/test-new-term-with-more-info-vcr.yml
     */
    public function testNewTermWithMoreInfo()
    {
        $termId = (int) $this->client->newTerm('Category Lorem', 'category', 'cat-lorem', 'Lorem Ipsum');
        $this->assertGreaterThan(0, $termId);

        $term = $this->client->getTerm($termId, 'category');
        $this->assertSame('Category Lorem', $term['name']);
        $this->assertSame('cat-lorem', $term['slug']);
        $this->assertSame('Lorem Ipsum', $term['description']);
    }

    /**
     * @vcr taxonomies/test-new-term-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You are not allowed to create terms in this taxonomy.
     */
    public function testNewTermNoPrivilege()
    {
        $termId = $this->guestClient->newTerm('foo', 'category');
    }

    /**
     * @vcr taxonomies/test-new-term-invalid-taxonomy-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid taxonomy
     */
    public function testNewTermInvalidTaxonomyName()
    {
        $termId = $this->client->newTerm('foo', 'category-foo');
    }

    /**
     * @vcr taxonomies/test-new-term-empty-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage The term name cannot be empty.
     */
    public function testNewTermEmptyName()
    {
        $termId = $this->client->newTerm('', 'category');
    }

    /**
     * @vcr taxonomies/test-new-term-no-hierachical-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage This taxonomy is not hierarchical.
     */
    public function testNewTermNoHierachical()
    {
        $tagId  = $this->client->newTerm('Tag bar', 'post_tag');
        $termId = $this->client->newTerm('Tag Foo', 'post_tag', null, null, $tagId);
    }

    /**
     * @vcr taxonomies/test-new-term-invalid-parent-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Parent term does not exist.
     */
    public function testNewTermInvalidParent()
    {
        $termId = $this->client->newTerm('Tag Foo', 'category', null, null, 999);
    }

    /**
     * @vcr taxonomies/test-edit-term-vcr.yml
     */
    public function testEditTerm()
    {
        $termId = $this->client->newTerm('Created to delete', 'category');
        $this->assertGreaterThan(0, (int) $termId);
        $result = $this->client->EditTerm($termId, 'category', array('name' => 'Category Lorem 2',));
        $this->assertTrue($result);

        $term = $this->client->getTerm($termId, 'category');
        $this->assertSame('Category Lorem 2', $term['name']);
    }

    /**
     * @vcr taxonomies/test-edit-term-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You are not allowed to edit terms in this taxonomy.
     */
    public function testEditTermNoPrivilege()
    {
        $terms  = $this->client->getTerms('category', array('number' => 1));
        $this->assertNotEmpty($terms);
        $result = $this->guestClient->EditTerm($terms[0]['term_id'], 'category');
    }

    /**
     * @vcr taxonomies/test-edit-term-invalid-taxonomy-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid taxonomy
     */
    public function testEditTermInvalidTaxonomyName()
    {
        $termId = $this->client->EditTerm(47, 'category-foo');
    }

    /**
     * @vcr taxonomies/test-edit-term-empty-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage The term name cannot be empty.
     */
    public function testEditTermEmptyName()
    {
        $terms  = $this->client->getTerms('category', array('number' => 1));
        $this->assertNotEmpty($terms);
        $result = $this->client->EditTerm($terms[0]['term_id'], 'category', array('name' => ''));
    }

    /**
     * @vcr taxonomies/test-edit-term-invalid-parent-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Parent term does not exist.
     */
    public function testEditTermInvalidParent()
    {
        $terms  = $this->client->getTerms('category', array('number' => 1));
        $this->assertNotEmpty($terms);
        $result = $this->client->EditTerm($terms[0]['term_id'], 'category', array('parent' => 999));
    }

    /**
     * @vcr taxonomies/test-edit-term-not-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid term ID
     */
    public function testEditTermNotExist()
    {
        $termId = $this->client->EditTerm(444, 'category');
    }

    /**
     * @vcr taxonomies/test-delete-term-vcr.yml
     */
    public function testDeleteTerm()
    {
        $termId = $this->client->newTerm('Deleted term', 'category');
        $result = $this->client->deleteTerm($termId, 'category');
        $this->assertTrue($result);
    }

    /**
     * @vcr taxonomies/test-delete-term-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You are not allowed to delete terms in this taxonomy.
     */
    public function testDeleteTermNoPrivilege()
    {
        $terms  = $this->client->getTerms('category', array('number' => 1));
        $this->assertNotEmpty($terms);
        $termId = $this->guestClient->DeleteTerm($terms[0]['term_id'], 'category');
    }

    /**
     * @vcr taxonomies/test-delete-term-invalid-taxonomy-name-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage Invalid taxonomy
     */
    public function testDeleteTermInvalidTaxonomyName()
    {
        $result = $this->client->deleteTerm(28, 'category-foo');
    }

    /**
     * @vcr taxonomies/test-delete-term-not-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid term ID
     */
    public function testDeleteTermNotExist()
    {
        $termId = $this->client->deleteTerm(444, 'category');
    }

    #
    # Test media API

    #
	
	/**
     * @vcr media/test-upload-file-vcr.yml
     */
    public function testUploadFile()
    {
        $content = file_get_contents("tests/image.jpg");
        $mime    = mime_content_type("tests/image.jpg");
        $file    = $this->client->uploadFile('foo image.jpg', $mime, $content);
        $this->assertArrayHasKey('id', $file);
        $this->assertArrayHasKey('file', $file);
        $this->assertArrayHasKey('url', $file);
        $this->assertArrayHasKey('type', $file);
    }

    /**
     * @vcr media/test-upload-file-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage You do not have permission to upload files.
     */
    public function testUploadFileNoPrivilege()
    {
        $file = $this->guestClient->uploadFile('Foo', 'image/jpeg', 'file_content');
    }

    /**
     * @vcr media/test-upload-file-error-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Could not write file Foo (Invalid file type)
     */
    public function testUploadFileError()
    {
        $file = $this->client->uploadFile('Foo', 'bar', '');
    }

    /**
     * @vcr media/test-get-media-item-vcr.yml
     */
    public function testGetMediaItem()
    {
        $content = file_get_contents("tests/image.jpg");
        $mime    = mime_content_type("tests/image.jpg");
        $file    = $this->client->uploadFile('foo image 1.jpg', $mime, $content);
        $media   = $this->client->getMediaItem($file['id']);
        $this->assertArrayHasKey('attachment_id', $media);
        $this->assertArrayHasKey('date_created_gmt', $media);
        $this->assertArrayHasKey('parent', $media);
        $this->assertArrayHasKey('link', $media);
        $this->assertArrayHasKey('title', $media);
        $this->assertArrayHasKey('caption', $media);
        $this->assertArrayHasKey('description', $media);
        $this->assertArrayHasKey('metadata', $media);
        $this->assertArrayHasKey('thumbnail', $media);
    }

    /**
     * @vcr media/test-get-media-item-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You do not have permission to upload files.
     */
    public function testGetMediaItemNoPrivilege()
    {
        $media = $this->guestClient->getMediaItem(229);
    }

    /**
     * @vcr media/test-get-media-item-no-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid attachment ID.
     */
    public function testGetMediaItemNoExist()
    {
        $media = $this->client->getMediaItem(999);
    }

    /**
     * @vcr media/test-get-media-library-vcr.yml
     */
    public function testGetMediaLibrary()
    {
        $content = file_get_contents("tests/image.jpg");
        $mime    = mime_content_type("tests/image.jpg");
        $file    = $this->client->uploadFile('foo image 2.jpg', $mime, $content);
        $medias  = $this->client->getMediaLibrary();
        $this->assertNotEmpty($medias);
        $this->assertArrayHasKey('attachment_id', $medias[0]);
        $this->assertArrayHasKey('date_created_gmt', $medias[0]);
        $this->assertArrayHasKey('parent', $medias[0]);
        $this->assertArrayHasKey('link', $medias[0]);
        $this->assertArrayHasKey('title', $medias[0]);
        $this->assertArrayHasKey('caption', $medias[0]);
        $this->assertArrayHasKey('description', $medias[0]);
        $this->assertArrayHasKey('metadata', $medias[0]);
        $this->assertArrayHasKey('thumbnail', $medias[0]);
    }

    /**
     * @vcr media/test-upload-file-with-attachment-vcr.yml
     */
    public function testUploadFileWithAttachment()
    {
        $post    = $this->client->newPost('Attachment post', '');
        $content = file_get_contents("tests/image.jpg");
        $mime    = mime_content_type("tests/image.jpg");
        $file    = $this->client->uploadFile('baz image.jpg', $mime, $content, null, $post);
        $this->assertArrayHasKey('id', $file);
        $this->assertArrayHasKey('file', $file);
        $this->assertArrayHasKey('url', $file);
        $this->assertArrayHasKey('type', $file);

        $file = $this->client->getMediaItem($file['id']);
        $this->assertEquals($post, $file['parent']);
    }

    /**
     * @vcr media/test-get-media-library-with-filter-vcr.yml
     */
    public function testGetMediaLibraryWithFilter()
    {
        $medias = $this->client->getMediaLibrary(array('number' => 5));
        $this->assertLessThanOrEqual(5, count($medias));
    }

    /**
     * @vcr media/test-get-media-library-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedException 401
     * @expectedExceptionMessage You do not have permission to upload files.
     */
    public function testGetMediaLibraryNoPrivilege()
    {
        $medias = $this->guestClient->getMediaLibrary();
    }

    #
    # Test option API

    #
	
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
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed to update options.
     */
    public function testSetOptionsNoPrivilege()
    {
        $result = $this->guestClient->setOptions(array('thumbnail_size_w' => 1000));
    }

    #
    # Test comments API

    #
	
	/**
     * @vcr comments/test-new-comment-vcr.yml
     */
    public function testNewComment()
    {
        $posts     = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $commentId = $this->client->newComment($posts[0]['post_id'], array('content' => 'Lorem ipsum 123'));
        $this->assertGreaterThan(0, (int) $commentId);
    }

    /**
     * @vcr comments/test-new-conmment-no-post-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid post ID.
     */
    public function testNewCommentNoPostExist()
    {
        $commentId = $this->client->newComment(1000, array('content' => 'First comment'));
    }

    /**
     * @vcr comments/test-get-comment-count-vcr.yml
     */
    public function testGetCommentCount()
    {
        $posts     = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $commentId = $this->client->newComment($posts[0]['post_id'], array('content' => 'Lorem ipsum 123 abc'));
        $count     = $this->client->getCommentCount($posts[0]['post_id']);
        $this->assertArrayHasKey('approved', $count);
        $this->assertArrayHasKey('awaiting_moderation', $count);
        $this->assertArrayHasKey('spam', $count);
        $this->assertArrayHasKey('total_comments', $count);
    }

    /**
     * @vcr comments/test-get-comment-count-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed access to details about comments.
     */
    public function testGetCommentCountNoPrivilege()
    {
        $posts = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $count = $this->guestClient->getCommentCount(1);
    }

    /**
     * @vcr comments/test-get-comment-vcr.yml
     */
    public function testGetComment()
    {
        $posts     = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $commentId = $this->client->newComment($posts[0]['post_id'], array('content' => 'Defacto 456'));
        $comment   = $this->client->getComment($commentId);
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
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed to moderate comments on this site.
     */
    public function testGetCommentNoPrivilege()
    {
        $comment = $this->guestClient->getComment(1);
    }

    /**
     * @vcr comments/test-get-comment-not-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid comment ID.
     */
    public function testGetCommentNotExist()
    {
        $comment = $this->client->getComment(-1);
    }

    /**
     * @vcr comments/test-get-comment-no-filter-vcr.yml
     */
    public function testGetCommentsNoFilter()
    {
        $posts     = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $commentId = $this->client->newComment($posts[0]['post_id'], array('content' => 'Defacto 456 xyz!!!'));
        $comments  = $this->client->getComments();
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
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you cannot edit comments.
     */
    public function testGetCommentsNoPrivilege()
    {
        $comments = $this->guestClient->getComments();
    }

    /**
     * @vcr comments/test-edit-comment-vcr.yml
     */
    public function testEditComment()
    {
        $posts     = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $commentId = $this->client->newComment($posts[0]['post_id'], array('content' => 'A comment to be edit'));
        $this->assertGreaterThan(0, (int) $commentId);
        $result    = $this->client->editComment($commentId, array('content' => 'I have editted this comment!'));
        $this->assertTrue($result);
        $comment   = $this->client->getComment($commentId);
        $this->assertSame('I have editted this comment!', $comment['content']);
    }

    /**
     * @vcr comments/test-edit-comment-not-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid comment ID.
     */
    public function testEditCommentNotExist()
    {
        $result = $this->client->editComment(-1, array('content' => 'I have editted this comment!'));
    }

    /**
     * @vcr comments/test-edit-comment-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed to moderate comments on this site.
     */
    public function testEditCommentNoPrivilege()
    {
        $result = $this->guestClient->editComment(1, array('content' => 'I have editted this comment!'));
    }

    /**
     * @vcr comments/test-delete-comment-vcr.yml
     */
    public function testDeleteComment()
    {
        $posts     = $this->client->getPosts(array('number' => 1));
        $this->assertNotEmpty($posts);
        $commentId = $this->client->newComment($posts[0]['post_id'], array('content' => 'A comment to be edit'));
        $this->assertGreaterThan(0, (int) $commentId);
        $result    = $this->client->deleteComment($commentId);
        $this->assertTrue($result);
        $comment   = $this->client->getComment($commentId);
        $this->assertSame('trash', $comment['status']);
    }

    /**
     * @vcr comments/test-delete-comment-not-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid comment ID.
     */
    public function testDeleteCommentNotExist()
    {
        $result = $this->client->deleteComment(-1);
    }

    /**
     * @vcr comments/test-delete-comment-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage You are not allowed to moderate comments on this site.
     */
    public function testDeleteCommentNoPrivilege()
    {
        $result = $this->guestClient->deleteComment(1);
    }

    /**
     * @vcr comments/test-get-comment-status-list-vcr.yml
     */
    public function testGetCommentStatusList()
    {
        $statuses = $this->client->getCommentStatusList();
        $this->assertGreaterThan(0, count($statuses));
    }

    #
    # Test user API

    #
	
	/**
     * @vcr users/test-get-profile-vcr.yml
     */
    public function testGetProfile()
    {
        $user = $this->client->getProfile();
        $this->assertArrayHasKey('user_id', $user);
        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('last_name', $user);
        $this->assertArrayHasKey('bio', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('nickname', $user);
        $this->assertArrayHasKey('nicename', $user);
        $this->assertArrayHasKey('url', $user);
        $this->assertArrayHasKey('display_name', $user);
        $this->assertArrayHasKey('registered', $user);
        $this->assertArrayHasKey('roles', $user);

        $user = $this->client->getProfile(array('user_id', 'email'));
        $this->assertArrayHasKey('user_id', $user);
        $this->assertArrayNotHasKey('username', $user);
        $this->assertArrayNotHasKey('first_name', $user);
        $this->assertArrayNotHasKey('last_name', $user);
        $this->assertArrayNotHasKey('bio', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayNotHasKey('nickname', $user);
        $this->assertArrayNotHasKey('nicename', $user);
        $this->assertArrayNotHasKey('url', $user);
        $this->assertArrayNotHasKey('display_name', $user);
        $this->assertArrayNotHasKey('registered', $user);
        $this->assertArrayNotHasKey('roles', $user);
    }

    /**
     * @vcr users/test-get-users-blogs-vcr.yml
     */
    public function testGetUsersBlogs()
    {
        $result = $this->client->getUsersBlogs();
        $this->assertGreaterThan(0, count($result));
        $this->assertArrayHasKey('blogid', head($result));
        $this->assertArrayHasKey('blogName', head($result));
        $this->assertArrayHasKey('url', head($result));
        $this->assertArrayHasKey('xmlrpc', head($result));
        $this->assertArrayHasKey('isAdmin', head($result));
    }

    /**
     * @vcr users/test-get-user-no-privilege-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you cannot edit users.
     */
    public function testGetUserNoPrivilege()
    {
        $profile = $this->client->getProfile();
        $user    = $this->guestClient->getUser($profile['user_id']);
    }

    /**
     * @vcr users/test-get-user-not-exist-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Invalid user ID
     */
    public function testGetUserNotExist()
    {
        $user = $this->client->getUser(-1);
    }

    /**
     * @vcr users/test-get-user-vcr.yml
     */
    public function testGetUser()
    {
        $profile = $this->client->getProfile();
        $user    = $this->client->getUser($profile['user_id']);
        $this->assertArrayHasKey('user_id', $user);
        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('last_name', $user);
        $this->assertArrayHasKey('bio', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('nickname', $user);
        $this->assertArrayHasKey('nicename', $user);
        $this->assertArrayHasKey('url', $user);
        $this->assertArrayHasKey('display_name', $user);
        $this->assertArrayHasKey('registered', $user);
        $this->assertArrayHasKey('roles', $user);

        $user = $this->client->getUser($profile['user_id'], array('user_id', 'email'));
        $this->assertArrayHasKey('user_id', $user);
        $this->assertArrayNotHasKey('username', $user);
        $this->assertArrayNotHasKey('first_name', $user);
        $this->assertArrayNotHasKey('last_name', $user);
        $this->assertArrayNotHasKey('bio', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayNotHasKey('nickname', $user);
        $this->assertArrayNotHasKey('nicename', $user);
        $this->assertArrayNotHasKey('url', $user);
        $this->assertArrayNotHasKey('display_name', $user);
        $this->assertArrayNotHasKey('registered', $user);
        $this->assertArrayNotHasKey('roles', $user);
    }

    /**
     * @vcr users/test-get-users-no-privilege-vcr.yml
     * @expectedException \HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 401
     * @expectedExceptionMessage Sorry, you cannot list users.
     */
    public function testGetUsersNoPrivilege()
    {
        $users = $this->guestClient->getUsers();
    }

    /**
     * @vcr users/test-get-users-vcr.yml
     */
    public function testGetUsers()
    {
        $users = $this->client->getUsers();
        $this->assertGreaterThan(0, count($users));
        $this->assertArrayHasKey('user_id', $users[0]);
        $this->assertArrayHasKey('username', $users[0]);
        $this->assertArrayHasKey('first_name', $users[0]);
        $this->assertArrayHasKey('last_name', $users[0]);
        $this->assertArrayHasKey('bio', $users[0]);
        $this->assertArrayHasKey('email', $users[0]);
        $this->assertArrayHasKey('nickname', $users[0]);
        $this->assertArrayHasKey('nicename', $users[0]);
        $this->assertArrayHasKey('url', $users[0]);
        $this->assertArrayHasKey('display_name', $users[0]);
        $this->assertArrayHasKey('registered', $users[0]);
        $this->assertArrayHasKey('roles', $users[0]);

        $users = $this->client->getUsers(array(), array('user_id', 'email'));
        $this->assertGreaterThan(0, count($users));
        $this->assertArrayHasKey('user_id', $users[0]);
        $this->assertArrayNotHasKey('username', $users[0]);
        $this->assertArrayNotHasKey('first_name', $users[0]);
        $this->assertArrayNotHasKey('last_name', $users[0]);
        $this->assertArrayNotHasKey('bio', $users[0]);
        $this->assertArrayHasKey('email', $users[0]);
        $this->assertArrayNotHasKey('nickname', $users[0]);
        $this->assertArrayNotHasKey('nicename', $users[0]);
        $this->assertArrayNotHasKey('url', $users[0]);
        $this->assertArrayNotHasKey('display_name', $users[0]);
        $this->assertArrayNotHasKey('registered', $users[0]);
        $this->assertArrayNotHasKey('roles', $users[0]);
    }

    /**
     * @vcr users/test-get-users-invalid-role-vcr.yml
     * @expectedException HieuLe\WordpressXmlrpcClient\Exception\XmlrpcException
     * @expectedExceptionCode 403
     * @expectedExceptionMessage The role specified is not valid
     */
    public function testGetUsersInvalidRole()
    {
        $users = $this->client->getUsers(array('role' => 'foo'));
    }

    /**
     * @vcr users/test-edit-profile-vcr.yml
     */
    public function testEditProfile()
    {
        $result = $this->client->editProfile(array('nickname' => 'JD'));
        $this->assertTrue($result);
        $user   = $this->client->getProfile();
        $this->assertSame('JD', $user['nickname']);
    }

    public function testUserAgent()
    {
        $xmlrpcClient     = new HieuLe\WordpressXmlrpcClient\WordpressClient();
        $defaultUserAgent = $xmlrpcClient->getDefaultUserAgent();
        $this->assertNotEmpty($xmlrpcClient->getUserAgent());

        $cutomUserAgent = "XML-RPC client";
        $xmlrpcClient->setUserAgent($cutomUserAgent);
        $this->assertSame($cutomUserAgent, $xmlrpcClient->getUserAgent());

        $xmlrpcClient->setUserAgent(false);
        $this->assertSame($defaultUserAgent, $xmlrpcClient->getUserAgent());
    }

    public function testErrorCallbacks()
    {
        $xmlrpcClient = new HieuLe\WordpressXmlrpcClient\WordpressClient();
        $error        = array();
        $xmlrpcClient->onError(function($e, $event) use (&$error) {
            $error['e'] = $e;
            $error['event'] = $event;
        });
        
        try
        {
           $xmlrpcClient->getProfile();
        }
        catch (Exception $ex)
        {
            $this->assertArrayHasKey('e', $error);
            $this->assertArrayHasKey('event', $error);
            $this->assertArrayHasKey('endpoint', $error['event']);
            return;
        }
        
        $xmlrpcClient->onError(function($e, $event) use (&$error) {
            $error['e'] = 1;
        });
        
        try
        {
           $xmlrpcClient->getProfile();
        }
        catch (Exception $ex)
        {
            $this->assertSame(1, $error['e']);
            return;
        }
        $this->fail('Error callbacks not called');
    }
    
    /**
     * @vcr test-on-sending-callbacks-vcr.yml
     */
    public function testOnSendingCallbacks()
    {
        $xmlrpcClient = new HieuLe\WordpressXmlrpcClient\WordpressClient(static::$_endpoint);
        $log        = array();
        $xmlrpcClient->onSending(function($event) use (&$log) {
            $log[0] = $event;
        });
        
        try
        {
           $xmlrpcClient->getProfile();
        }
        catch (Exception $ex)
        {
            $this->assertArrayHasKey('event', $log[0]);
            $this->assertArrayHasKey('endpoint', $log[0]);
            $this->assertArrayHasKey('username', $log[0]);
            $this->assertArrayHasKey('password', $log[0]);
            $this->assertArrayHasKey('method', $log[0]);
            $this->assertArrayHasKey('params', $log[0]);
            return;
        }
        
        $this->fail('Sending callbacks not called');
    }

}

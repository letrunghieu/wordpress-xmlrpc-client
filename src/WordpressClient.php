<?php

namespace HieuLe\WordpressXmlrpcClient;

/**
 * A XML-RPC client that implement the {@link http://codex.wordpress.org/XML-RPC_WordPress_API Wordpress API}.
 *
 * @version 2.5.0
 *
 * @author  Hieu Le <http://www.hieule.info>
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
class WordpressClient
{

    private $username;
    private $password;
    private $endPoint;
    private $request;
    private $responseHeader = array();
    private $error;
    private $proxyConfig = false;
    private $authConfig = false;
    private $userAgent;

    /**
     * Event custom callbacks
     */
    private $_callbacks = array();

    /**
     * Create a new client
     *
     * @param string                 $xmlrpcEndPoint The wordpress XML-RPC endpoint (optional)
     * @param string                 $username       The client's username (optional)
     * @param string                 $password       The client's password (optional)
     * @param \Illuminate\Log\Writer $logger         deprecated. This variable will not be used since 2.4.0
     */
    public function __construct($xmlrpcEndPoint = null, $username = null, $password = null, $logger = null)
    {
        $this->setCredentials($xmlrpcEndPoint, $username, $password);
        $this->userAgent = $this->getDefaultUserAgent();
    }

    /**
     * Add a callback for `error` event
     *
     * @param Closure $callback
     *
     * @since 2.4.0
     */
    function onError($callback)
    {
        $this->_callbacks['error'][] = $callback;
    }

    /**
     * Add a callback for `sending` event
     *
     * @param Closure $callback
     *
     * @since 2.4.0
     */
    function onSending($callback)
    {
        $this->_callbacks['sending'][] = $callback;
    }

    /**
     * Set the endpoint, username and password for next requests
     *
     * @param string $xmlrpcEndPoint The wordpress XML-RPC endpoint
     * @param string $username       The client's username
     * @param string $password       The client's password
     *
     * @since 2.4.0
     */
    function setCredentials($xmlrpcEndPoint, $username, $password)
    {
        // prepend http protocol to the end point if needed
        $scheme = parse_url($xmlrpcEndPoint, PHP_URL_SCHEME);
        if (!$scheme) {
            $xmlrpcEndPoint = "http://{$xmlrpcEndPoint}";
        }

        // swith to https when working with wordpress.com blogs
        $host = parse_url($xmlrpcEndPoint, PHP_URL_HOST);
        if (substr($host, -14) == '.wordpress.com') {
            $xmlrpcEndPoint = preg_replace('|http://|', 'https://', $xmlrpcEndPoint, 1);
        }

        // save information
        $this->endPoint = $xmlrpcEndPoint;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the current endpoint URL
     *
     * @return string
     *
     * @since 2.4.2
     */
    function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * Get library default user agent
     *
     * @return string
     *
     * @since 2.4.0
     */
    function getDefaultUserAgent()
    {
        $phpVersion  = phpversion();
        $curlVersion = curl_version();

        return "XML-RPC client (hieu-le/wordpress-xmlrpc-client 2.4.0) PHP {$phpVersion} cUrl {$curlVersion['version']}";
    }

    /**
     * Get current user agent string
     *
     * @return string
     *
     * @since 2.4.0
     */
    function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set the user agent for next requests
     *
     * @param string $userAgent custom user agent, give a falsy value to use library user agent
     *
     * @since 2.4.0
     */
    function setUserAgent($userAgent)
    {
        if ($userAgent) {
            $this->userAgent = $userAgent;
        } else {
            $this->userAgent = $this->getDefaultUserAgent();
        }
    }

    /**
     * Get the latest error message
     *
     * @return string
     *
     * @since 2.2
     */
    function getErrorMessage()
    {
        return $this->error;
    }

    /**
     * Set the proxy config. To disable proxy, using <code>false</code> as parameter
     *
     * @param boolean|array $proxyConfig The configuration array has these fields:
     *                                   <ul>
     *                                   <li><code>proxy_ip</code>: the ip of the proxy server (WITHOUT port)</li>
     *                                   <li><code>proxy_port</code>: the port of the proxy server</li>
     *                                   <li><code>proxy_user</code>: the username for proxy authorization</li>
     *                                   <li><code>proxy_pass</code>: the password for proxy authorization</li>
     *                                   <li><code>proxy_mode</code>: value for CURLOPT_PROXYAUTH option (default to
     *                                   CURLAUTH_BASIC)</li>
     *                                   </ul>
     *
     * @throws \InvalidArgumentException
     * @see   curl_setopt
     * @since 2.2
     */
    function setProxy($proxyConfig)
    {
        if ($proxyConfig === false || is_array($proxyConfig)) {
            $this->proxyConfig = $proxyConfig;
        } else {
            throw new \InvalidArgumentException(__METHOD__ . " only accept boolean 'false' or an array as parameter.");
        }
    }

    /**
     * Get current proxy config
     *
     * @return boolean|array
     * @since 2.2
     */
    function getProxy()
    {
        return $this->proxyConfig;
    }

    /**
     * Set authentication info
     *
     * @param boolean|array $authConfig the configuation array
     *                                  <ul>
     *                                  <li><code>auth_user</code>: the username for server authentication</li>
     *                                  <li><code>auth_pass</code>: the password for server authentication</li>
     *                                  <li><code>auth_mode</code>: value for CURLOPT_HTTPAUTH option (default to
     *                                  CURLAUTH_BASIC)</li>
     *                                  </ul>
     *
     * @throws \InvalidArgumentException
     * @see   curl_setopt
     * @since 2.2
     */
    function setAuth($authConfig)
    {
        if ($authConfig === false || is_array($authConfig)) {
            $this->authConfig = $authConfig;
        } else {
            throw new \InvalidArgumentException(__METHOD__ . " only accept boolean 'false' or an array as parameter.");
        }
    }

    /**
     * Get the current HTTP authentication config
     *
     * @return type
     * @since 2.2
     */
    function getAuth()
    {
        return $this->authConfig;
    }

    /**
     * Retrieve a post of any registered post type.
     *
     * @param integer $postId the id of selected post
     * @param array   $fields (optional) list of field or meta-field names to include in response.
     *
     * @return array
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPosts
     */
    function getPost($postId, array $fields = array())
    {
        if (empty($fields)) {
            $params = array(1, $this->username, $this->password, $postId);
        } else {
            $params = array(1, $this->username, $this->password, $postId, $fields);
        }

        return $this->sendRequest('wp.getPost', $params);
    }

    /**
     * Retrieve list of posts of any registered post type.
     *
     * @param array $filters optional
     * @param array $fields  optional
     *
     * @return array array of struct
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPosts
     */
    function getPosts(array $filters = array(), array $fields = array())
    {
        $params = array(1, $this->username, $this->password, $filters);
        if (!empty($fields)) {
            $params[] = $fields;
        }

        return $this->sendRequest('wp.getPosts', $params);
    }

    /**
     * Create a new post of any registered post type.
     *
     * @param string  $title        the post title
     * @param string  $body         the post body
     * @param array   $categorieIds the list of category ids
     * @param integer $thumbnailId  the thumbnail id
     * @param array   $content      the content array, see more at wordpress documentation
     *
     * @return integer the new post id
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.newPost
     */
    function newPost($title, $body, array $content = array())
    {
        $default                 = array(
            'post_type'   => 'post',
            'post_status' => 'publish',
        );
        $content                 = array_merge($default, $content);
        $content['post_title']   = $title;
        $content['post_content'] = $body;

        $params = array(1, $this->username, $this->password, $content);

        return $this->sendRequest('wp.newPost', $params);
    }

    /**
     * Edit an existing post of any registered post type.
     *
     * @param integer $postId       the id of selected post
     * @param string  $title        the new title
     * @param string  $body         the new body
     * @param array   $categorieIds the new list of category ids
     * @param integer $thumbnailId  the new thumbnail id
     * @param array   $content      the advanced array
     *
     * @return boolean
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.editPost
     */
    function editPost($postId, array $content)
    {
        $params = array(1, $this->username, $this->password, $postId, $content);

        return $this->sendRequest('wp.editPost', $params);
    }

    /**
     * Delete an existing post of any registered post type.
     *
     * @param integer $postId the id of selected post
     *
     * @return boolean
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.deletePost
     */
    function deletePost($postId)
    {
        $params = array(1, $this->username, $this->password, $postId);

        return $this->sendRequest('wp.deletePost', $params);
    }

    /**
     * Retrieve a registered post type.
     *
     * @param string $postTypeName the post type name
     * @param array  $fields       (optional) list of field or meta-field names to include in response.
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostType
     */
    function getPostType($postTypeName, array $fields = array())
    {
        $params = array(1, $this->username, $this->password, $postTypeName, $fields);

        return $this->sendRequest('wp.getPostType', $params);
    }

    /**
     * Retrieve list of registered post types.
     *
     * @param array $filter
     * @param array $fields
     *
     * @return array    list of struct
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostTypes
     */
    function getPostTypes(array $filter = array(), array $fields = array())
    {
        $params = array(1, $this->username, $this->password, $filter, $fields);

        return $this->sendRequest('wp.getPostTypes', $params);
    }

    /**
     * Retrieve list of post formats.
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostFormats
     */
    function getPostFormats()
    {
        $params = array(1, $this->username, $this->password);

        return $this->sendRequest('wp.getPostFormats', $params);
    }

    /**
     * Retrieve list of supported values for post_status field on posts.
     *
     * @return array    list of supported post status
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostStatusList
     */
    function getPostStatusList()
    {
        $params = array(1, $this->username, $this->password);

        return $this->sendRequest('wp.getPostStatusList', $params);
    }

    /**
     * Retrieve information about a taxonomy.
     *
     * @param string $taxonomy the name of the selected taxonomy
     *
     * @return array    taxonomy information
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTaxonomy
     */
    function getTaxonomy($taxonomy)
    {
        $params = array(1, $this->username, $this->password, $taxonomy);

        return $this->sendRequest('wp.getTaxonomy', $params);
    }

    /**
     * Retrieve a list of taxonomies.
     *
     * @return array array of taxonomy struct
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTaxonomies
     */
    function getTaxonomies()
    {
        $params = array(1, $this->username, $this->password);

        return $this->sendRequest('wp.getTaxonomies', $params);
    }

    /**
     * Retrieve a taxonomy term.
     *
     * @param integer $termId
     * @param string  $taxonomy
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTerm
     */
    function getTerm($termId, $taxonomy)
    {
        $params = array(1, $this->username, $this->password, $taxonomy, $termId);

        return $this->sendRequest('wp.getTerm', $params);
    }

    /**
     * Retrieve list of terms in a taxonomy.
     *
     * @param string $taxonomy
     * @param array  $filter
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTerms
     */
    function getTerms($taxonomy, array $filter = array())
    {
        $params = array(1, $this->username, $this->password, $taxonomy, $filter);

        return $this->sendRequest('wp.getTerms', $params);
    }

    /**
     * Create a new taxonomy term.
     *
     * @param string  $name
     * @param string  $taxomony
     * @param string  $slug
     * @param string  $description
     * @param integer $parentId
     *
     * @return integer new term id
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.newTerm
     */
    function newTerm($name, $taxomony, $slug = null, $description = null, $parentId = null)
    {
        $content = array(
            'name'     => $name,
            'taxonomy' => $taxomony,
        );
        if ($slug) {
            $content['slug'] = $slug;
        }
        if ($description) {
            $content['description'] = $description;
        }
        if ($parentId) {
            $content['parent'] = $parentId;
        }
        $params = array(1, $this->username, $this->password, $content);

        return $this->sendRequest('wp.newTerm', $params);
    }

    /**
     * Edit an existing taxonomy term.
     *
     * @param integer $termId
     * @param string  $taxonomy
     * @param array   $content
     *
     * @return boolean
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.editTerm
     */
    function editTerm($termId, $taxonomy, array $content = array())
    {
        $content['taxonomy'] = $taxonomy;
        $params              = array(1, $this->username, $this->password, $termId, $content);

        return $this->sendRequest('wp.editTerm', $params);
    }

    /**
     * Delete an existing taxonomy term.
     *
     * @param integer $termId
     * @param string  $taxonomy
     *
     * @return boolean
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.deleteTerm
     */
    function deleteTerm($termId, $taxonomy)
    {
        $params = array(1, $this->username, $this->password, $taxonomy, $termId);

        return $this->sendRequest('wp.deleteTerm', $params);
    }

    /**
     * Retrieve a media item (i.e, attachment).
     *
     * @param integer $itemId
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Media#wp.getMediaItem
     */
    function getMediaItem($itemId)
    {
        $params = array(1, $this->username, $this->password, $itemId);

        return $this->sendRequest('wp.getMediaItem', $params);
    }

    /**
     * Retrieve list of media items.
     *
     * @param array $filter
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Media#wp.getMediaLibrary
     */
    function getMediaLibrary(array $filter = array())
    {
        $params = array(1, $this->username, $this->password, $filter);

        return $this->sendRequest('wp.getMediaLibrary', $params);
    }

    /**
     * Upload a media file.
     *
     * @param string  $name      file name
     * @param string  $mime      file mime type
     * @param string  $bits      binary data (no encoded)
     * @param boolean $overwrite (optional)
     * @param int     $postId    (optional)
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Media#wp.uploadFile
     */
    function uploadFile($name, $mime, $bits, $overwrite = null, $postId = null)
    {
        xmlrpc_set_type($bits, 'base64');
        $struct = array(
            'name' => $name,
            'type' => $mime,
            'bits' => $bits,
        );
        if ($overwrite !== null) {
            $struct['overwrite'] = $overwrite;
        }
        if ($postId !== null) {
            $struct['post_id'] = (int)$postId;
        }
        $params = array(1, $this->username, $this->password, $struct);

        return $this->sendRequest('wp.uploadFile', $params);
    }

    /**
     * Retrieve comment count for a specific post.
     *
     * @param integer $postId
     *
     * @return integer
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getCommentCount
     */
    function getCommentCount($postId)
    {
        $params = array(1, $this->username, $this->password, $postId);

        return $this->sendRequest('wp.getCommentCount', $params);
    }

    /**
     * Retrieve a comment.
     *
     * @param integer $commentId
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getComment
     */
    function getComment($commentId)
    {
        $params = array(1, $this->username, $this->password, $commentId);

        return $this->sendRequest('wp.getComment', $params);
    }

    /**
     * Retrieve list of comments.
     *
     * @param array $filter
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getComments
     */
    function getComments(array $filter = array())
    {
        $params = array(1, $this->username, $this->password, $filter);

        return $this->sendRequest('wp.getComments', $params);
    }

    /**
     * Create a new comment.
     *
     * @param integer $post_id
     * @param array   $comment
     *
     * @return integer new comment_id
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.newComment
     */
    function newComment($post_id, array $comment)
    {
        $params = array(1, $this->username, $this->password, $post_id, $comment);

        return $this->sendRequest('wp.newComment', $params);
    }

    /**
     * Edit an existing comment.
     *
     * @param integer $commentId
     * @param array   $comment
     *
     * @return boolean
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.editComment
     */
    function editComment($commentId, array $comment)
    {
        $params = array(1, $this->username, $this->password, $commentId, $comment);

        return $this->sendRequest('wp.editComment', $params);
    }

    /**
     * Remove an existing comment.
     *
     * @param integer $commentId
     *
     * @return boolean
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.deleteComment
     */
    function deleteComment($commentId)
    {
        $params = array(1, $this->username, $this->password, $commentId);

        return $this->sendRequest('wp.deleteComment', $params);
    }

    /**
     * Retrieve list of comment statuses.
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getCommentStatusList
     */
    function getCommentStatusList()
    {
        $params = array(1, $this->username, $this->password);

        return $this->sendRequest('wp.getCommentStatusList', $params);
    }

    /**
     * Retrieve blog options.
     *
     * @param array $options
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Options#wp.getOptions
     */
    function getOptions(array $options = array())
    {
        if (empty($options)) {
            $params = array(1, $this->username, $this->password);
        } else {
            $params = array(1, $this->username, $this->password, $options);
        }

        return $this->sendRequest('wp.getOptions', $params);
    }

    /**
     * Edit blog options.
     *
     * @param array $options
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Options#wp.setOptions
     */
    function setOptions(array $options)
    {
        $params = array(1, $this->username, $this->password, $options);

        return $this->sendRequest('wp.setOptions', $params);
    }

    /**
     * Retrieve list of blogs for this user.
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getUsersBlogs
     */
    function getUsersBlogs()
    {
        $params = array($this->username, $this->password);

        return $this->sendRequest('wp.getUsersBlogs', $params);
    }

    /**
     * Retrieve a user.
     *
     * @param integer $userId
     * @param array   $fields Optional. List of field or meta-field names to include in response.
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getUser
     */
    function getUser($userId, array $fields = array())
    {
        $params = array(1, $this->username, $this->password, $userId);
        if (!empty($fields)) {
            $params[] = $fields;
        }

        return $this->sendRequest('wp.getUser', $params);
    }

    /**
     * Retrieve list of users.
     *
     * @param array $filters
     * @param array $fields
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getUsers
     */
    function getUsers(array $filters = array(), array $fields = array())
    {
        $params = array(1, $this->username, $this->password, $filters);
        if (!empty($fields)) {
            $params[] = $fields;
        }

        return $this->sendRequest('wp.getUsers', $params);
    }

    /**
     * Retrieve profile of the requesting user.
     *
     * @param array $fields
     *
     * @return array
     *
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getProfile
     */
    function getProfile(array $fields = array())
    {
        $params = array(1, $this->username, $this->password);
        if (!empty($fields)) {
            $params[] = $fields;
        }

        return $this->sendRequest('wp.getProfile', $params);
    }

    /**
     * Edit profile of the requesting user.
     *
     * @param array $content
     *
     * @return boolean
     *
     * http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.editProfile
     */
    function editProfile(array $content)
    {
        $params = array(1, $this->username, $this->password, $content);

        return $this->sendRequest('wp.editProfile', $params);
    }

    /**
     * Call a custom XML-RPC method
     *
     * @param string $method the method name
     * @param array  $params the parameters of this call
     *
     * @return mixed
     *
     * @since 2.3.0
     */
    public function callCustomMethod($method, $params)
    {
        return $this->sendRequest($method, $params);
    }

    /**
     * Create an XMLRPC DateTime object
     *
     * @param \DateTime $datetime
     *
     * @return string with the XMLRPC internal type is set to datetime
     */
    public function createXMLRPCDateTime($datetime)
    {
        $value = $datetime->format('Ymd\TH:i:sO');
        xmlrpc_set_type($value, 'datetime');

        return $value;
    }

    protected function performRequest()
    {
        if (function_exists('curl_init')) {
            return $this->requestWithCurl();
        } else {
            return $this->requestWithFile();
        }
    }

    protected function getRequest()
    {
        return $this->request;
    }

    private function sendRequest($method, $params)
    {
        if (!$this->endPoint) {
            $this->error = "Invalid endpoint " . json_encode(array(
                    'endpoint' => $this->endPoint,
                    'username' => $this->username,
                    'password' => $this->password,
                ));
            $this->logError();
            throw new \Exception($this->error);
        }
        $this->responseHeader = array();

        // This block is used for compatibility with the older version of this package, which run on PHP < 7.0.
        // Since the 2.5.0 version, the datetime must be set explicitly by using the `createXMLRPCDateTime` method.
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            $this->setXmlrpcType($params);
        }

        $this->request = xmlrpc_encode_request($method, $params,
            array('encoding' => 'UTF-8', 'escaping' => 'markup', 'version' => 'xmlrpc'));
        $body          = "";
        // Call sending event callbacks
        $callbacks = $this->getCallback('sending');
        $event     = array(
            'event'    => 'sending',
            'endpoint' => $this->endPoint,
            'username' => $this->username,
            'password' => $this->password,
            'method'   => $method,
            'params'   => $params,
            'request'  => $this->request,
            'proxy'    => $this->proxyConfig,
            'auth'     => $this->authConfig,
        );
        foreach ($callbacks as $callback) {
            call_user_func($callback, $event);
        }
        $body     = $this->performRequest();
        $response = xmlrpc_decode($body, 'UTF-8');
        if (is_array($response) && xmlrpc_is_fault($response)) {
            $this->error = ("xmlrpc: {$response['faultString']} ({$response['faultCode']})");
            $this->logError();
            throw new Exception\XmlrpcException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }

    /**
     * Set the correct type for each element in an array
     *
     * @param array $array
     *
     * @since 2.2
     */
    private function setXmlrpcType(&$array)
    {
        foreach ($array as $key => $element) {
            if (is_a($element, '\DateTime')) {
                $array[$key] = $element->format("Ymd\TH:i:sO");
                xmlrpc_set_type($array[$key], 'datetime');
            } elseif (is_array($element)) {
                $this->setXmlrpcType($array[$key]);
            }
        }
    }

    private function requestWithCurl()
    {
        $ch = curl_init($this->endPoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //Fixes the HTTP/1.1 417 Expectation Failed Bug
        if ($this->proxyConfig != false) {
            if (isset($this->proxyConfig['proxy_ip'])) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxyConfig['proxy_ip']);
            }
            if (isset($this->proxyConfig['proxy_port'])) {
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxyConfig['proxy_port']);
            }
            if (isset($this->proxyConfig['proxy_user']) && isset($this->proxyConfig['proxy_pass'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD,
                    "{$this->proxyConfig['proxy_user']}:{$this->proxyConfig['proxy_pass']}");
            }
            if (isset($this->proxyConfig['proxy_mode'])) {
                curl_setopt($ch, CURLOPT_PROXYAUTH, $this->proxyConfig['proxy_mode']);
            }
        }
        if ($this->authConfig) {
            if (isset($this->authConfig['auth_user']) && isset($this->authConfig['auth_pass'])) {
                curl_setopt($ch, CURLOPT_USERPWD,
                    "{$this->authConfig['auth_user']}:{$this->authConfig['auth_pass']}");
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            }
            if (isset($this->authConfig['auth_mode'])) {
                curl_setopt($ch, CURLOPT_HTTPAUTH, $this->authConfig['auth_mode']);
            }
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $message     = curl_error($ch);
            $code        = curl_errno($ch);
            $this->error = "curl: {$message} ({$code})";
            $this->logError();
            curl_close($ch);
            throw new Exception\NetworkException($message, $code);
        }
        $httpStatusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatusCode >= 400) {
            $message     = $response;
            $code        = $httpStatusCode;
            $this->error = "http: {$message} ({$code})";
            $this->logError();
            curl_close($ch);
            throw new Exception\NetworkException($message, $code);
        }
        curl_close($ch);

        return $response;
    }

    private function requestWithFile()
    {
        $contextOptions = array(
            'http' => array(
                'method'     => "POST",
                'user_agent' => $this->userAgent,
                'header'     => "Content-Type: text/xml\r\n",
                'content'    => $this->request,
            ),
        );

        if ($this->proxyConfig != false) {
            if (isset($this->proxyConfig['proxy_ip']) && isset($this->proxyConfig['proxy_port'])) {
                $contextOptions['http']['proxy']           = "tcp://{$this->proxyConfig['proxy_ip']}:{$this->proxyConfig['proxy_port']}";
                $contextOptions['http']['request_fulluri'] = true;
            }
            if (isset($this->proxyConfig['proxy_user']) && isset($this->proxyConfig['proxy_pass'])) {
                $auth = base64_encode("{$this->proxyConfig['proxy_user']}:{$this->proxyConfig['proxy_pass']}");
                $contextOptions['http']['header'] .= "Proxy-Authorization: Basic {$auth}\r\n";
            }
            if (isset($this->proxyConfig['proxy_mode'])) {
                throw new \InvalidArgumentException('Cannot use NTLM proxy authorization without cURL extension');
            }
        }
        if ($this->authConfig) {
            if (isset($this->authConfig['auth_user']) && isset($this->authConfig['auth_pass'])) {
                $auth = base64_encode("{$this->authConfig['auth_user']}:{$this->authConfig['auth_pass']}");
                $contextOptions['http']['header'] .= "Authorization: Basic {$auth}\r\n";
            }
            if (isset($this->authConfig['auth_mode'])) {
                throw new \InvalidArgumentException('Cannot use other authentication method without cURL extension');
            }
        }
        $context              = stream_context_create($contextOptions);
        $http_response_header = array();
        try {
            $file = @file_get_contents($this->endPoint, false, $context);
            if ($file === false) {
                $error       = error_get_last();
                $error       = $error ? trim($error['message']) : "error";
                $this->error = "file_get_contents: {$error}";
                $this->logError();
                throw new Exception\NetworkException($error, 127);
            }
        } catch (\Exception $ex) {
            $this->error = ("file_get_contents: {$ex->getMessage()} ({$ex->getCode()})");
            $this->logError();
            throw new Exception\NetworkException($ex->getMessage(), $ex->getCode());
        }

        return $file;
    }

    private function logError()
    {
        $callbacks = $this->getCallback('error');
        $event     = array(
            'event'    => 'error',
            'endpoint' => $this->endPoint,
            'request'  => $this->request,
            'proxy'    => $this->proxyConfig,
            'auth'     => $this->authConfig,
        );
        foreach ($callbacks as $callback) {
            call_user_func($callback, $this->error, $event);
        }
    }

    private function getCallback($name)
    {
        $callbacks = array();
        if (isset($this->_callbacks[$name]) && is_array($this->_callbacks[$name])) {
            $callbacks = $this->_callbacks[$name];
        }

        return $callbacks;
    }

}

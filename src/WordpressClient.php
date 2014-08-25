<?php

namespace HieuLe\WordpressXmlrpcClient;

/**
 * A XML-RPC client that implement the {@link http://codex.wordpress.org/XML-RPC_WordPress_API Wordpress API}.
 * 
 * @version 2.4.0
 * 
 * @author Hieu Le <http://www.hieule.info>
 * 
 * @license http://opensource.org/licenses/MIT MIT
 */
class WordpressClient
{

    private $_username;
    private $_password;
    private $_endPoint;
    private $_request;
    private $_responseHeader = array();
    private $_error;
    private $_proxyConfig = false;
    private $_authConfig  = false;
    private $_userAgent;

    /**
     * Event custom callbacks
     */
    private $_callbacks = array();

    /**
     * Create a new client
     * 
     * @param string $xmlrpcEndPoint The wordpress XML-RPC endpoint (optional)
     * @param string $username       The client's username (optional)
     * @param string $password       The client's password (optional)
     * @param \Illuminate\Log\Writer $logger deprecated. This variable will not be used since 2.4.0 
     */
    public function __construct($xmlrpcEndPoint = null, $username = null, $password = null, $logger = null)
    {
        $this->setCredentials($xmlrpcEndPoint, $username, $password);
        $this->_userAgent = $this->getDefaultUserAgent();
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
        $this->_endPoint = $xmlrpcEndPoint;
        $this->_username = $username;
        $this->_password = $password;
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
        $phpVersion = phpversion();
        $curlVersion = curl_version();
        return "XML-RPC client (hieu-le/wordpress-xmlrpc-client 2.4.0) PHP {$phpVersion} cUrl {$curlVersion['version']}";
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
        if ($userAgent)
        {
            $this->_userAgent = $userAgent;
        }
        else
        {
            $this->_userAgent = $this->getDefaultUserAgent();
        }
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
        return $this->_userAgent;
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
        return $this->_error;
    }

    /**
     * Set the proxy config. To disable proxy, using <code>false</code> as parameter
     * 
     * @param boolean|array $proxyConfig The configuration array has these fields:
     * <ul>
     * 	<li><code>proxy_ip</code>: the ip of the proxy server (WITHOUT port)</li>
     * 	<li><code>proxy_port</code>: the port of the proxy server</li>
     * 	<li><code>proxy_user</code>: the username for proxy authorization</li>
     * 	<li><code>proxy_pass</code>: the password for proxy authorization</li>
     * 	<li><code>proxy_mode</code>: value for CURLOPT_PROXYAUTH option (default to CURLAUTH_BASIC)</li>
     * </ul>
     * @throws \InvalidArgumentException
     * @see curl_setopt
     * @since 2.2
     */
    function setProxy($proxyConfig)
    {
        if ($proxyConfig === false || is_array($proxyConfig))
        {
            $this->_proxyConfig = $proxyConfig;
        }
        else
        {
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
        return $this->_proxyConfig;
    }

    /**
     * Set authentication info
     * 
     * @param boolean|array $authConfig the configuation array
     * <ul>
     * 	<li><code>auth_user</code>: the username for server authentication</li>
     * 	<li><code>auth_pass</code>: the password for server authentication</li>
     * 	<li><code>auth_mode</code>: value for CURLOPT_HTTPAUTH option (default to CURLAUTH_BASIC)</li>
     * </ul>
     * @throws \InvalidArgumentException
     * @see curl_setopt
     * @since 2.2
     */
    function setAuth($authConfig)
    {
        if ($authConfig === false || is_array($authConfig))
        {
            $this->_authConfig = $authConfig;
        }
        else
        {
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
        return $this->_authConfig;
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
        if (empty($fields))
        {
            $params = array(1, $this->_username, $this->_password, $postId);
        }
        else
        {
            $params = array(1, $this->_username, $this->_password, $postId, $fields);
        }
        return $this->_sendRequest('wp.getPost', $params);
    }

    /**
     * Retrieve list of posts of any registered post type. 
     * 
     * @param array $filters optional
     * @param array $fields	 optional
     * 
     * @return array array of struct
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPosts
     */
    function getPosts(array $filters = array(), array $fields = array())
    {
        $params = array(1, $this->_username, $this->_password, $filters);
        if (!empty($fields))
        {
            $params[] = $fields;
        }
        return $this->_sendRequest('wp.getPosts', $params);
    }

    /**
     * Create a new post of any registered post type. 
     * 
     * @param string  $title        the post title
     * @param string  $body	        the post body
     * @param array   $categorieIds	the list of category ids
     * @param integer $thumbnailId  the thumbnail id
     * @param array   $content	    the content array, see more at wordpress documentation
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

        $params = array(1, $this->_username, $this->_password, $content);
        return $this->_sendRequest('wp.newPost', $params);
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
        $params = array(1, $this->_username, $this->_password, $postId, $content);
        return $this->_sendRequest('wp.editPost', $params);
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
        $params = array(1, $this->_username, $this->_password, $postId);
        return $this->_sendRequest('wp.deletePost', $params);
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
        $params = array(1, $this->_username, $this->_password, $postTypeName, $fields);
        return $this->_sendRequest('wp.getPostType', $params);
    }

    /**
     * Retrieve list of registered post types. 
     * 
     * @param array $filter
     * @param array $fields
     * 
     * @return array	list of struct
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostTypes
     */
    function getPostTypes(array $filter = array(), array $fields = array())
    {
        $params = array(1, $this->_username, $this->_password, $filter, $fields);
        return $this->_sendRequest('wp.getPostTypes', $params);
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
        $params = array(1, $this->_username, $this->_password);
        return $this->_sendRequest('wp.getPostFormats', $params);
    }

    /**
     * Retrieve list of supported values for post_status field on posts. 
     * 
     * @return array	list of supported post status
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostStatusList
     */
    function getPostStatusList()
    {
        $params = array(1, $this->_username, $this->_password);
        return $this->_sendRequest('wp.getPostStatusList', $params);
    }

    /**
     * Retrieve information about a taxonomy. 
     * 
     * @param string $taxonomy the name of the selected taxonomy
     * 
     * @return array	taxonomy information
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTaxonomy
     */
    function getTaxonomy($taxonomy)
    {
        $params = array(1, $this->_username, $this->_password, $taxonomy);
        return $this->_sendRequest('wp.getTaxonomy', $params);
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
        $params = array(1, $this->_username, $this->_password);
        return $this->_sendRequest('wp.getTaxonomies', $params);
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
        $params = array(1, $this->_username, $this->_password, $taxonomy, $termId);
        return $this->_sendRequest('wp.getTerm', $params);
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
        $params = array(1, $this->_username, $this->_password, $taxonomy, $filter);
        return $this->_sendRequest('wp.getTerms', $params);
    }

    /**
     * Create a new taxonomy term. 
     * 
     * @param string $name
     * @param string $taxomony
     * @param string $slug
     * @param string $description
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
        if ($slug)
        {
            $content['slug'] = $slug;
        }
        if ($description)
        {
            $content['description'] = $description;
        }
        if ($parentId)
        {
            $content['parent'] = $parentId;
        }
        $params = array(1, $this->_username, $this->_password, $content);
        return $this->_sendRequest('wp.newTerm', $params);
    }

    /**
     * Edit an existing taxonomy term. 
     * 
     * @param integer $termId
     * @param string $taxonomy
     * @param array $content
     * 
     * @return boolean
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.editTerm
     */
    function editTerm($termId, $taxonomy, array $content = array())
    {
        $content['taxonomy'] = $taxonomy;
        $params              = array(1, $this->_username, $this->_password, $termId, $content);
        return $this->_sendRequest('wp.editTerm', $params);
    }

    /**
     * Delete an existing taxonomy term. 
     * 
     * @param integer $termId
     * @param string $taxonomy
     * 
     * @return boolean
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.deleteTerm
     */
    function deleteTerm($termId, $taxonomy)
    {
        $params = array(1, $this->_username, $this->_password, $taxonomy, $termId);
        return $this->_sendRequest('wp.deleteTerm', $params);
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
        $params = array(1, $this->_username, $this->_password, $itemId);
        return $this->_sendRequest('wp.getMediaItem', $params);
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
        $params = array(1, $this->_username, $this->_password, $filter);
        return $this->_sendRequest('wp.getMediaLibrary', $params);
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
        if ($overwrite !== null)
        {
            $struct['overwrite'] = $overwrite;
        }
        if ($postId !== null)
        {
            $struct['post_id'] = (int) $postId;
        }
        $params = array(1, $this->_username, $this->_password, $struct);
        return $this->_sendRequest('wp.uploadFile', $params);
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
        $params = array(1, $this->_username, $this->_password, $postId);
        return $this->_sendRequest('wp.getCommentCount', $params);
    }

    /**
     * Retrieve a comment. 
     * 
     * @param integer $commentId
     * @return array
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getComment
     */
    function getComment($commentId)
    {
        $params = array(1, $this->_username, $this->_password, $commentId);
        return $this->_sendRequest('wp.getComment', $params);
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
        $params = array(1, $this->_username, $this->_password, $filter);
        return $this->_sendRequest('wp.getComments', $params);
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
        $params = array(1, $this->_username, $this->_password, $post_id, $comment);
        return $this->_sendRequest('wp.newComment', $params);
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
        $params = array(1, $this->_username, $this->_password, $commentId, $comment);
        return $this->_sendRequest('wp.editComment', $params);
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
        $params = array(1, $this->_username, $this->_password, $commentId);
        return $this->_sendRequest('wp.deleteComment', $params);
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
        $params = array(1, $this->_username, $this->_password);
        return $this->_sendRequest('wp.getCommentStatusList', $params);
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
        if (empty($options))
        {
            $params = array(1, $this->_username, $this->_password);
        }
        else
        {
            $params = array(1, $this->_username, $this->_password, $options);
        }
        return $this->_sendRequest('wp.getOptions', $params);
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
        $params = array(1, $this->_username, $this->_password, $options);
        return $this->_sendRequest('wp.setOptions', $params);
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
        $params = array($this->_username, $this->_password);
        return $this->_sendRequest('wp.getUsersBlogs', $params);
    }

    /**
     * Retrieve a user. 
     * 
     * @param integer $userId
     * @param array $fields Optional. List of field or meta-field names to include in response. 
     * @return array
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getUser
     */
    function getUser($userId, array $fields = array())
    {
        $params = array(1, $this->_username, $this->_password, $userId);
        if (!empty($fields))
        {
            $params[] = $fields;
        }
        return $this->_sendRequest('wp.getUser', $params);
    }

    /**
     * Retrieve list of users. 
     * 
     * @param array $filters
     * @param array $fields
     * @return array
     * 
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getUsers
     */
    function getUsers(array $filters = array(), array $fields = array())
    {
        $params = array(1, $this->_username, $this->_password, $filters);
        if (!empty($fields))
        {
            $params[] = $fields;
        }
        return $this->_sendRequest('wp.getUsers', $params);
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
        $params = array(1, $this->_username, $this->_password);
        if (!empty($fields))
        {
            $params[] = $fields;
        }
        return $this->_sendRequest('wp.getProfile', $params);
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
        $params = array(1, $this->_username, $this->_password, $content);
        return $this->_sendRequest('wp.editProfile', $params);
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
        return $this->_sendRequest($method, $params);
    }

    private function _sendRequest($method, $params)
    {
        if (!$this->_endPoint)
        {
            $this->_error = "Invalid endpoint " . json_encode(array('endpoint' => $this->_endPoint, 'username' => $this->_username, 'password' => $this->_password));
            $this->_logError();
            throw new \Exception($this->_error);
        }
        $this->_responseHeader = array();
        $this->_setXmlrpcType($params);
        $this->_request        = xmlrpc_encode_request($method, $params, array('encoding' => 'UTF-8', 'escaping' => 'markup'));
        $body                  = "";
        // Call sending event callbacks
        $callbacks = $this->_getCallback('sending');
        $event = array(
            'event'    => 'sending',
            'endpoint' => $this->_endPoint,
            'username' => $this->_username,
            'password' => $this->_password,
            'method'   => $method,
            'params'   => $params,
            'request'  => $this->_request,
            'proxy'    => $this->_proxyConfig,
            'auth'     => $this->_authConfig,
        );
        foreach ($callbacks as $callback) {
            $callback($event);
        }
        if (function_exists('curl_init'))
        {
            $body = $this->_requestWithCurl();
        }
        else
        {
            $body = $this->_requestWithFile();
        }
        $response = xmlrpc_decode($body);
        if (is_array($response) && xmlrpc_is_fault($response))
        {
            $this->_error = ("xmlrpc: {$response['faultString']} ({$response['faultCode']})");
            $this->_logError();
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
    private function _setXmlrpcType(&$array)
    {
        foreach ($array as $key => $element)
        {
            if (is_a($element, '\DateTime'))
            {
                $array[$key] = $element->format("Ymd\TH:i:sO");
                xmlrpc_set_type($array[$key], 'datetime');
            }
            elseif (is_array($element))
            {
                $this->_setXmlrpcType($array[$key]);
            }
        }
    }

    private function _requestWithCurl()
    {
        $ch = curl_init($this->_endPoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_request);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_userAgent);
        if ($this->_proxyConfig != false)
        {
            if (isset($this->_proxyConfig['proxy_ip']))
            {
                curl_setopt($ch, CURLOPT_PROXY, $this->_proxyConfig['proxy_ip']);
            }
            if (isset($this->_proxyConfig['proxy_port']))
            {
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->_proxyConfig['proxy_port']);
            }
            if (isset($this->_proxyConfig['proxy_user']) && isset($this->_proxyConfig['proxy_pass']))
            {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$this->_proxyConfig['proxy_user']}:{$this->_proxyConfig['proxy_pass']}");
            }
            if (isset($this->_proxyConfig['proxy_mode']))
            {
                curl_setopt($ch, CURLOPT_PROXYAUTH, $this->_proxyConfig['proxy_mode']);
            }
        }
        if ($this->_authConfig)
        {
            if (isset($this->_authConfig['auth_user']) && isset($this->_authConfig['auth_pass']))
            {
                curl_setopt($ch, CURLOPT_USERPWD, "{$this->_authConfig['auth_user']}:{$this->_authConfig['auth_pass']}");
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            }
            if (isset($this->_authConfig['auth_mode']))
            {
                curl_setopt($ch, CURLOPT_HTTPAUTH, $this->_authConfig['auth_mode']);
            }
        }
        $response = curl_exec($ch);
        if (curl_errno($ch))
        {
            $message      = curl_error($ch);
            $code         = curl_errno($ch);
            $this->_error = "curl: {$message} ({$code})";
            $this->_logError();
            curl_close($ch);
            throw new Exception\NetworkException($message, $code);
        }
        $httpStatusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatusCode >= 400)
        {
            $message      = $response;
            $code         = $httpStatusCode;
            $this->_error = "http: {$message} ({$code})";
            $this->_logError();
            curl_close($ch);
            throw new Exception\NetworkException($message, $code);
        }
        curl_close($ch);
        return $response;
    }

    private function _requestWithFile()
    {
        $contextOptions = array('http' => array(
                'method'     => "POST",
                'user_agent' => $this->_userAgent,
                'header'     => "Content-Type: text/xml\r\n",
                'content'    => $this->_request
        ));

        if ($this->_proxyConfig != false)
        {
            if (isset($this->_proxyConfig['proxy_ip']) && isset($this->_proxyConfig['proxy_port']))
            {
                $contextOptions['http']['proxy']           = "tcp://{$this->_proxyConfig['proxy_ip']}:{$this->_proxyConfig['proxy_port']}";
                $contextOptions['http']['request_fulluri'] = true;
            }
            if (isset($this->_proxyConfig['proxy_user']) && isset($this->_proxyConfig['proxy_pass']))
            {
                $auth = base64_encode("{$this->_proxyConfig['proxy_user']}:{$this->_proxyConfig['proxy_pass']}");
                $contextOptions['http']['header'] .= "Proxy-Authorization: Basic {$auth}\r\n";
            }
            if (isset($this->_proxyConfig['proxy_mode']))
            {
                throw new \InvalidArgumentException('Cannot use NTLM proxy authorization without cURL extension');
            }
        }
        if ($this->_authConfig)
        {
            if (isset($this->_authConfig['auth_user']) && isset($this->_authConfig['auth_pass']))
            {
                $auth = base64_encode("{$this->_authConfig['auth_user']}:{$this->_authConfig['auth_pass']}");
                $contextOptions['http']['header'] .= "Authorization: Basic {$auth}\r\n";
            }
            if (isset($this->_authConfig['auth_mode']))
            {
                throw new \InvalidArgumentException('Cannot use other authentication method without cURL extension');
            }
        }
        $context              = stream_context_create($contextOptions);
        $http_response_header = array();
        try
        {
            $file = @file_get_contents($this->_endPoint, false, $context);
            if ($file === false)
            {
                $error        = error_get_last();
                $error        = $error ? trim($error['message']) : "error";
                $this->_error = "file_get_contents: {$error}";
                $this->_logError();
                throw new Exception\NetworkException($error, 127);
            }
        }
        catch (\Exception $ex)
        {
            $this->_error = ("file_get_contents: {$ex->getMessage()} ({$ex->getCode()})");
            $this->_logError();
            throw new Exception\NetworkException($ex->getMessage(), $ex->getCode());
        }
        return $file;
    }

    private function _logError()
    {
        $callbacks = $this->_getCallback('error');
        $event = array(
            'event'    => 'error',
            'endpoint' => $this->_endPoint,
            'request'  => $this->_request,
            'proxy'    => $this->_proxyConfig,
            'auth'     => $this->_authConfig,
        );
        foreach($callbacks as $callback)
        {
            $callback($this->_error, $event);
        }
    }

    private function _getCallback($name)
    {
        $callbacks = array();
        if (isset($this->_callbacks[$name]) && is_array($this->_callbacks[$name]))
        {
            $callbacks = $this->_callbacks[$name];
        }
        return $callbacks;
    }

}

<?php

namespace HieuLe\WordpressXmlrpcClient;

use Illuminate\Log\Writer;

/**
 * A XML-RPC client that implement the {@link http://codex.wordpress.org/XML-RPC_WordPress_API Wordpress API}.
 * 
 * @version 2.0
 * 
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 * 
 * @license http://opensource.org/licenses/MIT MIT
 */
class WordpressClient
{

	private $_username;
	private $_password;
	private $_endPoint;
	private $_request;
	private $_response;
	private $_responseHeader = array();
	private $_error;

	/**
	 *
	 * @var \Illuminate\Log\Writer;
	 */
	private $_logger;

	/**
	 * Create a new client with credentials
	 * 
	 * @param string $xmlrpcEndPoint The wordpress XML-RPC endpoint
	 * @param string $username The client's username
	 * @param string $password The client's password
	 * @param \Illuminate\Log\Writer $logger
	 */
	public function __construct($xmlrpcEndPoint, $username, $password, Writer $logger = null)
	{
		$this->_endPoint = $xmlrpcEndPoint;
		$this->_username = $username;
		$this->_password = $password;
		$this->_logger	 = $logger;
	}

	/**
	 * Get the latest error message
	 * 
	 * @return string
	 */
	function getErrorMessage()
	{
		return $this->_error;
	}

	/**
	 * Retrieve a post of any registered post type. 
	 * 
	 * @param integer $postId	post id The id of selected post
	 * @param array $fields	Optional. List of field or meta-field names to include in response.
	 * @return array|boolean
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
		if ($this->_sendRequest('wp.getPost', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of posts of any registered post type. 
	 * 
	 * @param array $filters Optional
	 * @param array $fields	Optional
	 * @return array|boolean array of struct
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPosts
	 */
	function getPosts(array $filters = array(), array $fields = array())
	{
		$params = array(1, $this->_username, $this->_password, $filters);
		if (!empty($fields))
		{
			$params[] = $fields;
		}
		if ($this->_sendRequest('wp.getPosts', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Create a new post of any registered post type. 
	 * 
	 * @param string $title	the post title
	 * @param string $body	the post body
	 * @param array $categorieIds	the list of category ids
	 * @param integer $thumbnailId	the thumbnail id
	 * @param array $content	the content array, see more at wordpress documentation
	 * @return integer|boolean the new post id
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.newPost
	 */
	function newPost($title, $body, array $categorieIds = array(), $thumbnailId = NULL, array $content = array())
	{
		$default				 = array(
			'post_type'		 => 'post',
			'post_status'	 => 'publish',
			'terms'			 => array(),
		);
		$content				 = array_merge($default, $content);
		$content['post_title']	 = $title;
		$content['post_content'] = $body;

		if ($thumbnailId != NULL)
		{
			$content['post_thumbnail'] = $thumbnailId;
		}
		if (!empty($categorieIds))
		{
			$content['terms']['category'] = $categorieIds;
		}
		$params = array(1, $this->_username, $this->_password, $content);
		if ($this->_sendRequest('wp.newPost', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Edit an existing post of any registered post type. 
	 * 
	 * @param integer $postId	the id of selected post
	 * @param string $title	the new title
	 * @param string $body	the new body
	 * @param array $categorieIds	the new list of category ids
	 * @param integer $thumbnailId	the new thumbnail id
	 * @param array $content	the advanced array
	 * @return boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.editPost
	 */
	function editPost($postId, array $content)
	{
		$params = array(1, $this->_username, $this->_password, $postId, $content);
		if ($this->_sendRequest('wp.editPost', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Delete an existing post of any registered post type. 
	 * 
	 * @param integer $postId	the id of selected post
	 * @return boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.deletePost
	 */
	function deletePost($postId)
	{
		$params = array(1, $this->_username, $this->_password, $postId);
		if ($this->_sendRequest('wp.deletePost', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve a registered post type. 
	 * 
	 * @param string $postTypeName the post type name
	 * @param array $fields	Optional. List of field or meta-field names to include in response. 
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostType
	 */
	function getPostType($postTypeName, array $fields = array())
	{
		$params = array(1, $this->_username, $this->_password, $postTypeName, $fields);
		if ($this->_sendRequest('wp.getPostType', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of registered post types. 
	 * 
	 * @param array $filter
	 * @param array $fields
	 * @return array|boolean	list of struct
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostTypes
	 */
	function getPostTypes(array $filter = array(), array $fields = array())
	{
		$params = array(1, $this->_username, $this->_password, $filter, $fields);
		if ($this->_sendRequest('wp.getPostTypes', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of post formats. 
	 * 
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostFormats
	 */
	function getPostFormats()
	{
		$params = array(1, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getPostFormats', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of supported values for post_status field on posts. 
	 * 
	 * @return array|boolean	list of supported post status
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPostStatusList
	 */
	function getPostStatusList()
	{
		$params = array(1, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getPostStatusList', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve information about a taxonomy. 
	 * 
	 * @param string $taxonomy the name of the selected taxonomy
	 * @return array|boolean	taxonomy information
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTaxonomy
	 */
	function getTaxonomy($taxonomy)
	{
		$params = array(1, $this->_username, $this->_password, $taxonomy);
		if ($this->_sendRequest('wp.getTaxonomy', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve a list of taxonomies. 
	 * 
	 * @return array|boolean array of taxonomy struct
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTaxonomies
	 */
	function getTaxonomies()
	{
		$params = array(1, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getTaxonomies', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve a taxonomy term. 
	 * 
	 * @param integer $termId 
	 * @param string $taxonomy
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTerm
	 */
	function getTerm($termId, $taxonomy)
	{
		$params = array(1, $this->_username, $this->_password, $taxonomy, $termId);
		if ($this->_sendRequest('wp.getTerm', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of terms in a taxonomy. 
	 * 
	 * @param string $taxonomy
	 * @param array $filter
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.getTerms
	 */
	function getTerms($taxonomy, array $filter = array())
	{
		$params = array(1, $this->_username, $this->_password, $taxonomy, $filter);
		if ($this->_sendRequest('wp.getTerms', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Create a new taxonomy term. 
	 * 
	 * @param string $name
	 * @param string $taxomony
	 * @param string $slug
	 * @param string $description
	 * @param integer $parentId
	 * @return integer|boolean new term id
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.newTerm
	 */
	function newTerm($name, $taxomony, $slug = null, $description = null, $parentId = null)
	{
		$content = array(
			'name'		 => $name,
			'taxonomy'	 => $taxomony,
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
		if ($this->_sendRequest('wp.newTerm', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Edit an existing taxonomy term. 
	 * 
	 * @param integer $termId
	 * @param string $taxonomy
	 * @param array $content
	 * @return boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.editTerm
	 */
	function editTerm($termId, $taxonomy, array $content = array())
	{
		$content['taxonomy'] = $taxonomy;
		$params				 = array(1, $this->_username, $this->_password, $termId, $content);
		if ($this->_sendRequest('wp.editTerm', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Delete an existing taxonomy term. 
	 * 
	 * @param integer $termId
	 * @param string $taxonomy
	 * @return boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Taxonomies#wp.deleteTerm
	 */
	function deleteTerm($termId, $taxonomy)
	{
		$params = array(1, $this->_username, $this->_password, $taxonomy, $termId);
		if ($this->_sendRequest('wp.deleteTerm', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve a media item (i.e, attachment). 
	 * 
	 * @param integer $itemId
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Media#wp.getMediaItem
	 */
	function getMediaItem($itemId)
	{
		$params = array(1, $this->_username, $this->_password, $itemId);
		if ($this->_sendRequest('wp.getMediaItem', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of media items. 
	 * 
	 * @param array $filter
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Media#wp.getMediaLibrary
	 */
	function getMediaLibrary(array $filter = array())
	{
		$params = array(1, $this->_username, $this->_password, $filter);
		if ($this->_sendRequest('wp.getMediaLibrary', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Upload a media file. 
	 * 
	 * @param string $name
	 * @param string $mime
	 * @param string $bits Binary data (no encoded)
	 * @return array|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Media#wp.uploadFile
	 */
	function uploadFile($name, $mime, $bits)
	{
		xmlrpc_set_type($bits, 'base64');
		$struct	 = array(
			'name'	 => $name,
			'type'	 => $mime,
			'bits'	 => $bits,
		);
		$params	 = array(1, $this->_username, $this->_password, $struct);
		if ($this->_sendRequest('wp.uploadFile', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve comment count for a specific post. 
	 * 
	 * @param integer $postId
	 * @return integer|boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getCommentCount
	 */
	function getCommentCount($postId)
	{
		$params = array(1, $this->_username, $this->_password, $postId);
		if ($this->_sendRequest('wp.getCommentCount', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
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
		if ($this->_sendRequest('wp.getComment', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of comments. 
	 * 
	 * @param array $filter
	 * @return array
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getComments
	 */
	function getComments(array $filter = array())
	{
		$params = array(1, $this->_username, $this->_password, $filter);
		if ($this->_sendRequest('wp.getComments', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Create a new comment.
	 * 
	 * @param integer $post_id
	 * @param array $comment
	 * @return integer new comment_id
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.newComment
	 */
	function newComment($post_id, array $comment)
	{
		$params = array(1, $this->_username, $this->_password, $post_id, $comment);
		if ($this->_sendRequest('wp.newComment', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Edit an existing comment. 
	 * 
	 * @param integer $commentId
	 * @param array $comment
	 * @return boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.editComment
	 */
	function editComment($commentId, array $comment)
	{
		$params = array(1, $this->_username, $this->_password, $commentId, $comment);
		if ($this->_sendRequest('wp.editComment', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Remove an existing comment. 
	 * 
	 * @param integer $commentId
	 * @return boolean
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.deleteComment
	 */
	function deleteComment($commentId)
	{
		$params = array(1, $this->_username, $this->_password, $commentId);
		if ($this->_sendRequest('wp.deleteComment', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve list of comment statuses. 
	 * @return array
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Comments#wp.getCommentStatusList
	 */
	function getCommentStatusList()
	{
		$params = array(1, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getCommentStatusList', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve blog options. 
	 * 
	 * @param array $options
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
		if ($this->_sendRequest('wp.getOptions', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Edit blog options. 
	 * 
	 * @param array $options
	 * @return array
	 * 
	 * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Options#wp.setOptions
	 */
	function setOptions(array $options)
	{
		$params = array(1, $this->_username, $this->_password, $options);
		if ($this->_sendRequest('wp.setOptions', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
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
		if ($this->_sendRequest('wp.getUsersBlogs', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
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
		if ($this->_sendRequest('wp.getUser', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
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
		if ($this->_sendRequest('wp.getUsers', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieve profile of the requesting user. 
	 * 
	 * @param array $fields
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
		if ($this->_sendRequest('wp.getProfile', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Edit profile of the requesting user. 
	 * 
	 * @param array $content
	 * @return boolean
	 * 
	 * http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.editProfile
	 */
	function editProfile(array $content)
	{
		$params = array(1, $this->_username, $this->_password, $content);
		if ($this->_sendRequest('wp.editProfile', $params))
		{
			return $this->_response;
		}
		else
		{
			return false;
		}
	}

	private function _sendRequest($method, $params)
	{
		$this->_responseHeader	 = array();
		$this->_request			 = xmlrpc_encode_request($method, $params, array('encoding' => 'UTF-8', 'escaping' => 'markup'));
		$context				 = stream_context_create(array('http' => array(
				'method'	 => "POST",
				'header'	 => "Content-Type: text/xml",
				'content'	 => $this->_request
		)));
		$http_response_header	 = array();
		try
		{
			$file = @file_get_contents($this->_endPoint, false, $context);
			if ($file === false)
			{
				$error					 = error_get_last();
				$error					 = $error ? trim($error['message']) : "error";
				$this->_error			 = "file_get_contents: {$error}";
				$this->_responseHeader	 = $http_response_header;
				$this->_logError();
				return false;
			}
		}
		catch (\Exception $ex)
		{
			$this->_error			 = ("file_get_contents: {$ex->getMessage()} ({$ex->getCode()})");
			$this->_responseHeader	 = $http_response_header;
			$this->_logError();
			return false;
		}
		$response = xmlrpc_decode($file);
		if (is_array($response) && xmlrpc_is_fault($response))
		{
			$this->_error = ("xmlrpc: {$response['faultString']} ({$response['faultCode']})");
			$this->_logError();
			return false;
		}
		$this->_response = $response;
		return true;
	}

	private function _logError()
	{
		if ($this->_logger)
		{
			$this->_logger->getMonolog()->error($this->_error, array(
				'endPoint'			 => $this->_endPoint,
				'username'			 => $this->_username,
				'password'			 => $this->_password,
				'response_header'	 => $this->_responseHeader,
				'request'			 => $this->_request,
			));
		}
	}

}

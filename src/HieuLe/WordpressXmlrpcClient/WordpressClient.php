<?php

namespace HieuLe\WordpressXmlrpcClient;

use Illuminate\Log\Writer;

/**
 * Description of WordpressClient
 *
 * @author TrungHieu
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

	public function __construct($xmlrpcEndPoint, $username, $password, Writer $logger = null)
	{
		$this->_endPoint = $xmlrpcEndPoint;
		$this->_username = $username;
		$this->_password = $password;
		$this->_logger	 = $logger;
	}

	function getResponseHeader()
	{
		return $this->_responseHeader;
	}

	function getErrorMessage()
	{
		return $this->_error;
	}

	function getResponse()
	{
		return $this->_response;
	}

	function createPost($title, $body, $categories = array(), $thumbnailId = NULL, $customFields = NULL)
	{
		$content = array(
			'post_title'	 => $title,
			'post_content'	 => $body,
			'post_type'		 => 'post',
			'post_status'	 => 'publish',
			'terms'			 => array(),
		);
		if ($customFields != NULL)
		{
			$content['custom_fields'] = $customFields;
		}
		if ($thumbnailId != NULL)
		{
			$content['post_thumbnail'] = $thumbnailId;
		}
		if (!empty($categories))
		{
			$content['terms']['category'] = $categories;
		}
		$params = array(1, $this->_username, $this->_password, $content);
		if ($this->_sendRequest('wp.newPost', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return FALSE;
		}
	}

	function editPost($postId, $title, $body, $categories = array(), $thumbnailId = NULL, $customFields = NULL)
	{
		$content = array(
			'post_title'	 => $title,
			'post_content'	 => $body,
			'post_type'		 => 'post',
			'terms'			 => array(),
		);
		if ($customFields != NULL)
		{
			$content['custom_fields'] = $customFields;
		}
		if ($thumbnailId != NULL)
		{
			$content['post_thumbnail'] = $thumbnailId;
		}
		if (!empty($categories))
		{
			$content['terms']['category'] = $categories;
		}
		$params = array(1, $this->_username, $this->_password, $postId, $content);
		if ($this->_sendRequest('wp.editPost', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function getAuthors()
	{
		$params = array(0, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getAuthors', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function getUsersblogs()
	{
		$params = array($this->_username, $this->_password);
		if ($this->_sendRequest('wp.getUsersBlogs', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function getCategories()
	{
		$params = array(0, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getCategories', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function getTags()
	{
		$params = array(0, $this->_username, $this->_password);
		if ($this->_sendRequest('wp.getTags', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function addCategory($name, $slug, $parent_id, $description)
	{
		$struct	 = array(
			'name'			 => $name,
			'slug'			 => $slug,
			'parent_id'		 => $parent_id,
			'description'	 => $description);
		$params	 = array(0, $this->_username, $this->_password, $struct);
		if ($this->_sendRequest('wp.newCategory', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function getPost($id)
	{
		$params = array(1, $this->_username, $this->_password, $id);
		if ($this->_sendRequest('wp.getPost', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

	function getPosts()
	{
		$params = array(1, $this->_username, $this->_password, array('number' => '9999'));
		if ($this->_sendRequest('wp.getPosts', $params))
		{
			return $this->getResponse();
		}
		else
		{
			return false;
		}
	}

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
			return $this->getResponse();
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
			if ($file === FALSE)
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



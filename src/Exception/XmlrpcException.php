<?php

namespace HieuLe\WordpressXmlrpcClient\Exception;

/**
 * An error respond from wordpress 
 *
 * @author TrungHieu
 */
class XmlrpcException extends \Exception
{
	public function __construct($message, $code)
	{
		parent::__construct($message, $code, null);
	}
	
	public function __toString()
	{
		return "XML-RPC error: {$this->message} (Code: {$this->code})";
	}
}

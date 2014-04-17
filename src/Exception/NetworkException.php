<?php

namespace HieuLe\WordpressXmlrpcClient\Exception;

/**
 * An error occurs when connecting to the wordpress server
 *
 * @author TrungHieu
 */
class NetworkException extends \Exception
{
	public function __construct($message, $code)
	{
		parent::__construct($message, $code, null);
	}
	
	public function __toString()
	{
		return "Network error: {$this->message} (Code: {$this->code})";
	}
}

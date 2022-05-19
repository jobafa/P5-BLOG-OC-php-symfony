<?php

namespace Inc;

use Exception;

/**
 * Class Method
 * Manage $_GET and $_POST variables 
 */
class Method
{
	private $method;

	public function __construct($method)
	{
		$this->method = $method;
	}

	public function get($name)
	{
		if (isset($this->method[$name]))
		{
			return $this->method[$name];
		}
	}

	public function set($name, $value)
	{
		$this->method[$name] = $value;
	}

	public function all()
	{
		return $this->method;
	}
}
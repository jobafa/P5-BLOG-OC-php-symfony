<?php

namespace Inc;

use Inc\Method;
use Inc\SessionManager;

/**
 * Class Request
 * Manage request through global variables ($_POST, $_GET, $_SESSION, $_FILES)
 */
class Request
{
	private $get;
	private $post;
	private $session;
	private $file;

	public function __construct()
	{
		$this->get = new Method($_GET);
		$this->post = new Method($_POST);
		$this->session = new SessionManager($_SESSION);
		$this->file = new File($_FILES);
	}

	/**
	 * @return method
	 */
	public function getGet()
	{
		return $this->get;
	}

	/**
	 * @return method
	 */
	public function getPost()
	{
		return $this->post;
	}

	/**
	 * @return Session
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * @return File  
	 */
	public function getFile()
	{
		return $this->file;
	}

}

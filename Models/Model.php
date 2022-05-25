<?php

namespace Models;

require_once ('Models/Manager.php');

abstract class Model {
    
        protected $db;

		public function __construct()
		{
			
			$this->db = \Manager::getPdo();
		}
}
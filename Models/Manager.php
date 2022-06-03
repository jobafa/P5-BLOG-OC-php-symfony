<?php

//namespace Models;

class Manager{

private static $instance = null;

public static function getPdo()

    {
		 //connexion a la bdd

			try
				{
						if(self::$instance === null){
              self::$instance = new \PDO('mysql:host=localhost;dbname=test;charset=utf8', 'dbuser', '', [
              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
              ]);
              
            }
						
				}
				catch (PDOException $e)
				{
				echo 'Connexion échouée : ' . $e->getMessage();
				}				    
				return self::$instance;
			}
  }

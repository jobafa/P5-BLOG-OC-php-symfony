<?php

//use PDOException;

//namespace Models;

class Manager{

private static $instance = null;

public static function getPdo()

    {
		 //connexion a la bdd

			try
				{
						/*if(self::$instance === null){
              self::$instance = new \PDO('mysql:host=localhost;dbname=test;charset=utf8', 'dbuser', '', [
              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
              ]);*/
              if(self::$instance === null){
                self::$instance = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]);
            
              /*//PREPROD
              if(self::$instance === null){
                self::$instance = new \PDO('mysql:host=localhost;dbname=poo_ocp5;charset=utf8', 'poo_ocp5_usr', '*O15p16', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]);
               */
             /*// PREPROD
                $db = new \PDO('mysql:host=localhost;dbname=poo_ocp5;charset=utf8', 'poo_ocp5_usr', '*O15p16', [
              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
              ]);
              */
						//$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
						
				}
				catch (PDOException $e)
				{
				  
          $errorMessage = $e->getMessage();
          var_dump($errorMessage);
          exit;
          \Http::redirect("view/errorView.php?errorMessage = $errorMessage");
    		  //require'view/errorView.php';
				}				    
				return self::$instance;
			}
  }

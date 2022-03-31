<?php

namespace OC\PhpSymfony\Blog\Model;


abstract class Manager
{

	private  $_db;

    protected function dbConnect()

    {
		 //connexion a la bdd

			try
				{
						$db = new \PDO('mysql:host=localhost;dbname=test;charset=utf8', 'dbuser', '');
				}
				catch (PDOException $e)
				{
				echo 'Connexion �chou�e : ' . $e->getMessage();
				}				    
				return $db;
			}


}

/**

blog-php

 *  class Bdd
 *  Permet la connexion à la  db
 
Abstract class Database
{

  protected $db;
  private $db_host      = "localhost";
  private $db_name      = "blog-php";
  private $db_login     = "root";
  private $db_password  = "root";

  public function __construct()
  {
    $db = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', $this->db_login, $this->db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    $this->db = $db;
  }
}
*/

 /* private static $_db;

  //connexion to database

  private static function setdb(){
    self::$_db = new \PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root');

    //on utilise les constantes de PDO pour gérer les erreurs
    self::$_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
  }

  //fonction de connexion par defaut a la bdd
  protected function dbConnect(){
    if (self::$_db == null) {
      self::setdb();
      return self::$_db;
    }
  }

  //creation d ela methode
  //de récupération de liste d'elements
  //dans la bdd

  protected function getAll($table, $obj){
    $this->getBdd();
    $var = [];
    $req = self::$_bdd->prepare('SELECT * FROM '.$table.' ORDER BY id desc');
    $req->execute();

    //on crée la variable data qui
    //va cobntenir les données
    while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
      // var contiendra les données sous forme d'objets
      $var[] = new $obj($data);
    }

    return $var;
    $req->closeCursor();


  }

  protected function getOne($table, $obj, $id)
  {
    $this->getBdd();
    $var = [];
    $req = self::$_bdd->prepare("SELECT id, title, content, DATE_FORMAT(date, '%d/%m/%Y à %Hh%imin%ss') AS date FROM " .$table. " WHERE id = ?");
    $req->execute(array($id));
    while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
      $var[] = new $obj($data);
    }

    return $var;
    $req->closeCursor();
  }

  protected function createOne($table, $obj)
  {
    $this->getBdd();
    $req = self::$_bdd->prepare("INSERT INTO ".$table." (title, content, date) VALUES (?, ?, ?)");
    $req->execute(array($_POST['title'], $_POST['content'], date("d.m.Y")));

    $req->closeCursor();
  }
}
*/







	
/**/

/*public function __construct()
{
$dsn = 'mysql:dbname=pieces_leader;host=localhost;port=3308';
$user = 'root';
$password = '';
try
{
$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e)
{
echo 'Connexion �chou�e : ' . $e->getMessage();
}
*/
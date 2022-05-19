<?php

namespace Controllers;
use Inc\SessionManager;
//require_once('models/CommentManager.php');
//require_once('models/UserManager.php');
//require_once('libraries/utils.php');
//require_once('inc/SessionManager.php');
//require_once('Controllers/Controller.php');

class Comment extends Controller{

	//protected $commentManager;
	//protected $modelName ;
    //private $ispublished = '1';

	protected $model;
	//protected $modelName = "\Models\PostManager";
    protected $modelName = \Models\CommentManager::class;

    /*public function __construct(){

    $this->model = new \Models\CommentManager();

    }*/

    /***ADD COMMENT****************
	**@PARAMS $postId $author, $comment, $userid**
	************************************/

	public function addComment($postId, $author, $comment, $userid)
	{
		//$commentManager = new \Models\CommentManager();

		$affectedLines = $this->model->insert($postId, $author, $comment, $userid);
		
		//$session = new \inc\SessionManager($_SESSION); // create object instance
		$action = SessionManager::getInstance($_SESSION)->get('ACTION');
		//$action = $session->get('ACTION');

				//generate a message according to the action processed
				initmessage($action,$affectedLines);

		if ($affectedLines === false) {
			throw new Exception('Impossible d\'ajouter le commentaire !');
		}
		else {
			//header('Location: index.php?action=frontpost&id=' . $postId);
			\Http::redirect("index.php?action=frontpost&id=$postId#commentaires");
		}
	}


}
<?php

namespace Controllers;
use Inc\SessionManager;
use Inc\MessageDisplay;

class Comment extends Controller{

	protected $model;
	
    protected $modelName = \Models\CommentManager::class;
 

    /***ADD COMMENT****************
	**@PARAMS $postId $author, $comment, $userId**
	************************************/

	public function addComment($postId, $author, $comment, $userId)
	{
		if( (!$this->is_Admin()) && (!$this->is_Guest()) ){ 
			$this->redirectLogin();
		}

		$affectedLines = $this->model->insert($postId, $author, $comment, $userId);
				
		$action = SessionManager::getInstance()->get('ACTION');
		
		//generate a message according to the action processed
		$messageDisplay = new \Inc\MessageDisplay();
		$messageDisplay->initmessage($action,$affectedLines);

		if ($affectedLines === false) {
			throw new Exception('Impossible d\'ajouter le commentaire !');
		}
		else {
			
			\Http::redirect("index.php?action=frontpost&id=$postId#commentaires");
		}
	}
}
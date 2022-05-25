<?php

namespace Controllers;
use Inc\SessionManager;


class Comment extends Controller{

	

	protected $model;
	
    protected $modelName = \Models\CommentManager::class;

   

    /***ADD COMMENT****************
	**@PARAMS $postId $author, $comment, $userid**
	************************************/

	public function addComment($postId, $author, $comment, $userid)
	{
		

		$affectedLines = $this->model->insert($postId, $author, $comment, $userid);
		
		
		$action = SessionManager::getInstance()->get('ACTION');
		
				//generate a message according to the action processed
				initmessage($action,$affectedLines);

		if ($affectedLines === false) {
			throw new Exception('Impossible d\'ajouter le commentaire !');
		}
		else {
			
			\Http::redirect("index.php?action=frontpost&id=$postId#commentaires");
		}
	}


}
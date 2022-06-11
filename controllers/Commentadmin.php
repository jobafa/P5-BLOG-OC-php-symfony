<?php

namespace Controllers;

require_once'Controllers/Controller.php';

use Inc\SessionManager;
use Inc\MessageDisplay;
use Controllers\User;

class Commentadmin extends Controller{

	protected $model;
	protected $modelName = \Models\CommentManager::class;
    protected $messagedisplay;
    

    # **********************************
    #  LISTS User's Comments 
    # **********************************

    public function myComments(){
            
        // CHEKS IF USER IS A GUEST
        if( !$this->is_Guest() ) $this->redirectLogin();
        
        $userid= SessionManager::getInstance()->get('USERID');
        $commentsvalidate = $this->model->getAll($postId = null, '0', $userid = null); // Appel d'une fonction de cet objet
        
        require('view/backend/listCommentsView.php');
    }

    # *******************************
    #  LISTS Comments  To Validate   OR DELETE
    # *************************************

    public function listCommentsValidate(){

        if( !$this->is_Admin() && !$this->is_Guest() ) $this->redirectLogin();

        // CHEKS IF USER IS A GUEST
       
        if($this->is_Guest()){
           
            $userid= SessionManager::getInstance()->get('USERID');
            $commentsvalidate = $this->model->getAll( $postId = null, $isenabled = null,$userid ); // Appel d'une fonction de cet objet
            $title = 'Mes Commentaires';
        }elseif($this->is_Admin()){
            $commentsvalidate = $this->model->getAll($postId = null, '0'); // Appel d'une fonction de cet objet
            $title = 'Gestion des Commentaires';
        }
        
        require('view/backend/listCommentsView.php');
    }

    # *****************
    # Delete a comment 
    #@param $commentid
    # *****************

    public function deleteComment($commentid) {
        
        if( !$this->is_Admin() && !$this->is_Guest() ) $this->redirectLogin();

        $userid= SessionManager::getInstance()->get('USERID');
    
        $deletecomment = $this->model->deleteCommentPost($idpost = null , $commentid,$userid = null);
    
        $messageDisplay = new \Inc\MessageDisplay();
        $action = SessionManager::getInstance()->get('ACTION');
        $messageDisplay->initmessage($action,$deletecomment);
      
        \Http::redirect('index.php?action=commentsadmin&controller=commentadmin');
      
    }

    # **************
    #  LISTS Comments  To Validate   OR DELETE
    # **************
    
    public function CommentValidate($commentId){

        if( !$this->is_Admin() ) $this->redirectLogin();

            $validateComment = $this->model->validateComment($commentId);

            $messageDisplay = new \Inc\MessageDisplay();
            $action = SessionManager::getInstance()->get('ACTION');
            //gerate a message according to the action process
            $messageDisplay->initmessage($action,$validateComment);

            \Http::redirect('index.php?action=commentsadmin&controller=commentadmin');

    }
}
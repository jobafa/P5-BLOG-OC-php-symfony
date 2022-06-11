<?php
namespace Controllers;

use Inc\SessionManager;
//use Controllers\User;

class Post extends Controller{
    
    protected $model;
	protected $modelName = \Models\PostManager::class;

    /***DISPLAYS ALLE POSTS********************************
	**@PARAMS $from : front or back and $getPage : for pagination**
	****************************************************/

	public function listposts($from = '', $getPage)
	{
		$totalRecrods = $this->model->gettotalPosts($isPublished = '1'); 
		
		// PAGINAION

		$limit = 4;
		$page = $getPage;
		$paginationStart = ($page - 1) * $limit;
		
		// Calculate total pages
		$totoalPages = ceil($totalRecrods / $limit);

		// Prev + Next
		$prev = $page - 1;
		$next = $page + 1;

		// end pagination

		$posts = $this->model->getAll($userId = null, $from, $isPublished = '1', $paginationStart, $limit); 
				
        $title = 'Mon blog';  
		
		\Renderer::render('frontend/listPostsView', compact('title', 'posts', 'page', 'totoalPages', 'next', 'prev'));
	}


	/***DISPLAYS ONE POST****************
	**@PARAMS $id : Post id and $isPublished**
	************************************/

	public function post($id, $page)
	{		
		$commentManager = new \Models\CommentManager();
		$cleanObject = new \Inc\Clean();
		//$user = new \Controllers\User();

		$action  = SessionManager::getInstance()->get('ACTION');

		if($action === "frontpost"){
			$isPublished = "1";
		}elseif($action === "post"){
			$isPublished = NULL;
		}
				
		$post = $this->model->get($id, $isPublished );

		if(($this->is_Admin())){ // IF ADMIN
			
			if(SessionManager::getInstance()->get('ACTION') != 'frontpost'){ // IF COMING FROM ADMIN DASHBOARD
				$title = "visualisation Article";

				require'view/backend/postView.php';
				
			}else{ // IF COMING FROM FRONTEND POST VIEW

				$comments = $commentManager->getAll($id,'1');
				
                $title = $cleanObject->escapeoutput($post['title']); 

		        \Renderer::render('frontend/PostView', compact('post', 'comments', 'page'));
			}
		}else{
			$comments = $commentManager->getAll($id,'1');

			$title = $cleanObject->escapeoutput($post['title']); 

		    \Renderer::render('frontend/postView', compact('post', 'comments', 'page'));
		}
	}


	/***ADD COMMENT****************
	**@PARAMS $postId $author, $comment, $userId**
	************************************/

	public function insert($postId, $author, $comment, $userId)
	{
		$commentManager = new \Models\CommentManager();

		$affectedLines = $commentManager->postComment($postId, $author, $comment, $userId);

		$action = $get->get('action');

				//generate a message according to the action processed
				initmessage($action,$affectedLines);

		if ($affectedLines === false) {
			throw new Exception('Impossible d\'ajouter le commentaire !');
		}
		else {
			
            \Http::redirect('index.php?action=frontpost&id=' . $postId);
		}
	}


}
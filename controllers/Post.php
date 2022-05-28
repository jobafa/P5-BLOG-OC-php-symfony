<?php
namespace Controllers;


class Post extends Controller{

    /
    protected $model;
	
    protected $modelName = \Models\PostManager::class;


    

    /***DISPLAYS ALLE POSTS********************************
	**@PARAMS $from : front or back and $getpage : for pagination**
	****************************************************/

	public function listposts($from = '', $getpage)
	{
		

		$totalRecrods = $this->model->gettotalPosts($ispublished = '1'); 
		
		// PAGINAION

		  $limit = 4;
		  $page = $getpage;
		  $paginationStart = ($page - 1) * $limit;
		  
		  // Calculate total pages
		  $totoalPages = ceil($totalRecrods / $limit);

		  // Prev + Next
		  $prev = $page - 1;
		  $next = $page + 1;

		  // end pagination

		  $posts = $this->model->getAll($userid = null, $from, $ispublished = '1', $paginationStart, $limit); 
		
		
        $title = 'Mon blog';  

		
		\Renderer::render('frontend/listPostsView', compact('title', 'posts', 'page', 'totoalPages', 'next', 'prev'));
	}


	/***DISPLAYS ONE POST****************
	**@PARAMS $id : Post id and $is_published**
	************************************/

	public function post($id, $page)
	{
		//$postManager = new \Models\PostManager();
		$commentManager = new \Models\CommentManager();
		//$session = new \inc\SessionManager($_SESSION); // create session instance

		//$action  = $session->get('ACTION');
		$action  = SessionManager::getInstance()->get('ACTION');

		if($action === "frontpost"){
			$is_published = "1";
		}elseif($action === "post"){
			$is_published = NULL;
		}
		
		
		$post = $this->model->get($id, $is_published );

		if((null !== SessionManager::getInstance()->get('USERTYPEID') ) && (SessionManager::getInstance()->get('USERTYPEID' == 1))){ // IF ADMIN

			if(SessionManager::getInstance()->get('ACTION') != 'frontpost'){ // IF COMING FROM ADMIN DASHBOARD

				require'view/backend/postView.php';
				
			}else{ // IF COMING FROM FRONTEND POST VIEW

				$comments = $commentManager->getAll($id,'1');
				
                $title = $cleanobject->escapeoutput($post['title']); 

		        \Renderer::render('frontend/PostView', compact('post', 'comments', 'page'));
			}
		}else{
			$comments = $commentManager->getAll($id,'1');

			$title = $cleanobject->escapeoutput($post['title']); 

		    \Renderer::render('frontend/postView', compact('post', 'comments', 'page'));
		}
	}


	/***ADD COMMENT****************
	**@PARAMS $postId $author, $comment, $userid**
	************************************/

	public function insert($postId, $author, $comment, $userid)
	{
		$commentManager = new \Models\CommentManager();

		$affectedLines = $commentManager->postComment($postId, $author, $comment, $userid);

		$action = $get->get('action');

				//generate a message according to the action processed
				initmessage($action,$affectedLines);

		if ($affectedLines === false) {
			throw new Exception('Impossible d\'ajouter le commentaire !');
		}
		else {
			//header('Location: index.php?action=frontpost&id=' . $postId);
            \Http::redirect('index.php?action=frontpost&id=' . $postId);
		}
	}


}

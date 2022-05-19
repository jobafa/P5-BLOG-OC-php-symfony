<?php
namespace Controllers;

//require_once('autoload.php');
//require_once('models/PostManager.php');
//require_once('models/CommentManager.php');
//require_once('models/UserManager.php'); 
//require_once('libraries/utils.php');
//require_once('controllers/Controller.php');

class Post extends Controller{

    //protected $postManager;
   // protected $model = "postManager";
    protected $model;
	//protected $modelName = "\Models\PostManager";
    protected $modelName = \Models\PostManager::class;


    //private $ispublished = '1';

    /*
    public function __construct(){

    $this->model = new \Models\PostManager();

    }*/

    /***DISPLAYS ALLE POSTS********************************
	**@PARAMS $from : front or back and $getpage : for pagination**
	****************************************************/

	public function listposts($from = '', $getpage)
	{
		//$postManager = new \Models\PostManager(); // Création d'un objet

		$totalRecrods = $this->model->gettotalPosts($ispublished = '1'); 
		//$totalRecrods = 9;
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
		
		//require('view/frontend/listPostsView.php');
        $title = 'Mon blog';  

		//render('frontend/listPostsView', $posts);
		\Renderer::render('frontend/listPostsView', compact('title', 'posts', 'page', 'totoalPages', 'next', 'prev'));
	}


	/***DISPLAYS ONE POST****************
	**@PARAMS $id : Post id and $is_published**
	************************************/

	public function post($id, $page)
	{
		//$postManager = new \Models\PostManager();
		$commentManager = new \Models\CommentManager();
		$session = new \inc\SessionManager($_SESSION); // create session instance

		$action  = $session->get('ACTION');

		if($action === "frontpost"){
			$is_published = "1";
		}elseif($action === "post"){
			$is_published = NULL;
		}
		
		
		$post = $this->model->get($id, $is_published );

		if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){ // IF ADMIN

			if($_SESSION['ACTION'] != 'frontpost'){ // IF COMING FROM ADMIN DASHBOARD

				require('view/backend/postView.php');
				
			}else{ // IF COMING FROM FRONTEND POST VIEW

				$comments = $commentManager->getAll($id,'1');
				//require('view/frontend/postView.php');
				//exit;
                $title = htmlentities($post['title']); 

		        \Renderer::render('frontend/PostView', compact('post', 'comments', 'page'));
			}
		}else{
			$comments = $commentManager->getAll($id,'1');

			//require('view/frontend/postView.php');
			//exit;
            $title = htmlentities($post['title']); 

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

		$action = $_GET['action'];

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
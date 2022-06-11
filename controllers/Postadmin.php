<?php

namespace Controllers;

require_once'Controllers/Controller.php';

use Inc\SessionManager;
use Inc\MessageDisplay;
use Inc\FileUpload;
use Controllers\User;

class Postadmin extends Controller{

	//private $errors = [];
	protected $model;
    //protected $messageDisplay;
	protected $modelName = \Models\PostManager::class;

	/*private static $filters = array(
	'string' => FILTER_SANITIZE_STRING,
	'string[]' => [
		'filter' => FILTER_SANITIZE_STRING,
		'flags' => FILTER_REQUIRE_ARRAY
	],
	'email' => FILTER_SANITIZE_EMAIL,
	'password' => FILTER_UNSAFE_RAW,
	'int' => [
		'filter' => FILTER_SANITIZE_NUMBER_INT,
		'flags' => FILTER_REQUIRE_SCALAR
	],
	'url' => FILTER_SANITIZE_URL,
	);*/

    public function modifyPostView($postId)
    {
        if( !$this->is_Admin() ) $this->redirectLogin();

        $post = $this->model->get($postId, $is_published = null);
        SessionManager::getInstance()->set('photo', $post['image']);

        $messageDisplay = new \Inc\MessageDisplay();
        $user = new \Controllers\User();

        $title = 'Edition Article';

        require'view/backend/posteditView.php';
          
    }

    # ********************
    # update Post 
    # ********************

    public function updatePost($postId, $post)
    {        
        if( !$this->is_Admin() ) $this->redirectLogin();

        $file = new \Inc\File();
        $fileupload = new \Inc\FileUpload();
            
        
        if( $file->get('pimage','error') !== 4){    // NO FILE UPLOADED
            
            $status = $file->get('pimage','error');
            $postPhoto = $file->get('pimage');

            $photo = $fileupload->checkUploadStatus($status, $postPhoto, $postId);
            
            if($photo == false){

                \Http::redirect('index.php?action=modifypost&controller=postadmin&id='.$postId);
                
            }
            
        }else{
            $photo = SessionManager::getInstance()->get('photo');
            
        } // END OF 
        
        /*// UPLOAD POST IMAGE

        if(isset($_FILES)){

            $status = $_FILES['pimage']['error'];
            $post_image = $_FILES["pimage"];
            
            $pimage = checkUploadStatus($status,$post_image,$postId);
            
            if($pimage == false){
                        
                header('Location: index.php?action=modifypost&id='.$postId);
                exit;
            }
            
        }*/

        try{
            //$post = $_POST;
            //$postManager = new \Models\PostManager();
            $affectedLines = $this->model->updatePost($postId, $post, $photo);
            
            $action = SessionManager::getInstance()->get('ACTION');

                //generate a message according to the action in process
                //$messageDisplay = new \Inc\MessageDisplay();
                //$messageDisplay->initmessage($action,$updateprofile);
                $messageDisplay = new \Inc\MessageDisplay();
                
                $messageDisplay->initmessage($action,$affectedLines);

                if ($affectedLines === false) {
                throw new Exception('Impossible de mettre � jour le post !');
                }
                else{
                //header('Location: index.php?action=modifypost&id=' . $postId .'&post_id='.$_GET['post_id'] );
                //header('Location: index.php?action=postsupdate');// BACK TO  POSTS ADMIN
                \Http::redirect("index.php?action=modifypost&controller=postadmin&id=$postId");// BACK TO  POSTS ADMIN
                }
        }
        catch(Exception $e) {
            $errorMessage = $e->getMessage();
            require'view/errorView.php';
        }					
    }

    # ********************
    # DISPLAYS New Post VIEW
    # ********************

    public function addPostView()
    {
        if( !$this->is_Admin() ) $this->redirectLogin();

        require'view/backend/addpostView.php';
    
    }
    

    # **************
    # Add New Post 
    # **************

    public function addPost($post)
    {
        if( !$this->is_Admin() ) $this->redirectLogin();

        $file = new \Inc\File();
        $fileupload = new \Inc\FileUpload();
            
        
        //if( $file->get('pimage','error') !== 4){    // NO FILE UPLOADED
            
            $status = $file->get('pimage','error');
            $postPhoto = $file->get('pimage');

            $photo = $fileupload->checkUploadStatus($status, $postPhoto, $postId);
           
            if($photo == false){

                \Http::redirect('index.php?action=addpostview&controller=postadmin');
                
            }
            
        /*}else{
            $photo = SessionManager::getInstance()->get('photo');
            
        } // END OF FILE UPLOAD CHECKING*/

        try{
            $post_userid = SessionManager::getInstance()->get('USERID');

            $post_title = htmlspecialchars(trim($post['title']));
            $post_lede = htmlspecialchars(trim($post['lede']));
            $post_author = htmlspecialchars(trim($post['author']));
            $post_content = htmlspecialchars(trim($post['content']));
            $is_enabled="1";
                        
            $result = $this->model->addPost( $post_userid, $post_title, $post_lede, $post_author, $post_content, $photo, $is_enabled);
            $action = SessionManager::getInstance()->get('ACTION');

            //Generate a message according to the action processed
            $messageDisplay = new \Inc\MessageDisplay();
            $messageDisplay->initmessage($action,$result);
            
            if ($result === false) {
                $_SESSION['postaddmessage'] = 0;
            throw new Exception('Impossible d\'ajouter le post !');
            }
            else {
                
            $_SESSION['postaddmessage'] = 1;
                \Http::redirect('index.php?action=addpostview&controller=postadmin');
                //header('Location: index.php?action=addpostview');
            }
        }
        catch(Exception $e){
            $errorMessage = $e->getMessage();
            require'view/errorView.php';
        }

    }

    # *********************************************
    #  LISTS Posts  FOR Activate, disactivate, Update or DELETE
    # @param userid or null if listing all posts
    # *********************************************

    public function listPostsUpdate($userId = null)
    {
        if( !$this->is_Admin() ) $this->redirectLogin();
           
        $posts = $this->model->getAll($userId,$from = null, $is_published = null, $paginationStart = null, $limit = null);

        $title = 'Gestion des Articles';
        $cleanobject = new \Inc\Clean();// FOR ESCAPE OUTPUT FUNCTION

        if( null === SessionManager::getInstance() ) session_start();

        $messageDisplay = new \Inc\MessageDisplay();

        $action = SessionManager::getInstance()->get('ACTION');
        SessionManager::getInstance()->Set('MYPOST', $action);
         
        if( !$this->is_Logged()){
            SessionManager::getInstance()->Set('actionmessage', 'Accès non authorisé, Veuillez vous  connecter !');
            SessionManager::getInstance()->Set('alert_flag', 0);
            
            \Http::redirect('loginview-user.html#login');
            
        }

        require'view/backend/backblogmanage_OK.php';
        
    }



    # **************
    #  Post Acivation
    # **************

    public function postPublish(int $postId, int $ispublished)
    {
        if( !$this->is_Admin() ) $this->redirectLogin();

        $publishpost = $this->model->publishPost($postId, $ispublished);

         // whitch post was deleted  : admin post or any post : helps sending the user back to where he comes from = adminposts or adminmyposts
         $whosePost = SessionManager::getInstance()->get('MYPOST');

         \Http::redirect("index.php?action=$whosePost&controller=postadmin");
                
    }


    # **************
    # Delete Post 
    #@param $posiId
    # **************


    public function deletePost($postId)
    {
        if( !$this->is_Admin() ) $this->redirectLogin();

        $commentManager = new \Models\CommentManager();

        $deletecomment = $commentManager->deleteCommentPost($postId,$idcomment = null, $userId = null); // DELETE COMMENTS RELATED TO THE POST
        $deletepost = $this->model->deletePost($postId); // DELETE POST

        //gerate a message according to the action process
        $messageDisplay = new \Inc\MessageDisplay();
        $messageDisplay->initmessage($action,$deletepost);        
        
        // whitch post was deleted  : admin post or any post : helps sending the user back to where he comes from = adminposts or adminmyposts
        $whosePost = SessionManager::getInstance()->get('MYPOST');

        \Http::redirect("index.php?action=$whosePost&controller=postadmin");
                
    }
}
<?php


// Chargement des classes


require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');

function listPosts()
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet
    $posts = $postManager->getPosts(); // Appel d'une fonction de cet objet

    require('view/frontend/listPostsView.php');
}

function post()
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $post = $postManager->getPost($_GET['id']);
    $comments = $commentManager->getComments($_GET['id'],'1');

    require('view/frontend/postView.php');
}

function addComment($postId, $author, $comment)
{
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $affectedLines = $commentManager->postComment($postId, $author, $comment);

	$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$affectedLines);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }
}


// EXERCICE TP COURS A SUPPRIMER

function modifyComment($commentId)
{
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $comment = $commentManager->getComment($commentId);

	require('view/frontend/commenteditView.php');

    /*if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }*/
}

// FIN EXERCICE TP COURS


# **************
        # BACK END
        # **************

# **************
        # Add New Post 
        # **************

		function verifytype(){
			
			$pseudo = $_SESSION['PSEUDO'];
			$action = $_GET['action'];
			$result = $_SESSION['RESULT'];

			if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 3)){

				$result1 = 0;
				initmessage($action,$result1);

				//require('view/backend/backblogmanage_old.php');
				//require('view/backend/loginView.php');
				if(isset($_SESSION['POSTID'] )){
					header('Location: index.php?action=post&id='.$_SESSION['POSTID']);
				}else{
					header('Location: view/backend/loginView.php');
				}

					
				}elseif(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){

					$result1 = 1;
					initmessage($action,$result1);

				//require('view/backend/backblogmanage_old.php');
				require('view/backend/backblogmanage_OK.php');

			}
			
			
			
		}

		# **************
        # Add New Post 
        # **************


		function addPostView(){

			

			require('view/backend/addpostView.php');

			
		}
        
		  /**
    * function addPostImage
    * verifie puis ajoute une image pour l'article
    */
    /*public function addPostImage($post_image){
      if ($post_thumbnail['error'] == 0){
        $allow_ext = array("jpg","png","gif","jpeg"); //extensions acceptÃ©e
        $ext = strtolower(substr($post_thumbnail['name'],-3)); //recupere l'extension

        if(in_array($ext,$allow_ext)){ //verifie l'extension sinon message erreur
          move_uploaded_file($post_thumbnail['tmp_name'], "public/img/".$post_thumbnail['name']);
          return true;
        }else{
          return $post_thumbnail;
        }
      }
      return false;
      }*/





function addPostImage($post_image){

	if (isset($post_image) && ($post_image['error'] == 0))
			{
//echo $post_image["name"];
//exit;
				$allowed_image_extension = array(	"png","jpg","jpeg");
				
				// Get image file extension
				$file_extension = pathinfo($post_image["name"], PATHINFO_EXTENSION);
				//echo $file_extension;
//exit;
			//try{
				// Validate file input to check if is not empty
				if (! file_exists($post_image["tmp_name"])) {
					//throw new Exception('Choose image file to upload!');
					$response = array(
						"type" => "error",
						"message" => "Choose image file to upload."
					);
				}   
				
				// Validate file input to check if is with valid extension
				else if (! in_array($file_extension, $allowed_image_extension)) {
					//throw new Exception('Upload valiid images. Only PNG, JPG and JPEG are allowed!');
					$message = "File upload stopped by extension";
					header('Location: index.php?action=addpostview&message='.$message);
					exit;
					//echo $result;
				}   
				
				// Validate image file size
				else if (($post_image["size"] > 2000000)) {
					//throw new Exception('Image size exceeds 2MB!');
					$message = "File upload Size issue";
					header('Location: index.php?action=addpostview&message='.$message);
					exit;
				}   
				
				else {
					$dossier = "uploads/images/" ;
					$file_name = basename($post_image["name"]);
					$target = $dossier.$file_name;
					if (file_exists($target)) {
						//$message =  'The image '.$post_image["name"].'  already exists in '.$dossier;
						$timestamp=time();
						$file_name = $timestamp.'-'.$file_name;
						$target = $dossier.$file_name;
//echo $target ;
						//header('Location: index.php?action=addpostview&message='.$message);
					//exit;
					    //header('Location: index.php?action=addpostview&message='.$message);
				    }
					if (! empty($message)){
						header('Location: index.php?action=addpostview&message='.$message);
						//exit;
					}else{
								if (move_uploaded_file($post_image["tmp_name"], $target)) {
									return $file_name;
								} else {
									echo 'Upload issue :';
									print_r($_FILES);
								}
					}
				}
			/*}
			catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}*/
			}
}

        function doAdd() {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet

            $post = $_POST;

			// UPLOAD POST IMAGE
			if(isset($_FILES)){
				$status = $_FILES['pimage']['error'];

//echo $status ;

// an error occurs
if ($status == UPLOAD_ERR_OK) {
  // echo $status ;
  // exit;
		//echo 'fichier  '.$_FILES['pimage']['name'];
		//exit;
		$post_image = $_FILES["pimage"];
		$pimage=addPostImage($post_image);
			//}
//else exit;
	try{
			/*if ($pimage === false) { 

				//echo 'false : '.$pimage;
				//exit;
				throw new Exception('Impossible d\'uploader l\'image!');
			 }
			 else {
					 $pimage_name=basename($post_image["name"]);
			 }*/
			$post_userid = 1; // A REPLACER PAR id_user DU USER AUTHENTIFIE STOQUE DANS $_SESSION
            $post_title = htmlspecialchars(trim($post['title']));
			$post_lede = htmlspecialchars(trim($post['lede']));
			$post_author = htmlspecialchars(trim($post['author']));
            $post_content = htmlspecialchars(trim($post['content']));
			$is_enabled="1";
            
			//echo $post_content;
			//exit;
			
            $result=$postManager->addPost( $post_userid, $post_title, $post_lede, $post_author, $post_content, $pimage, $is_enabled);
			$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$result);
            
             if ($result === false) {
				 $_SESSION['postaddmessage'] = 0;
				throw new Exception('Impossible d\'ajouter le post !');
			 }
			 else {
					
				$_SESSION['postaddmessage'] = 1;
				 header('Location: index.php?action=addpostview');
			 }

	}
	catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}
			}  else {switch ($status) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
			}
					//$err_message = $status;     
					 header('Location: index.php?action=addpostview&message='.$message);
			 } 
        }
		}

# **************
        #  LISTS PostS  FOR Update   OR DELETE
        # **************
        function listPostsUpdate()
			{
				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet
				$posts = $postManager->getPosts(); // Appel d'une fonction de cet objet

				require('view/backend/listPostsView.php');
			}

			# **************
        #  LISTS Comments  To Validate   OR DELETE
        # **************

        function listCommentsValidate()
			{
				$CommentManager = new \OC\PhpSymfony\Blog\Model\CommentManager(); // Création d'un objet
				$commentsvalidate = $CommentManager->getComments(null,'0'); // Appel d'une fonction de cet objet

				require('view/backend/listCommentsView.php');
			}


		# **************
        #  LISTS Comments  To Validate   OR DELETE
        # **************
		
		function CommentValidate($commentId)
			{
				$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

				$validateComment = $commentManager->validateComment($commentId);

				//require('view/frontend/commenteditView.php');
				header('Location: index.php?action=commentsadmin');
				$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$validateComment);

				/*if ($affectedLines === false) {
					throw new Exception('Impossible d\'ajouter le commentaire !');
				}
				else {
					header('Location: index.php?action=post&id=' . $postId);
				}*/
			}


		function modifyPost($postId)
			{

				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();

				$post = $postManager->getPost($postId);

				require('view/backend/posteditView.php');

					
				}

		# **************
        # Edit Post 
        # **************

		function userLogout() {        
			
			if( isset($_SESSION) ){
				unset( $_SESSION );
				session_destroy();
				header('Location: home.php');
			}
			
        }


# **************
        # Delete Post 
        # **************



        function deletepost($postId) {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

			$deletecomment = $commentManager->deleteCommentPost($postId);
			$deletepost = $postManager->deletePost($postId);

			$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$deletepost);
			
			/*// if Post id is defined and the Post was deleted 

			if (isset($postId) && (! $deletepost )) {
				//$message = 0;
				$_SESSION['actionmessage'] = 0;
			}else if (isset($postId) && ($deletepost )) {
				//$message = 1;
				$_SESSION['actionmessage'] = 1;
			}
			*/
			//$_SESSION['actionmessage'] = $message;
			/*echo $_SESSION['message'] ;
			exit;*/
			//require('view/backend/listPostsView.php');
			header('Location: index.php?action=adminposts');
            
        }

		# **************
        # User Add View
        # **************
		function adduserView(){

			

			require('view/backend/adduserView.php');

			
		}
        
		

		# **************
        # User Add
        # **************



        function addUser() {

			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet

            $post = $_POST;

			// TESTER SI L'EMAIL EXISTE DANS LA BDD SI OUI EXIT AVEC MESSAGE
		
			$verifyemail = $userManager->VerifyUserEmail($email);
			if ($verifyemail) 
				{
					//$_SESSION['postaddmessage'] = 0;
					throw new Exception('Impossible d\'ajouter l\'utilisateur!');
				}
			 

			
			$post_usertypeid = 5; // A REPLACER PAR id_user DU USER AUTHENTIFIE STOQUE DANS $_SESSION
            $usertype_id = htmlspecialchars(trim($post['usertype_id']));
			$pseudo = htmlspecialchars(trim($post['pseudo']));
			$email = htmlspecialchars(trim($post['email']));
            $password = htmlspecialchars(trim($post['password']));
			$is_enabled="1";
			/**/
            
			//echo $post_content;
			//exit;
			
           // 
			$result=$userManager->registerUser($usertype_id, $pseudo, $email, $password);
			$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$result);
            
             if ($result === false) {
				 $_SESSION['postaddmessage'] = 0;
				throw new Exception('Impossible d\'ajouter l\'utilisateur!');
			 }
			 else {
					
				$_SESSION['postaddmessage'] = 1;
				 header('Location: index.php?action=adduserview');
			 }
            
        }

		# **************
        # Display user Login form
        # **************



        function loginView() {

			require('view/backend/loginView.php');

        }

		# **************
        # Verify User  Login
        # **************



        function verifyLogin() {

			
			//EXIT;

			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet

            $post = $_POST;
			$action = $_GET['action'];
//echo ' ENTRE VERIFYLOGIN()  action  : '.$action;
			// TESTER LES VARIABLES RECUE DEPUIS LE FORM

			$post_email =  $post['email'];
			$post_password =  $post['password'];

			$result=$userManager->loginUser($post_email, $post_password) ;	
			//var_dump($result);
			initmessage($action,$result);

			//gerate a message according to the action process
			if($result){


					$_SESSION['USERTYPEID'] = $result['usertype_id'];
					$_SESSION['PSEUDO'] = $result['pseudo'];
					$_SESSION['RESULT'] = $result;
					//echo 'ENTRE '.$_SESSION['PSEUDO'].'  '.$_SESSION['IS_LOGGED'].'   '.$_SESSION['RESULT']['email'];
					//exit;
					
					header('Location: index.php?action=backblogmanage');

			}else{
					header('Location: index.php?action=loginview');
			}

			/*// TESTER SI L'EMAIL EXISTE DANS LA BDD SI OUI EXIT AVEC MESSAGE
		
			$verifyemail = $userManager->getUserFromEmail($email);
			if ($verifyemail) 
				{
					//$_SESSION['postaddmessage'] = 0;
					throw new Exception('Impossible d\'ajouter l\'utilisateur!');
				}
			 */

			
			
			/*
			$email = htmlspecialchars(trim($post['email']));
            $password = htmlspecialchars(trim($post['password']));
			
			*/
            
			//echo $post_content;
			//exit;
			
           // 
					

        }

		# **************
        # Dsplay Action Message
        # **************



        function initmessage($action,$result) {
			
			switch ($action) {
			
			case 'addcomment':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Comment Adding failed !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {
					$_SESSION['actionmessage'] = 'Votre Commentaire a été enregisté et sera publié après Validation !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;

            case 'addpost':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Post Adding failed !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {
					$_SESSION['actionmessage'] = 'Success Post Added !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;
			
            case 'updatepost':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Post Update failed !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {
					$_SESSION['actionmessage'] = 'Success Post Updated !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;
			//}
			case 'deletepost':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Post Delete failed !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {
					$_SESSION['actionmessage'] = 'Success Post Deleteted !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;

				case 'useradd':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Failed to Add User !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {
					$_SESSION['actionmessage'] = 'Success User Added !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;

				case 'verifylogin':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Failed Bad Login Or Password !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {
					$_SESSION['actionmessage'] = 'Success User Logged !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;

				case 'backblogmanage':
				if ( $result == 1) {
					$_SESSION['actionmessage'] = 'Success Welcome to the BackEnd Management  !';
					$_SESSION['alert_flag'] = 1;
				 }
				elseif ( $result == 0) {
					$_SESSION['actionmessage'] = 'Success you Are connected !';
					$_SESSION['alert_flag'] = 1;
				 }
                
                break;
			 
			}
			//echo 'init '.$action.' '.$_SESSION['actionmessage'].' '.$_SESSION['alert_flag'];
			//exit;
            
        }
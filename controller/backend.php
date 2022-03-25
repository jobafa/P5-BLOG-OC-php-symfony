<?php

// Chargement des classes


require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');

# **************
        # BACK END
        # **************

# **************
        # Verify type of user ( level ) : Admin or Guest 
        # **************

		function verifytype(){

			if(isset($_SESSION['FROM'])){
				$from = $_SESSION['FROM'];
			}
					
			$pseudo = $_SESSION['PSEUDO'];
			$action = $_GET['action'];
			$result = $_SESSION['RESULT'];

			if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 3)){

					$result1 = 0;
					initmessage($action,$result1);

					//require('view/backend/backblogmanage_old.php');
					//require('view/backend/loginView.php');

					// IF GUSET AND COMES FROM A POST PAGE THEN SEND BACK TO THE POST PAGE

					if(isset($_SESSION['POSTID'] )){
						
						header('Location: index.php?action=frontpost&id='.$_SESSION['POSTID']);
						exit;
						//var_dump($_SESSION['POSTID']);exit;
					}else{
						// IF GUSET AND DOES NOT COME FROM A POST PAGE : SEND TO DASHBOARD PAGE
						//var_dump($_SESSION['USERTYPEID']);exit;
						header('Location: index.php?action=mycomments');
						
						//require('view/backend/backblogmanage_OK.php');

						//header('Location: view/backend/loginView.php');
						// require('view/backend/loginView.php');
					}

					
				}elseif(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){

					$result1 = 1;
					initmessage($action,$result1);

					// IF GUSET AND COMES FROM A POST PAGE THEN SEND BACK TO THE POST PAGE

					if(isset($_SESSION['POSTID'] ) ){
						
						header('Location: index.php?action=post&id='.$_SESSION['POSTID']);
					}else{
						// IF GUSET AND DOES NOT COME FROM A POST PAGE : SEND TO DASHBOARD PAGE

						header('Location: index.php?action=adminposts');
						
						//require('view/backend/backblogmanage_OK.php');

						//header('Location: view/backend/loginView.php');
						// require('view/backend/loginView.php');
					}

					//require('view/backend/backblogmanage_old.php');
					//require('view/backend/backblogmanage_OK.php');
					//header('Location: view/backend/backblogmanage_OK.php');

				}
			
			
			
		}

		# **************
        # Add New Post 
        # **************


		function addPostView(){

			

			require('view/backend/addpostView.php');

			
		}
        
	


        function doAdd() {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet

            $post = $_POST;

			// UPLOAD POST IMAGE
			if(isset($_FILES)){
				$status = $_FILES['pimage']['error'];

				// an error occurs
				if ($status == UPLOAD_ERR_OK) {
				  
						$post_image = $_FILES["pimage"];
						$pimage=addImage($post_image);
							
					try{
							
							$post_userid = $_SESSION['USERID']; // A REPLACER PAR id_user DU USER AUTHENTIFIE STOQUE DANS $_SESSION
							$post_title = htmlspecialchars(trim($post['title']));
							$post_lede = htmlspecialchars(trim($post['lede']));
							$post_author = htmlspecialchars(trim($post['author']));
							$post_content = htmlspecialchars(trim($post['content']));
							$is_enabled="1";
							
							
							$result=$postManager->addPost( $post_userid, $post_title, $post_lede, $post_author, $post_content, $pimage, $is_enabled);
							$action = $_GET['action'];

							//Genrate a message according to the action process
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
			   }else{
							
						switch ($status) {
							case UPLOAD_ERR_INI_SIZE:

								//$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
								$_SESSION['actionmessage'] = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
								$_SESSION['alert_flag'] = 0;
								break;
							case UPLOAD_ERR_FORM_SIZE:
								//$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
								$_SESSION['actionmessage'] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
								$_SESSION['alert_flag'] = 0;
								
								break;
							case UPLOAD_ERR_PARTIAL:
								//$message = "The uploaded file was only partially uploaded";
								$_SESSION['actionmessage'] = "The uploaded file was only partially uploaded";
								$_SESSION['alert_flag'] = 0;
								break;
							case UPLOAD_ERR_NO_FILE:
								//$message = "No file was uploaded";
							 $_SESSION['actionmessage'] = "No file was uploaded";
								$_SESSION['alert_flag'] = 0;
								break;
							case UPLOAD_ERR_NO_TMP_DIR:
								//$message = "Missing a temporary folder";
								$_SESSION['actionmessage'] = "Missing a temporary folder";
								$_SESSION['alert_flag'] = 0;
								break;
							case UPLOAD_ERR_CANT_WRITE:
								//$message = "Failed to write file to disk";
								$_SESSION['actionmessage'] = "Failed to write file to disk";
								$_SESSION['alert_flag'] = 0;
								break;
							case UPLOAD_ERR_EXTENSION:
								//$message = "File upload stopped by extension";
								$_SESSION['actionmessage'] = "File upload stopped by extension";
								$_SESSION['alert_flag'] = 0;
								break;

							default:
								//$message = "Unknown upload error";
								$_SESSION['actionmessage'] = "Unknown upload error";
								$_SESSION['alert_flag'] = 0;
								break;
						}
								//$err_message = $status;     
								 //header('Location: index.php?action=addpostview&message='.$message);
								 require('view/backend/addpostView.php');
			   } 
	        }
        }

# **************
        #  LISTS Posts  FOR Activate, disactivate, Update or DELETE
		# @param userid or null if listing all posts
        # **************
        function listPostsUpdate($userid = null)
			{
				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet
				$posts = $postManager->getPosts($userid,$from = null, $is_published = null, $paginationStart = null, $limit = null); // Appel d'une fonction de cet objet

				//var_dump($posts); 
//$posts = listPostsUpdate($userid);
//exit;
				if(! isset($action)){	
					
					$posts = $postManager->getPosts($userid,$from = null, $is_published = null, $paginationStart = null, $limit = null); // Appel d'une fonction de cet objet

					
					require('view/backend/backblogmanage_OK.php');
					
					return $posts;
				}
				else{/**/
				
				require('view/backend/backblogmanage_OK.php');
				}
			}


		
			# **************
        #  LISTS Comments  To Validate   OR DELETE
        # **************

        function myComments()
			{
				
				// CHEKS IF USER IS A GUEST

				if(isset( $_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 3)){
					$userid= $_SESSION['USERID'];
				}
				
				$CommentManager = new \OC\PhpSymfony\Blog\Model\CommentManager(); // Création d'un objet
				$commentsvalidate = $CommentManager->getComments($postId = null, '0', $userid = null); // Appel d'une fonction de cet objet
				
				//require('view/backend/listCommentsView-old_OK.php');
				require('view/backend/listCommentsView.php');
			}

			# **************
        #  LISTS Comments  To Validate   OR DELETE
        # **************

        function listCommentsValidate()
			{//var_dump($_SESSION['USERTYPEID']);exit;
				//$CommentManager = new \OC\PhpSymfony\Blog\Model\CommentManager(); // Création d'un objet
				//$commentsvalidate = $CommentManager->getComments($postId = null,'0'); // Appel d'une fonction de cet objet
				
				$CommentManager = new \OC\PhpSymfony\Blog\Model\CommentManager(); // Création d'un objet
				
				// CHEKS IF USER IS A GUEST
				if(isset( $_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 3)){
					//var_dump($_SESSION['USERTYPEID']);exit;
					$userid= $_SESSION['USERID'];
					$commentsvalidate = $CommentManager->getComments( $postId = null, $isenabled = null,$userid ); // Appel d'une fonction de cet objet

				}else{
					$commentsvalidate = $CommentManager->getComments($postId = null, '0'); // Appel d'une fonction de cet objet
				}

				//require('view/backend/listCommentsView-old_OK.php');
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

				$post = $postManager->getPost($postId, $is_published = null);

				require('view/backend/posteditView.php');

					
				}


			function updatePost($id){
				
					// UPLOAD POST IMAGE

					if(isset($_FILES)){
						$status = $_FILES['pimage']['error'];

						// an error occurs
						if ($status == UPLOAD_ERR_OK) {
						  
								$post_image = $_FILES["pimage"];
								$pimage=addImage($post_image);
						}elseif	($status == UPLOAD_ERR_NO_FILE){ // NO FILE UPLOADED : NO PHOTO
								$pimage = '';
									
							
						}else{
							
									switch ($status) {
										case UPLOAD_ERR_INI_SIZE:

											//$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
											$_SESSION['actionmessage'] = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
											$_SESSION['alert_flag'] = 0;
											break;
										case UPLOAD_ERR_FORM_SIZE:
											//$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
											$_SESSION['actionmessage'] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
											$_SESSION['alert_flag'] = 0;
											
											break;
										case UPLOAD_ERR_PARTIAL:
											//$message = "The uploaded file was only partially uploaded";
											$_SESSION['actionmessage'] = "The uploaded file was only partially uploaded";
											$_SESSION['alert_flag'] = 0;
											break;
										case UPLOAD_ERR_NO_FILE:
											//$message = "No file was uploaded";
										 $_SESSION['actionmessage'] = "No file was uploaded";
											$_SESSION['alert_flag'] = 0;
											break;
										case UPLOAD_ERR_NO_TMP_DIR:
											//$message = "Missing a temporary folder";
											$_SESSION['actionmessage'] = "Missing a temporary folder";
											$_SESSION['alert_flag'] = 0;
											break;
										case UPLOAD_ERR_CANT_WRITE:
											//$message = "Failed to write file to disk";
											$_SESSION['actionmessage'] = "Failed to write file to disk";
											$_SESSION['alert_flag'] = 0;
											break;
										case UPLOAD_ERR_EXTENSION:
											//$message = "File upload stopped by extension";
											$_SESSION['actionmessage'] = "File upload stopped by extension";
											$_SESSION['alert_flag'] = 0;
											break;

										default:
											//$message = "Unknown upload error";
											$_SESSION['actionmessage'] = "Unknown upload error";
											$_SESSION['alert_flag'] = 0;
											break;
									}
								//$err_message = $status;     
								 header('Location: index.php?action=modifypost&id='.$id);
								 //require('view/backend/addpostView.php');
						 } 
					}
					try{
						$post = $_POST;
						$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
						$affectedLines = $postManager->updatePost($id, $post, $pimage);
															
						initmessage($action,$affectedLines);

						 if ($affectedLines === false) {
							throw new Exception('Impossible de mettre à jour le post !');
						 }
						 else{
							//header('Location: index.php?action=post&id=' . $id .'&post_id='.$_GET['post_id'] );
							header('Location: index.php?action=postsupdate');// BACK TO  POSTS ADMIN
						 }
					}
					catch(Exception $e) {
						$errorMessage = $e->getMessage();
						require('view/errorView.php');
					}					
			}

		# **************
        # Edit Post 
        # **************

		function userLogout() {        
			
			if( isset($_SESSION) ){
				unset( $_SESSION['USERID']);
				unset( $_SESSION['USERTYPEID']);
				unset( $_SESSION );
				session_destroy();
				header('Location: accueil.html');
			}
			
        }

		# **************
        #  Post Acivation
        # **************

		 function postpublish($postId, $ispublished) {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
			

			$publishpost = $postManager->publishPost($postId, $ispublished);

			//$action = $_GET['action'];

			//gerate a message according to the action process
			//initmessage($action,$deletepost);
			
			
			//require('view/backend/listPostsView.php');
			header('Location: index.php?action=adminposts');
            
        }


# **************
        # Delete Post 
        # **************



        function deletepost($postId) {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

			//$deletecomment = $commentManager->deleteCommentPost($idcomment = null, $idpost = null);
			$deletecomment = $commentManager->deleteCommentPost($postId,$idcomment = null);
			$deletepost = $postManager->deletePost($postId);

			//$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$deletepost);
			
			
			//require('view/backend/listPostsView.php');
			header('Location: index.php?action=backblogmanage');
            
        }

		function deleteComment($commentid) {

			
			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
//var_dump($_SESSION['USERID']);exit;
			if(isset($_SESSION['USERID'])){
				$userid = $_SESSION['USERID'];
			}
			$deletecomment = $commentManager->deleteCommentPost($idpost = null , $commentid,$userid = null);
			//

			//gerate a message according to the action process
			$action = $_SESSION['ACTION'];
			initmessage($action,$deletecomment);
			
			
			//require('view/backend/listCommentsView.php');
			header('Location: index.php?action=commentsadmin');
            
        }

		# **************
        # User Add View
        # **************
		function adduserView($action){

			//echo 'yes'.$action;
			if(isset($action) && ($action == "signinview")){
						
					require('view/frontend/signinView.php');

			}elseif(isset($action) && ($action == "adduserview")){
//echo 'yes';
					require('view/backend/adduserView.php');

			}

			
		}
        
		

		# **************
        # User Add
        # **************



        function addUser($post, $URL, $token_name) {
			
			//  errors is the array that contains data input validation errors
			unset($_SESSION['errors']);

			$action =$_SESSION['ACTION'];
			if($action == 'usersignin'){

				$check_token = check_token(600,  $URL.'signinview.html?inscription', $token_name);

			}if($action == 'useradd'){ // for admin user

				$check_token = "ras";
			}
			
			if($check_token)
			 {
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);

					$pseudo =  $post['pseudo'];
					$email =  $post['email'];
					$password =  $post['password'];

					$inputs = [
						'pseudo' => $pseudo,
						'email' => $email,
						'password' => $password
						];

					$fields = [
						'pseudo' => 'string',
						'email' => 'email',
						'password' => 'string'
						
					];

					$data = sanitize_inputs($inputs,$fields);

					/*$data = [
						'email' => $email,
						'password' => $password
					];*/

					$fields = [
						'pseudo' => 'required',
						'email' => 'required',
						//'email' => 'required|email|unique:user,email',
						'password' => 'required'
						//'password' => 'required | secure'
					];
					//$data = sanitize_inputs($inputs,$fields);
					$errors = validate($data, $fields);	
					/*$errors = validate($data, $fields, [
						'required' => 'Le champ %s est requis',
						'password2' => ['same'=> 'Merci de saisir le même mot de passe']]
					);
					*/

					if(!empty($errors)){
						$_SESSION['errors'] = $errors;
						header('location: usersignin.html?inscription');
						//var_dump($errors);
					}/*else{
						echo 'clean';
					}
					exit;*/
				}else{
var_dump($check_token);
var_dump($data );
var_dump($_SERVER['HTTP_REFERER']  );

exit;

					$action = "tokenlife";
						
				
			
			
					 initmessage($action,$check_token);

					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 header('location: usersignin.html?inscription');
					 }
			 }
			
			// NO ERRORS GO ON PROCESS
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Objet creation
			
			// GET DATA FROM THE SANITIZED AND VALDATED POST DATA ARRAY

          	$pseudo =  $data['pseudo'];
			$email =  $data['email'];
			$password =  $data['password'];
			
			// TESTS IF EMAIL ALREADY EXISTS
			$verifyemail = $userManager->VerifyUserEmail($email);
			

			if ($verifyemail) 
				{ 
					$emailexists = 'email';
					$action = $_SESSION['ACTION'];
					
					
					initmessage($action,$emailexists);

					if( isset($_SESSION['USERTYPEID'])){ // USER IS CONNECTED
						
						if($_SESSION['USERTYPEID'] == '3'){ // USER IS A GUEST
							
							header('Location: index.php?action=signinview');

						}elseif($_SESSION['USERTYPEID'] == '1'){ // USER IS AN ADMIN
							
							header('Location: index.php?action=adduserview');
						}

					}else{ // USER IS NOT CONNECTED

						header('Location: signinview.html?inscription');
					}

				}else{// EMAIL DOES NOT EXIST IN DB
	 
				
				// CHECKING FILE UPLOAD

					if(isset($_FILES)){
						
						$status = $_FILES['photo']['error'];
						$photo = checkUploadStatus($status);
						
						
					} // END OF  FILE UPLOAD CHECKING

					try{
			
									/*****************
									** Initialize $usertype_id according to wether we are comming from the frontend or the backend  user AddForm		
									**********************/

									if($action == 'usersignin'){
										$usertype_id = "3";
									}elseif($action == 'useradd'){
										$usertype_id = htmlspecialchars(trim($post['usertype_id']));
									}
									
									$pseudo = htmlspecialchars(trim($post['pseudo']));
									
									$password = htmlspecialchars(trim($post['password']));
									$is_enabled="1";

									// MUST ADD TOKEN EXPIRATION DATE TO USER'S ACTIVATION 

									$token = password_hash($pseudo,PASSWORD_DEFAULT);
								   
									$result = $userManager->registerUser($usertype_id, $pseudo, $email, $password, $photo,  $token);

									if ($result ) {
										
										$id = $_SESSION['LASTUSERID'];
										
										 UserActivationEmail( $email,$pseudo, $id,  $token);
										
									 }
									 
									$action = $_SESSION['ACTION'];

									//gerate a message according to the action process
									
									initmessage($action,$result);
									
									 
									 if($action == 'usersignin'){
										 //header('Location: index.php?action=signinview');
										 header('Location: signinview.html?inscription');
									}elseif($action == 'useradd'){
										header('Location: index.php?action=adduserview');
									}
							}

							
							catch(Exception $e) {
									$errorMessage = $e->getMessage();
									require('view/errorView.php');
							}
	}
}
       // }


			/**
	 * Sending  account activation email  to user after inscription
	 * @param  Parameter $post [pseudo, email]
	 * @param  string $activation_code  
	 */

	function UserActivationEmail( $email,$pseudo, $id, $token)
	{
		$subject = "Activation de votre compte";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		$message = "Bonjour " . $pseudo. ", bienvenue sur mon blog !\r\n\r\nPour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/index.php?action=useractivation&id=" . $id . "&link_emaill=" . $email . "&token=" . $token . "\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";

		//$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);
		//echo $activation_code;
	}


	/**
	 * Get email and activation key from activation link and call userManager tocheck correspondance in database. Delete activation_code from database to activate user account.
	 * @param  Parameter $get [email, activation_code]
	 * 
	 */

	function userActivation($userid, $email, $token, $isactivated)
	 {
		/*ECHO 'ENTRE '.$isactivated;
				EXIT;*/
		//if ((isset($action)) && ($action ==  'useractivation')){

						
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet
			//$result=$userManager->getUseractivationcode($userId, $token);
			if(isset($token) && ($token  != null)){

				$result=$userManager->getUseractivationcode($userid);

			}elseif(isset($isactivated)){// WE ARE NOT COMING FROM USER'S ACTIVATION EMAIL = COMING FROM ADMIN DASHBOARD
				
				
				$result = false;

				if($isactivated == 'on'){

				

					$token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

					$token = password_hash($token,PASSWORD_DEFAULT);

				}elseif($isactivated == 'off'){

					$token == null;
				}

				

			}
			// USING PARAMETERS EMAIL ( INSTED OF USERID ) AND TOKEN = DELETE PARAMETER ID IN EMAIL LINK SENT TO USER FOR ACCOUNT ACTIVATION

			$result2 = $userManager->userActivate($email, $token); 

			//$result=$userManager->getUseractivationcode(30, '$2y$10$odDAbI2lvnk9OGxJRbU8Ju7DPQqiKFZjoB3ZUmVWlzY3X9qBUl6t');


		// APPEL DE  getUseractivationcode AVEC L'ID  PUIS FAIRE LES TEST SUR $token AU RETOUR DE L'PPEL DE FONCTION

			if ( $result)  {

				if(($token == $result['is_activated']) || (($token == null) && (isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)))){
					
					$result2 = $userManager->userActivate($userid);
				}
				
				//$result2 = $userManager->userActivate('30');
				
				if($result2){
					$_SESSION['actionmessage'] = 'Félicitations ! Votre compte vient d\'&ecirc;tre  activ&eacute;';
					$_SESSION['alert_flag'] = 1;
				}else{
					$_SESSION['actionmessage'] = 'Désolé ! Probléme lors de l\'activation de votre compte. <BR>Merci de contacter l\'administrateur via le formulaire de contact ';
					$_SESSION['alert_flag'] = 1;
				}
			}elseif($result['is_enabled'] == NULL){

				$_SESSION['actionmessage'] = 'Votre compte est d&egrave;j&aacute;  activ&eacute;';
				$_SESSION['alert_flag'] = 1;
			}
			elseif($result['is_enabled'] != NULL){

				$_SESSION['actionmessage'] = 'Mauvaise cl&eacute; d\'activativation';
				$_SESSION['alert_flag'] = 0;
			}
			if(! isset($isactivated)){
				require('view/frontend/loginView.php');
			}else{
				//require('view/backend/listusersView.php');	
				header('Location: index.php?action=usersadmin');
			}
	 }

	/**
	 * Get email and activation key from activation link and call userManager tocheck correspondance in database. Delete activation_code from database to activate user account.
	 * @param  Parameter $get [email, activation_code]
	 * 
	 TO BE DELETED WHEN DONE WITH function userActivation($userid,$token = null,$isactivated = null)  ************/

	function userActivation_OK($userid,$token = null,$isactivated = null)
	 {
		
		//if ((isset($action)) && ($action ==  'useractivation')){

						
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet
			//$result=$userManager->getUseractivationcode($userId, $token);
			if($token != null){
				$result=$userManager->getUseractivationcode($userid);
			}
			//$result=$userManager->getUseractivationcode(30, '$2y$10$odDAbI2lvnk9OGxJRbU8Ju7DPQqiKFZjoB3ZUmVWlzY3X9qBUl6t');


		// APPEL DE  getUseractivationcode AVEC L'ID  PUIS FAIRE LES TEST SUR $token AU RETOUR DE L'PPEL DE FONCTION

			if ( $result)  {

				if(($token == $result['is_activated']) || (($token == null) && (isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)))){
					
					$result2 = $userManager->userActivate($userid);
				}
				
				//$result2 = $userManager->userActivate('30');
				
				if($result2){
					$_SESSION['actionmessage'] = 'Félicitations ! Votre compte vient d\'&ecirc;tre  activ&eacute;';
					$_SESSION['alert_flag'] = 1;
				}else{
					$_SESSION['actionmessage'] = 'Désolé ! Probléme lors de l\'activation de votre compte. <BR>Merci de contacter l\'administrateur via le formulaire de contact ';
					$_SESSION['alert_flag'] = 1;
				}
			}elseif($result['is_enabled'] == NULL){

				$_SESSION['actionmessage'] = 'Votre compte est d&egrave;j&aacute;  activ&eacute;';
				$_SESSION['alert_flag'] = 1;
			}
			elseif($result['is_enabled'] != NULL){

				$_SESSION['actionmessage'] = 'Mauvaise cl&eacute; d\'activativation';
				$_SESSION['alert_flag'] = 0;
			}

			require('view/frontend/loginView.php');	
	 }




		# **************
        # Display user Login form
        # **************



       /* function loginView() {

			require('view/backend/loginView.php');

        }*/

		# **************
        # Display user's password reset Requesting form
        # **************



      /*  function passresetRequest() {

			require('view/frontend/passresetView.php');

        }*/

			/**
	 * Get user's email 
	 * @param  Parameter $email
	 * 
	     function passReset($post, $URL, $token_name) {

			// CHECK LOGIN CSRF TOKEN
			$check_token = check_token(600,  $URL.'passresetview.html', $token_name);
			//var_dump($check_token);
			
			if($check_token)
			 {
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);

					$email =  $post['email'];
					//$password =  $post['password'];

					$data = [
						'email' => $email
					];

					$fields = [
						'email' => 'required | email | unique: users,email'
						];

				//	$errors = validate($data, $fields);	
					//$errors = validate($data, $fields, [
					//	'required' => 'Le champ %s est requis',
					//	'password2' => ['same'=> 'Merci de saisir le même mot de passe']]
					//);
					

					if(!empty($errors)){
						$_SESSION['errors'] = $errors;
						header('location: passresetview.html');
						
					}
				}else{

					$action = "tokenlife";
						
				
			
			
					 initmessage($action,$check_token);

					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 header('location: passresetview.html');
					 }
			 }
			
			if(empty($errors)){
				$action =$_SESSION['ACTION'];

				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet

						
				//$email = htmlspecialchars(trim($post['email']));
				
				// TESTS IF EMAIL  EXISTS
				$verifyemail = $userManager->VerifyUserEmail($postemail);
				

				if ($verifyemail) 
					{ 

						$token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
						$token = password_hash($token,PASSWORD_DEFAULT);

						$userid = $verifyemail['id'];
							
						$passreset = $userManager->resetPassTokenInsert($userid, $token);
						
						//var_dump($verifyemail);
							
						if ($passreset ) {//var_dump($passreset);exit;	

							$pseudo = $verifyemail['pseudo'];
							
							PassRestEmail( $postemail,$pseudo, $userid,  $token);
										
						}
						$action = $_SESSION['ACTION'];
						initmessage($action,$passreset);

				}

						require('view/backend/passresetView.php');
			}
        }
*/

			/**
	 * Get email and reset password token from users email link and verify if the same token in user's record and if it is not expired
	 if OK displays reset password form
	 * @param  Parameter $link_email $link_token
	 * 
	 

        function verifyPassresetToken($link_email,$link_token){

			$action =$_SESSION['ACTION'];

			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet

          			
			
			$verifyemailtoken = $userManager->VerifyEmailToken($link_email, $link_token);
			

			if ($verifyemailtoken) 
				{ 

					$action = $_SESSION['ACTION'];
					initmessage($action,$verifyemailtoken);

				}

					require('view/backend/passreinitView.php');

        }*/

	/**
	 * Get data of new password form and verify if password and confirmed password are equal
	 * @param  Parameter $post
	 * 
	 

        function getNewPass($post){

			

			if($post['newpassword'] == $post['confirmnewpassword']){
				$newpass = $post['newpassword'];
				$link_email = $post['email'];
				$link_token = $post['reset_link_token'];

				//$userpassword = $user_password;

				$hash = password_hash($newpass,PASSWORD_DEFAULT);
				
				$newpass = $hash;

				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet


				$insertNewPass = $userManager->updatePass($newpass, $link_email, $link_token);

				$action = $_SESSION['ACTION'];
				initmessage($action,$insertNewPass);

				if ($insertNewPass) 
				{ 
				
					require('view/frontend/loginView.php');

				}
			
			}else{
				$action = $_SESSION['ACTION'];
				initmessage($action,$insertNewPass = false);

				require('view/backend/passreinitView.php');
			}
			

			

					

        }
*/
			/**
	 * Sending  reset password token email  to user after password forgot
	 * @param  Parameter $email,$pseudo, $id, $token
	 * 
	 

	function PassRestEmail( $email,$pseudo, $id, $passreset_token)
	{
		$subject = "R&eacute;initialisation de votre Mot de Passe";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		$message = "Bonjour " . $pseudo. ", Pour R&eacute;initialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttp://ocblog.capdeco.com/index.php?action=passreinitialisation&email=". $email."&token=" . $passreset_token . "\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";

		//$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);
		//echo $activation_code;
	}
*/
		/**
	 * Sending  account activation email  to user after inscription
	 * @param  Parameter $post [pseudo, email]
	 * @param  string $activation_code  
	 

	function PassResetEmail( $email,$pseudo, $id, $passreset_token)
	{
		$subject = "Activation de votre compte";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		$message = "Bonjour " . $pseudo. ", bienvenue sur mon blog !\r\n\r\nPour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttp://ocblog.capdeco.com/index.php?action=useractivation&id=" . $id . "&token=" . $token . "\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";

		//$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);
		//echo $activation_code;
	}*/

		/**
	 * Get user's information for update.
	 * @param  Parameter $userid
	 * 
	 */

	function userProfile($userid)
	 {
			
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Create objet
			
			//$userid = $_SESSION['USERID'];
			$profile = $userManager->getUser($userid);
			
			require('view/backend/usereditView.php');	
	 }

	/**
	 * Get user's  for,Admin.
	  */

	function usersAdmin()
	 {
			
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Create objet
			
			
			$getusers = $userManager->getUser();
			/*var_dump($getusers);
			exit;*/
			require('view/backend/listusersView.php');	
	 }


	 	/**
	 * Get user's information for update.
	 * @param  Parameter $userid
	 * 
	 */

	function userupdate($post,$id)
	 {
			
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Create objet
			
			$userid = $id;

			if(isset($_FILES)){
				$status = $_FILES['photo']['error'];

				// UOLOAD OK OR NO UPLOAD
				if (($status == UPLOAD_ERR_OK) ||  ($status == UPLOAD_ERR_NO_FILE) ){
				  
						$post_image = $_FILES["photo"];
						$photo=addImage($post_image);
						
						try{
								$updateprofile = $userManager->userUpdateProfile($userid, $post, $photo);

								$action = $_SESSION['ACTION'];

								//generate a message according to the action in process
								
								initmessage($action,$updateprofile);

								if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){
									
									header('Location: index.php?action=usersadmin');
								}

								require('view/backend/usereditView.php');	

						}

						catch(Exception $e) {
										$errorMessage = $e->getMessage();
										require('view/errorView.php');
						}
				}else { // UOLOAD ERROR
					
						switch ($status) {
						case UPLOAD_ERR_INI_SIZE:

							//$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
							$_SESSION['actionmessage'] = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
							$_SESSION['alert_flag'] = 0;
							break;
						case UPLOAD_ERR_FORM_SIZE:
							//$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
							$_SESSION['actionmessage'] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
							$_SESSION['alert_flag'] = 0;
							
							break;
						case UPLOAD_ERR_PARTIAL:
							//$message = "The uploaded file was only partially uploaded";
							$_SESSION['actionmessage'] = "The uploaded file was only partially uploaded";
							$_SESSION['alert_flag'] = 0;
							break;
						/*case UPLOAD_ERR_NO_FILE:
							//$message = "No file was uploaded";
						 $_SESSION['actionmessage'] = "No file was uploaded";
							$_SESSION['alert_flag'] = 0;
							break;*/
						case UPLOAD_ERR_NO_TMP_DIR:
							//$message = "Missing a temporary folder";
							$_SESSION['actionmessage'] = "Missing a temporary folder";
							$_SESSION['alert_flag'] = 0;
							break;
						case UPLOAD_ERR_CANT_WRITE:
							//$message = "Failed to write file to disk";
							$_SESSION['actionmessage'] = "Failed to write file to disk";
							$_SESSION['alert_flag'] = 0;
							break;
						case UPLOAD_ERR_EXTENSION:
							//$message = "File upload stopped by extension";
							$_SESSION['actionmessage'] = "File upload stopped by extension";
							$_SESSION['alert_flag'] = 0;
							break;

						default:
							//$message = "Unknown upload error";
							$_SESSION['actionmessage'] = "Unknown upload error";
							$_SESSION['alert_flag'] = 0;
							break;
						}

						//require('view/backend/usereditView.php');	
						header('Location: index.php?action=myprofile&id='.$userid);
				 } 
        }
							

	 }


	# **************
        # Delete USER 
        # **************



        function userdelete($userid, $usertypeid) {
		
			// DELETE USER'S COMMENTS
			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
			$deletecomment = $commentManager->deleteCommentPost($postId =null,$idcomment = null, $userid);
			//var_dump($deletecomment);
			// IF ADMIN DELETE USER'S POSTS
			if($usertypeid == 1){
				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
				$deletepost = $postManager->deletePost($postId = null, $userid);
//var_dump($deletepost);
			}
			
			// DELETE USER
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager();
			$deleteuser = $userManager->deleteUser($userid);

/*var_dump($deleteuser);
exit;*/
			$action = $_SESSION['ACTION'];

			
			initmessage($action,$deleteuser);
			
			
			//require('view/backend/listusersView.php');
			header('Location: index.php?action=usersadmin');
            
        }

		

		# **************
        # Initialize Display Action Message
		# @Param $action : user's action 
		# @Param $result : user's action result
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
				if ($result == 1) {
					$_SESSION['actionmessage'] = 'Merci pour votre insription. Pour activer votre compte. Merci de cliquer sur le lien d\'activation qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .  !';
					$_SESSION['alert_flag'] = 1;
				 }
					
				 elseif($result == 'email') {
					$_SESSION['actionmessage'] = 'Email Already exists !';
					$_SESSION['alert_flag'] = 0;
				 }
				elseif ( ($result != 1) && ($result != 'email')) {
					$_SESSION['actionmessage'] = 'Failed to Add User !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'usersignin':
				if ($result == 1) {
					$_SESSION['actionmessage'] = 'Merci pour votre insription. Pour activer votre compte. Merci de cliquer sur le lien d\'activation qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .  !';
					$_SESSION['alert_flag'] = 1;
				 }
					
				 elseif($result == 'email') {
					$_SESSION['actionmessage'] = 'Email Already exists !';
					$_SESSION['alert_flag'] = 0;
				 }
				elseif ( ($result != 1) && ($result != 'email')) {
					$_SESSION['actionmessage'] = 'Failed to Add User !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'verifylogin':
				if (! $result) {
					$_SESSION['actionmessage'] = 'Failed Bad Login Or Password !';
					$_SESSION['alert_flag'] = 0;
				 }elseif($result == 'account_not_activated') {
					$_SESSION['actionmessage'] = 'Votre compte n\'est pas activé. Merci de verifier votre messagerie pour l\'activer !';
					$_SESSION['alert_flag'] = 0;
				 }/**/
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

				case 'userupdate':
				if ( $result ) {
					$_SESSION['actionmessage'] = 'Profile Utilistaeur mis à jour !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'problème lors de la mise à jour !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'userdelete':
				if ( $result ) {
					$_SESSION['actionmessage'] = 'Utilistaeur supprimé !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'problème lors de la Suppression !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'passreset':
				if ( $result ) {
					$_SESSION['actionmessage'] = ' Pour r&eacute;initialiser votre Mot de Passe. Merci de cliquer sur le lien  qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .   !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'l\adresse email n\existe pas !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'passreinitialisation':
				if ( $result ) {
					$_SESSION['actionmessage'] = ' Veuillez saisir et confirmer votre nouveau Mot de Passe .   !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'le lien Pour r&eacute;initialiser votre Mot de Passe est expir&eacute;  !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'newpass':
				if ( $result ) {
					$_SESSION['actionmessage'] = ' Votre Mot de Passe a &eacute;t&eacute; r&eacute;initialis&eacute; .   !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'Probl&egrave;me de confirmation de mot de passe  !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'contactform':
				if ( $result ) {
					$_SESSION['actionmessage'] = ' Votre message a &eacute;t&eacute; envoy&eacute; .   !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'Probl&egrave;me d\'envoi du mail  !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'tokenlife':
				if ( $result ) {
					if(($result == 'token') || ($result == 'referer') ){
						$_SESSION['actionmessage'] = 'Vous ne pouvez pas faire cela !';
						$_SESSION['alert_flag'] = 0;
						//require('view/frontend/template.php');
						//exit;
					}elseif($result == 'expiredtoken' ){
						//echo 'ici aussi';
						//exit;
						$_SESSION['actionmessage'] = ' Votre session est expir&eacute;e .  Merci raffraîchir la page et de recommencer !';
						$_SESSION['alert_flag'] = 0;
					}
					
				 }else{
						$_SESSION['actionmessage'] = ' Probl&egrave;me de token .  Merci de recommencer !';
						$_SESSION['alert_flag'] = 0;
				 }
				/*else{
					$_SESSION['actionmessage'] = 'Probl&egrave;me d\'envoi du mail  !';
					$_SESSION['alert_flag'] = 0;
				 }*/
                
                break;
			 
			}
			
        }
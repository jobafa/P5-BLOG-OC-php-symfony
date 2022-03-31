<?php

// Chargement des classes


require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');



		# **************
        # BACK END
        # **************

		# ********************************
        # Verify type of user ( level ) : Admin or Guest 
		# and redirect to Post view or dashboard
        # *********************************

		function verifyType(){

			if(isset($_SESSION['FROM'])){
				$from = $_SESSION['FROM'];
			}
			
			$pseudo = $_SESSION['PSEUDO'];
			$action = $_GET['action'];
			$result = $_SESSION['RESULT'];

			if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 3)){ // IF GUEST 

					//$result1 = 0;
					//initmessage($action,$result1);

					if(isset($_SESSION['POSTID'] )){// IF COMES FROM A POST VIEW THEN SEND BACK TO THE POST VIEW
						
						//header('Location: index.php?action=frontpost&id='.$_SESSION['POSTID']);
						header('Location: frontpost-'.$_SESSION['POSTID'].'.html#post');
						exit;
						
					}else{// IF  DOES NOT COME FROM A POST VIEW : SEND TO GUEST DASHBOARD PAGE

						header('Location: index.php?action=mycomments');
						
					}

					
				}elseif(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){ // IF ADMIN

					//$result1 = 1;
					//initmessage($action,$result1);

					// IF COMES FROM A POST VIEW THEN SEND BACK TO THE POST VIEW

					if(isset($_SESSION['POSTID'] ) ){
						
						//header('Location: index.php?action=frontpost&id='.$_SESSION['POSTID']);
						header('Location: frontpost-'.$_SESSION['POSTID'].'.html#post');
					}else{

						// IF DOES NOT COME FROM A POST VIEW : SEND TO ADMIN DASHBOARD PAGE

						header('Location: index.php?action=adminposts');
						
					}

					

				}
			
			
			
		}

		# ********************
        # DISPLAYS New Post VIEW
        # ********************


		function addPostView(){

			require('view/backend/addpostView.php');
		
		}
        
	
		# **************
        # Add New Post 
        # **************

        function addPost() {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // CrEation of objet

            $post = $_POST;

			// CHECKING UPLOAD POST IMAGE

			if(isset($_FILES)){
				
				$status = $_FILES['pimage']['error'];
				$post_image = $_FILES["pimage"];

				$pimage = checkUploadStatus($status, $post_image);
				
				if($pimage == false){
				
							require('view/backend/addpostview.php');
							exit;
					 //}
				}
				
			} // END OF  FILE UPLOAD CHECKING

			try{
				$post_userid = $_SESSION['USERID']; 
				$post_title = htmlspecialchars(trim($post['title']));
				$post_lede = htmlspecialchars(trim($post['lede']));
				$post_author = htmlspecialchars(trim($post['author']));
				$post_content = htmlspecialchars(trim($post['content']));
				$is_enabled="1";
				
				
				$result=$postManager->addPost( $post_userid, $post_title, $post_lede, $post_author, $post_content, $pimage, $is_enabled);
				$action = $_GET['action'];

				//Generate a message according to the action processed
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

        }

		# *********************************************
        #  LISTS Posts  FOR Activate, disactivate, Update or DELETE
		# @param userid or null if listing all posts
        # *********************************************

        function listPostsUpdate($userid = null)
			{
				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Creation of objet
				$posts = $postManager->getPosts($userid,$from = null, $is_published = null, $paginationStart = null, $limit = null); 
				
				require('view/backend/backblogmanage_OK.php');

				
			}


		
		# **********************************
        #  LISTS User's Comments 
        # **********************************

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

			# *******************************
        #  LISTS Comments  To Validate   OR DELETE
        # *************************************

        function listCommentsValidate()
			{
				$CommentManager = new \OC\PhpSymfony\Blog\Model\CommentManager(); // Création d'un objet
				
				// CHEKS IF USER IS A GUEST
				if(isset( $_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 3)){
					
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

				header('Location: index.php?action=commentsadmin');
				$action = $_GET['action'];

			//gerate a message according to the action process
			initmessage($action,$validateComment);

			}


		function modifyPostView($postId)
			{

				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();

				$post = $postManager->getPost($postId, $is_published = null);

				require('view/backend/posteditView.php');

					
				}


		function updatePost($id){
			
				$data = $_POST;
				
				// UPLOAD POST IMAGE

				if(isset($_FILES)){

					$status = $_FILES['pimage']['error'];
					$post_image = $_FILES["pimage"];
					
					$pimage = checkUploadStatus($status,$post_image,$id);
					
					if($pimage == false){
								
						header('Location: index.php?action=modifypost&id=' . $id);
						exit;
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
						//header('Location: index.php?action=modifypost&id=' . $id .'&post_id='.$_GET['post_id'] );
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

			header('Location: index.php?action=adminposts');
            
        }


		# **************
        # Delete Post 
		#@param $posiId
        # **************



        function deletepost($postId) {

			$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

			$deletecomment = $commentManager->deleteCommentPost($postId,$idcomment = null); // DELETE COMMENTS RELATED TO THE POST
			$deletepost = $postManager->deletePost($postId); ;// DELETE POST

			//gerate a message according to the action process
			initmessage($action,$deletepost);
			
			
			
			header('Location: index.php?action=backblogmanage');
            
        }

		# *****************
        # Delete a comment 
		#@param $commentid
        # *****************

		function deleteComment($commentid) {

			
			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

			if(isset($_SESSION['USERID'])){
				$userid = $_SESSION['USERID'];
			}
			$deletecomment = $commentManager->deleteCommentPost($idpost = null , $commentid,$userid = null);
		
			//generate a message according to the action process
			$action = $_SESSION['ACTION'];
			initmessage($action,$deletecomment);
			
			
			header('Location: index.php?action=commentsadmin');
            
        }

		# **************
        # User Add View
        # **************

		function adduserView($action){

			
			if(isset($action) && ($action == "signinview")){
						
					require('view/frontend/signinView.php');

			}elseif(isset($action) && ($action == "adduserview")){

					require('view/backend/adduserView.php');

			}

			
		}
        
		

		# *****************************
        # User Add
		#@params $post, $URL, $token_name
        # *****************************

        function addUser($post, $URL, $token_name) {
			
			//  errors is the array that contains data input validation errors
			unset($_SESSION['errors']);

			$action =$_SESSION['ACTION'];
			if($action == 'usersignin'){

				$check_token = check_token(600,  $URL.'signinview.html', $token_name);

			}if($action == 'useradd'){ // for admin user

				$check_token = "ras";
			}
			
			if($check_token)
			 {
				if($check_token == "ras"){ // WE HAVE A MATCH OF TOKENS 
					
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
					
					// SANITIZE DATA
					$data = sanitize_inputs($inputs,$fields);

					
					$fields = [
						'pseudo' => 'required',
						//'email' => 'required',
						'email' => 'required|email|unique:user,email',
						'password' => 'required'
						//'password' => 'required | secure'
					];

					//VALIDATE DATA
					$errors = validate($data, $fields);	

				

					if(!empty($errors)){
						$_SESSION['errors'] = $errors;

						if( isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID']  == 1)){
									
									header('Location: index.php?action=adduserview');
									
							 }else{
									header('location: signinview.html#inscription');
									
							 }
						
					}
						
				}else{ // IN CASE OF CSRF PROBLEM


						$action = "tokenlife";
						
						//  INITIATE DISPLAY MESSAGE
						 initmessage($action,$check_token);

						 unset($_SESSION[$token_name.'_token']);
						 unset($_SESSION[$token_name.'_token_time']);

						 header('location: signinview.html#inscription');
				}
			 }
			
			if(empty($errors)){ // NO ERRORS GO ON PROCESS
	
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Objet creation
			
			// GET DATA FROM THE SANITIZED AND VALDATED POST DATA ARRAY

          	$pseudo =  $data['pseudo'];
			$email =  $data['email'];
			$password =  $data['password'];
			
			
				
			// CHECKING FILE UPLOAD

			if(isset($_FILES)){
				
				$status = $_FILES['photo']['error'];
				$post_image = $_FILES["photo"];

				$photo = checkUploadStatus($status, $post_image);
				
				if($photo == false){
					if(isset($_SESSION['USERTYPEID'] ) && ($_SESSION['USERTYPEID']  == 1)){
									
									require('view/backend/adduserView.php');
									exit;
					 }else{
							header('Location: signinview.html#inscription');
							
							exit;
					 }
				}
				
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
						
						$is_enabled="1";

						// GENERATE TOKEN FOR ACCOUNT ACTIVATION ,MUST ADD TOKEN EXPIRATION DATE TO USER'S ACTIVATION 
						$token = get_token('activation');
					   
						$result = $userManager->registerUser($usertype_id, $pseudo, $email, $password, $photo, $token);

						if ($result ) {
							
							$id = $_SESSION['LASTUSERID'];
							
							 UserActivationEmail( $email,$pseudo, $id,  $token);
							
						 }
						 
						$action = $_SESSION['ACTION'];

						initmessage($action,$result); //generate a message according to the action in process
															 
						 if($action == 'usersignin'){
							 
							 header('Location: signinview.html#inscription');
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
		//$message = "Bonjour " . $pseudo. ", bienvenue sur mon blog !\r\n\r\nPour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/index.php?action=useractivation&id=" . $id . "&link_emaill=" . $email . "&token=" . $token . "\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";
		
		$message = "Bonjour " . $pseudo. ", bienvenue sur mon blog !\r\n\r\nPour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/useractivation-".$id."-".$token. ".html\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";

		mail($email, $subject, $message, $headers);
		
	}


	/** FUNCTION USED TO ACTIVATE USER'S ACCOUNT FROM MAIL LINK
	 *	  ALSO USED TO ACTIVATE OR DISACIVATE USER'S ACCOUNT FROM ADMIN DASHBOARD
	 * Get email and activation key from activation link and call userManager tocheck correspondance in database.
		Delete activation_code from database to activate user account.
	 * @param  Parameters $userid, $email, $token, $isactivated
	 * 
	 */

	function userActivation($userid, $email, $token, $isactivated)
	 {
		$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // CrEation OF objet

		//  USER ACTIVATION PROCESSED FROM ADMIN DASHBOARD
		
		if(isset($isactivated) && ($isactivated  != NULL)){
				
				//$isadmin = is_admin(); // check if admin is logged

				$result = false;

				if($isactivated == 'on'){

					$token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

					$token = password_hash($token,PASSWORD_DEFAULT);
				
				}elseif($isactivated == 'off'){

					$token = null;
					
				}

			$adminactivation = $userManager->userActivate($userid, $token); 

			header('Location: index.php?action=usersadmin');

			// END OF USER' ACTIVATION FROM ADMIN DASHBOARD	

		 
		}elseif(isset($token) && ($token  != NULL)){ // USER ACTIVATION PROCESSED FROM USER'S MAIL LINK


				$getactivationcode = $userManager->getUseractivationcode($userid);

		}

			// TODO : USING PARAMETER EMAIL ( INSTED OF USERID ) AND TOKEN = DELETE PARAMETER ID IN EMAIL LINK SENT TO USER FOR ACCOUNT ACTIVATION

		// IF WE HAVE A MATCH : ACTIVATE ACCOUNT

		if ( ($getactivationcode) && ( $getactivationcode['is_activated'] == $token) )  {
				
				//$action = $_SESSION['ACTION'];

				$activatedaccount = $userManager->userActivate($userid);

				
											
		}		

		DisplayActivationMessage($getactivationcode, $activatedaccount);

				/*// TEST ACTIVATION RESULT TO GENERATE ACTION MESSAGE TO BE DISPLAYAED TO USER

				if( $activatedaccount ){ // USER'ACTIVATION FROM MAIL LINK IS OK
				
					$_SESSION['actionmessage'] = 'F&eacute;licitations ! Votre compte vient d\'&ecirc;tre  activ&eacute;';
					$_SESSION['alert_flag'] = 1;
					header('Location: loginview.html#login');

				}else{
					$_SESSION['actionmessage'] = 'D&eacute;sol&eacute; ! Probl&egrave;me lors de l\'activation de votre compte. <BR>Merci de contacter l\'administrateur via le formulaire de contact ';
					$_SESSION['alert_flag'] = 1;
					header('Location: signinview.html#inscription');
				}

		}elseif(( $getactivationcode ) && ( $getactivationcode['is_activated'] == NULL )){

				$_SESSION['actionmessage'] = 'Votre compte est d&egrave;j&agrave;  activ&eacute;';
				$_SESSION['alert_flag'] = 1;
				header('Location: loginview.html#login');
		}
		elseif(( $getactivationcode ) && ( $getactivationcode['is_activated'] != NULL )){

				$_SESSION['actionmessage'] = 'Mauvaise cl&eacute; d\'activativation';
				$_SESSION['alert_flag'] = 0;
				header('Location: signinview.html#inscription');
		}*/
		
		/*
		if(! isset($isactivated)){ // Means we are coming from user's activation link = display login form
			header('Location: loginview.html#login');
			//require('view/frontend/loginView.php');

		}else{// Means we are coming from admin  dashboard

			//require('view/backend/listusersView.php');	
			header('Location: index.php?action=usersadmin');
		}
		*/
	 }


	/*************************************
	 * Get user's information for Profile update.
	 * @param  Parameter $userid
	 *************************************/ 
	 
	function userProfile($userid)
	 {
			
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Create objet
			
			$profile = $userManager->getUser($userid);
			
			require('view/backend/usereditView.php');	
	 }

	/*****************
	 * Get user's  for,Admin.
	  ******************/

	function usersAdmin()
	 {
			
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Create objet
			
			
			$getusers = $userManager->getUser();
			
			require('view/backend/listusersView.php');	
	 }


	 /******************************
	 * Get user's information for update.
	 * @param $post data inputs, $userid
	 *  *****************************/
	

	function userUpdate($post,$id)
	 {
			
			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Create objet
			
			$userid = $id;

			if(isset($_FILES)){

						$status = $_FILES['photo']['error'];
						$post_photo = $_FILES["photo"];
						
						$photo = checkUploadStatus($status,$post_photo,$id);
						
						if($photo == false){
		

							header('Location: index.php?action=myprofile&id='.$userid);
							exit;
						}
						
					}

			try{
						$updateprofile = $userManager->userUpdateProfile($userid, $post, $photo);

						$action = $_SESSION['ACTION'];

						//generate a message according to the action in process
						
						initmessage($action,$updateprofile);

						if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){
							
							header('Location: index.php?action=usersadmin');
						}

						//require('view/backend/usereditView.php');	
						header('Location: index.php?action=myprofile&id='.$userid);

				}

				catch(Exception $e) {
								$errorMessage = $e->getMessage();
								require('view/errorView.php');
				}

		}


		# **************************************
        # Delete USER 
		#@PARAMS $userid, $usertypeid : is admin or not
        # ****************************************



        function userdelete($userid, $usertypeid) {
		
			// DELETE USER'S COMMENTS

			$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
			$deletecomment = $commentManager->deleteCommentPost($postId =null,$idcomment = null, $userid);
			
			// IF ADMIN DELETE USER'S POSTS

			if($usertypeid == 1){
				$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
				$deletepost = $postManager->deletePost($postId = null, $userid);

			}
			
			// DELETE USER

			$userManager = new \OC\PhpSymfony\Blog\Model\UserManager();
			$deleteuser = $userManager->deleteUser($userid);


			$action = $_SESSION['ACTION'];

			
			initmessage($action,$deleteuser);
			
			
			//require('view/backend/listusersView.php');
			header('Location: index.php?action=usersadmin');
            
        }

		

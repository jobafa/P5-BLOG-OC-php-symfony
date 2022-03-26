<?php
//require_once __DIR__.'/inc/functions.php';

// Chargement des classes


require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');

/***DISPLAYS ALLE POSTS********************************
**@PARAMS $from : front or back and $getpage : for pagination**
****************************************************/

function listPosts($from, $getpage)
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet

	$totalRecrods = $postManager->gettotalPosts( $ispublished = '1'); 
	
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

    $posts = $postManager->getPosts($userid = null, $from, $ispublished = '1', $paginationStart, $limit); 
	
    require('view/frontend/listPostsView.php');
}


/***DISPLAYS ONE POST****************
**@PARAMS $id : Post id and $is_published**
************************************/

function post($id, $is_published)
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
	
    $post = $postManager->getPost($id, $is_published );

	if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){
		if($_SESSION['ACTION'] != 'frontpost'){
			require('view/backend/postView.php');
			
		}else{

			$comments = $commentManager->getComments($id,'1');
			require('view/frontend/postView.php');
			exit;
		}
	}else{
		$comments = $commentManager->getComments($id,'1');

		require('view/frontend/postView.php');
		exit;
	}
}

function addComment($postId, $author, $comment, $userid)
{
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $affectedLines = $commentManager->postComment($postId, $author, $comment, $userid);

	$action = $_GET['action'];

			//generate a message according to the action processed
			initmessage($action,$affectedLines);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=frontpost&id=' . $postId);
    }
}

	
		/***********************************************
	 * Checking  contact form csrf token and sending the mail ***
	 * @param  Parameter $contact-post, $URL, $token_name***
	 * ***********************************************/
	 

	function checkContactdata($post, $URL, $token_name){

		$check_token = check_token(600,  $URL.'accueil.html', $token_name);
		
		if($check_token)
			{
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);
					
					$name =  $post['name'];
					$email =  $post['email'];
					$subject =  $post['subject'];
					$message =  $post['message'];

					$inputs = [
						'name' => $name,
						'email' => $email,
						'subject' => $subject,
						'message' => $message
						];

					$fields = [
						'name' => 'string',
						'email' => 'email',
						'subject' => 'string',
						'message' => 'string'
						
					];

					$data = sanitize_inputs($inputs,$fields);

					$fields = [
						'name' => 'required',
						'email' => 'required',
						'subject' => 'required',
						'message' => 'required'
					];
					
					$errors = validate($data, $fields);	
					
					if(!empty($errors)){
						$_SESSION['errors'] = $errors;
						header('location: contact.html#contact');
						//var_dump($errors);
					}
			
					sendContactEmail($post);

				}else{

					$action = "tokenlife";
					
					
				}
			
			
			initmessage($action,$check_token);

			unset($_SESSION[$token_name.'_token']);
			unset($_SESSION[$token_name.'_token_time']);

			header('location: accueil.html#contact');
		}
	}

		/**
	 * Sending  contact email  ****************
	 * @param  Parameter $post, post inputs data
	 * ***********************************/
	 

	function sendContactEmail($post)

	{
		$name= $post['name'];
		$email = $post['email'];
		$subject = $post['subject'];
		$message = "<div style=width: 100%; text-align: center; font-weight: bold >Bonjour ".$name. "<BR><BR></div>\r\n";
		$message .= $post['message'];
		$headers = "Reply-To: " . $email . "\r\n";
		$headers .= 'From: Mon Blog <'.$email.'>'."\n"; // Expediteur
		$headers .= "X-Mailer: PHP/".phpversion();
		$headers .= 'MIME-Version: 1.0' . "\n"; // Version MIME
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		
		if(mail(CF_EMAIL, $subject, $message, $headers)){

			$email_ok = true;

		}else{

			$email_ok = false;	
		}

		$action = $_GET['action'];

			//generate a message according to the action processed
			
		initmessage($action,$email_ok);
		header('location: home.php#contact');
		
		
	}

	# **************
        # Display user Login form
        # **************



        function loginView( ) {

			require('view/frontend/loginView.php');

        }


		# **************
        # Verify User  Login
        # **************



        function verifyLogin($post, $URL, $token_name) {

			unset($_SESSION['errors']);
			
			// CHECK LOGIN CSRF TOKEN
			$check_token = check_token(600,  $URL.'loginview.html', $token_name);
				
			if($check_token)
			 {
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);

					$email =  $post['email'];
					$password =  $post['password'];

					$inputs = [
						'email' => $email,
						'password' => $password
						];

					$fields = [
						'email' => 'email',
						'password' => 'string'
						
					];

					$data = sanitize_inputs($inputs,$fields);


					$fields = [
						'email' => 'required',
						'password' => 'required'
						//'password' => 'required | secure'
					];
					
					$errors = validate($data, $fields);	
					/*$errors = validate($data, $fields, [
						'required' => 'Le champ %s est requis',
						'password2' => ['same'=> 'Merci de saisir le même mot de passe']]
					);
					*/

					if(!empty($errors)){

						$_SESSION['errors'] = $errors;
						header('location: loginview.html');
						
					}

				}else{// test of $checktoken != "ras"

					$action = "tokenlife";
									
					 initmessage($action,$check_token);

					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 header('location: loginview.html');
					 }
			 }


			// VALIDATION OF FORM DATA IS OK / VERIFY USER  LOGIN AND PASSWORD

			if(empty($errors)){
				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Creation of objet 

				$action = $_SESSION['ACTION'];

				$result=$userManager->loginUser($email, $password) ;	
				
				
				if( ($result) &&  ($result == 'not_activated') ){ // USER ACCOUNT NOT ACTIVATED

					$result = 'account_not_activated';
					initmessage($action,$result);
					require('view/frontend/loginView.php');

				}elseif (($result) &&  ($result != 'not_activated')){ // LOGIN AND PASSWORD MATCH IN DB
				
					initmessage($action,$result);

					$_SESSION['USERID'] = $result['id'];
					$_SESSION['USERTYPEID'] = $result['usertype_id'];
					$_SESSION['PSEUDO'] = $result['pseudo'];
					$_SESSION['PHOTO'] = $result['photo'];
					$_SESSION['RESULT'] = $result;
					
					
					 if($_SESSION['USERTYPEID'] == 3){

						header('Location: index.php?action=mycomments');

					 }elseif($_SESSION['USERTYPEID'] == 1){

						header('Location: index.php?action=adminposts');

					 }

				
				}else{ // LOGIN OR PASSWORD OR BOTH DON'T MATCH

					initmessage($action,$result);
					header('Location: loginview.html');
					
				}
			}

		}


	
		# **************
        # Display user's password reset Requesting form
        # **************



        function passresetRequest() {

			require('view/frontend/passresetView.php');

        }


		# **************
        # Display user's new password  form
        # **************



        function passreinitNew() {

			require('view/frontend/passreinitView.php');

        }

			/**
	 * Get user's email 
	 * @param  Parameter $email
	 * 
	 */

        function passReset($post, $URL, $token_name) {

			// CHECK LOGIN CSRF TOKEN
			$check_token = check_token(600,  $URL.'passresetrequest.html', $token_name);
							
			if($check_token)
			 {
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);

					$email =  $post['email'];
					
					$inputs = [
						'email' => $email
						];

					$fields = [
						'email' => 'email'
						];

					$data = sanitize_inputs($inputs,$fields);

					$fields = [
						'email' => 'required'
						];

					$errors = validate($data, $fields);	
					/*$errors = validate($data, $fields, [
						'required' => 'Le champ %s est requis',
						'password2' => ['same'=> 'Merci de saisir le même mot de passe']]
					);
					*/

					if(!empty($errors)){
						$_SESSION['errors'] = $errors;
						header('location: passresetrequest.html');
						
					}

				}else{


					$action = "tokenlife";
						
				
			
			
					 initmessage($action,$check_token);

					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 //require('view/frontend/passresetView.php');
					 header('location: passresetrequest.html');
					 //require('passresetrequest.html');
					 }
			 }
			
			if(empty($errors)){
				$action =$_SESSION['ACTION'];

				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Creation of objet
				$email =  $data['email'];
								
				// TESTS IF EMAIL  EXISTS
				$verifyemail = $userManager->VerifyUserEmail($email);
				

				if ($verifyemail) 
					{ 

						$token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
						$token = password_hash($token,PASSWORD_DEFAULT);

						$userid = $verifyemail['id'];
							
						$passreset = $userManager->resetPassTokenInsert($userid, $token);
													
						if ($passreset ) {

							$pseudo = $verifyemail['pseudo'];
							
							PassResetEmail( $email,$pseudo, $userid,  $token);
										
						}
						$action = $_SESSION['ACTION'];
						initmessage($action,$passreset);

				}

						//require('view/frontend/passresetView.php');
						//require('passresetrequest.html');
						header('location: passresetrequest.html');
			}
        }


			/**
	 * Get email and reset password token from users email link and verify if the same token in user's record and if it is not expired
	 if OK displays reset password form
	 * @param  Parameter $link_email $link_token
	 * 
	 */

        function verifyPassresetToken($link_email,$link_token){
			
			$errors =array();
			
			$fields = [
						'link_email' => 'required',
						'link_token' => 'required'
						];

			$data = [
						'link_email' => $link_email,
						'link_token' => $link_token
					];

			$errors = validate($data, $fields);	
			/*$errors = validate($data, $fields, [
				'required' => 'Le champ %s est requis',
				'password2' => ['same'=> 'Merci de saisir le même mot de passe']]
			);
			*/

			if(!empty($errors)){
				$_SESSION['errors'] = $errors;
				header('location: passresetrequest.html');
				//require('passresetrequest.html');
				
			}else{

				$action =$_SESSION['ACTION'];

				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Creation of objet
							
				$verifyemailtoken = $userManager->VerifyEmailToken($link_email, $link_token);
				


				if ($verifyemailtoken) 
					{ 

						$action = $_SESSION['ACTION'];
						$_SESSION['LINK_EMAIL'] = $link_email;
						$_SESSION['LINK_TOKEN'] = $link_token;
						
						initmessage($action,$verifyemailtoken);

					}

				//require('view/frontend/passreinitView.php');
				header('Location: passreinitnew.html');

				//header('location: passreinitialisation.html');
			}

        }

	/**
	 * Get data of new password form and verify if password and confirmed password are equal
	 * @param  Parameter $post
	 * 
	 */

        function getNewPass($post, $URL, $token_name){

			if(isset($_SESSION['errors'] )) unset($_SESSION['errors']) ;
			
			$errors = array();
			// CHECK LOGIN CSRF TOKEN
			$check_token = check_token(600,  $URL.'passreinitnew.html', $token_name);

			if($check_token)
			 {
					if($check_token == "ras"){
					
						unset($_SESSION[$token_name.'_token']);
						unset($_SESSION[$token_name.'_token_time']);

						$newpassword = $post['newpassword'];
						$confirmnewpassword =  $post['confirmnewpassword'];
						$link_token = $post['reset_link_token'];
						$email = $post['email'];

						$inputs = [
							'newpassword' => $newpassword,
							'confirmnewpassword' => $confirmnewpassword,
							'email' => $email,
							'reset_link_token' => $link_token
							];

						$fields = [
							'newpassword' => 'string',
							'confirmnewpassword' => 'string',
							'email' => 'string',
							'reset_link_token' => 'string'
							
							];

						$data = sanitize_inputs($inputs,$fields);

						$fields = [
							//'newpassword' => 'required | secure',
							'newpassword' => 'required',
							'confirmnewpassword' => 'required | same:newpassword'
							];

						$errors = validate($data, $fields);	
						/*$errors = validate($data, $fields, [
							'required' => 'Le champ %s est requis',
							'password2' => ['same'=> 'Merci de saisir le même mot de passe']]
						);
						*/
						//var_dump($data);
						
						if(!empty($errors)){var_dump($newpassword);
						
						$_SESSION['errors'] = $errors;
						header('location: passreinitnew.html');
						//require('passresetrequest.html');
						
						}
					}else{
				

						$action = "tokenlife";
							
					
				
				
						 initmessage($action,$check_token);

						 unset($_SESSION[$token_name.'_token']);
						 unset($_SESSION[$token_name.'_token_time']);

						 require('view/frontend/passreinitView.php');
						// header('location: newpass.html');
						 //require('passresetrequest.html');
						 }
			 }
			
			if(empty($errors)){
			

					
						$newpass = $data['newpassword'];
						$link_email = $data['email'];
						$link_token = $data['reset_link_token'];

						
						$hash = password_hash($newpass,PASSWORD_DEFAULT);
						
						$newpass = $hash;

						$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Creation of objet


						$updateNewPass = $userManager->updatePass($newpass, $link_email, $link_token);

						$action = $_SESSION['ACTION'];
						initmessage($action,$insertNewPass);

						if ($updateNewPass) 
						{ 
							$action = $_SESSION['ACTION'];
							initmessage($action,$updateNewPass);
							//require('view/frontend/loginView.php');
							header('Location: loginview.html');

						}else{
						$action = $_SESSION['ACTION'];
						initmessage($action,$updateNewPass = false);

						//require('view/frontend/passreinitView.php');
						header('Location: passreinitview.html');
					}
				}
		}

			/**
	 * Sending  reset password token email  to user after password forgot
	 * @param  Parameter $email,$pseudo, $id, $token
	 * 
	 */

	function PassResetEmail( $email,$pseudo, $id, $passreset_token)
	{
		$subject = "Réinitialisation de votre Mot de Passe";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		//$message = "Bonjour " . $pseudo. ", Pour Réinitialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/index.php?action=passreinitialisation&email=". $email."&token=" . $passreset_token . "\r\n\r\n----------------------.";
		$message = "Bonjour " . $pseudo. ", Pour Réinitialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/passreinitialisation-". $email."-". $passreset_token . ".html\r\n\r\n----------------------.";
		//$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);
		
	}


// EXERCICE TP COURS A SUPPRIMER

function modifyComment($commentId)
{
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $comment = $commentManager->getComment($commentId);

	require('view/frontend/commenteditView.php');

    
}

// FIN EXERCICE TP COURS


<?php
//require_once __DIR__.'/inc/functions.php';

// Chargement des classes


require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');

function listPosts($from, $getpage)
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet

	$totalRecrods = $postManager->gettotalPosts( $ispublished = '1'); 
	
	// PAGINAION

	 //$limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
	  $limit = 4;
	
	  $page = $getpage;

	  $paginationStart = ($page - 1) * $limit;

	 
	  
	  // Calculate total pages
	  $totoalPages = ceil($totalRecrods / $limit);

	  // Prev + Next
	  $prev = $page - 1;
	  $next = $page + 1;

	  // end pagination

    $posts = $postManager->getPosts($userid = null, $from, $ispublished = '1', $paginationStart, $limit); // Appel d'une fonction de cet objet
	
    require('view/frontend/listPostsView.php');
}

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

	
		/**
	 * Checking  contact form csrf token and sending the mail 
	 * @param  Parameter $contact-post
	 * 
	 */

	function checkContactdata($contact_post, $URL, $token_name){

		$check_token = check_token(600,  $URL.'accueil.html', $token_name);
		//var_dump( $URL);
	
		if($check_token)
			{
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);
					//$contact_post = $post;
			
					sendcontactemail($contact_post);

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
	 * Sending  contact email  
	 * @param  Parameter $contact-post
	 * 
	 */

	function sendcontactemail($post)

	{
		$name= $post['name'];
		$email = $post['email'];
		$subject = $post['subject'];
		$message = "<div style=width: 100%; text-align: center; font-weight: bold>Bonjour ".$name. "</div>\r\n";
		$message .= $post['message'];
		//$subject = "Réinitialisation de votre Mot de Passe";
		//$headers = "From: Blog Abderrahim Fathi\r\n";
		$headers = "Reply-To: " . $email . "\r\n";
		$headers .= 'From: Mon Blog <'.$email.'>'."\n"; // Expediteur
		//$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "X-Mailer: PHP/".phpversion();

		$headers .= 'MIME-Version: 1.0' . "\n"; // Version MIME
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		//$message = ;

		//$message = wordwrap($message, 70, "\r\n");
		if(mail(CF_EMAIL, $subject, $message, $headers)){

			$email_ok = true;

		}else{

			$email_ok = false;	
		}

		$action = $_GET['action'];

			//generate a message according to the action processed
			
		initmessage($action,$email_ok);
		header('location: home.php#contact');
		//header('location: home.php');
		//echo $activation_code;
		
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
//unset($_SESSION[$token_name.'_token']);
					//unset($_SESSION[$token_name.'_token_time']);
			// DESTROY ERRORS ARRAY VAR
			unset($_SESSION['errors']);
			
			// CHECK LOGIN CSRF TOKEN
			$check_token = check_token(600,  $URL.'loginview.html', $token_name);
			//var_dump($check_token);
						//var_dump($_SESSION[$token_name.'_token']);
			//var_dump($URL);/**/

			//exit;
			
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

					/*$data = [
						'email' => $email,
						'password' => $password
					];*/

					$fields = [
						'email' => 'required | email | unique: users,email',
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
						header('location: loginview.html');
						//var_dump($errors);
					}/*else{
						echo 'clean';
					}
					exit;*/
				}else{

					$action = "tokenlife";
						
				
			
			
					 initmessage($action,$check_token);

					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 header('location: loginview.html');
					 }
			 }
/*
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

			foreach($data as $key=>$value){
				
				$$Err = $key.'Err';
				$errors[$key] = $$Err;
								
			}
			
			if($data){
				foreach($data as $key=>$value){
					
					if (!empty($value)){
					
						// check if email address is a correct format
						if($key == 'email'){
						   if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {

								foreach($errors as $key=>$value){

									if($key == 'email'){

										$$value = "Format Email Invalid"; 

									}
								}
									
						   }
							 
						}
					
					}else{

						$$Err = $key.' Requis !';
					}
					
				}// end foreach data
			}// end  if  $data
			
			$post_email =  $data['email'];
			$post_password =  $data['password'];

			
			if(($emailErr != '') || ($passErr != '')){
				require('view/frontend/loginView.html');
				exit;
			}
*/

			// VALIDATION OF FORM DATA IS OK / VERIFY USER  LOGIN AND PASSWORD

			if(empty($errors)){
				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet

				//$post = $method;
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
					
					 //verifytype();
					 if($_SESSION['USERTYPEID'] == 3){
						header('Location: index.php?action=mycomments');
					 }elseif($_SESSION['USERTYPEID'] == 1){
						header('Location: index.php?action=adminposts');
					 }

				
				}else{ // LOGIN OR PASSWORD OR BOTH DON4T MATCH
					initmessage($action,$result);
					header('Location: loginview.html');
					//require('view/frontend/loginView.php');
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
			/*var_dump($check_token);
			var_dump($token_name);
						var_dump($token_name.'_token');
			var_dump($token_name.'_token_time');*/

			
			if($check_token)
			 {//var_dump($check_token);
				if($check_token == "ras"){
					
					unset($_SESSION[$token_name.'_token']);
					unset($_SESSION[$token_name.'_token_time']);

					$email =  $post['email'];
					//$password =  $post['password'];
					$inputs = [
						'email' => $email
						];

					$fields = [
						'email' => 'email'
						];

					$data = sanitize_inputs($inputs,$fields);

					//$data = [
						//'email' => $email
					//];

					$fields = [
						'email' => 'required | email | unique: users,email'
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
						//var_dump($errors);
					}/*else{
						echo 'clean';
					}
					exit;*/
				}else{
//echo 'entré'.$check_token.'oui';

					$action = "tokenlife";
						
				
			
			
					 initmessage($action,$check_token);
//var_dump($_SESSION['actionmessage']);
//exit;
					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 //require('view/frontend/passresetView.php');
					 header('location: passresetrequest.html');
					 //require('passresetrequest.html');
					 }
			 }
			
			if(empty($errors)){
				$action =$_SESSION['ACTION'];

				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet
				$email =  $data['email'];
						
				//$email = htmlspecialchars(trim($post['email']));
				
				// TESTS IF EMAIL  EXISTS
				$verifyemail = $userManager->VerifyUserEmail($email);
				

				if ($verifyemail) 
					{ 

						$token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
						$token = password_hash($token,PASSWORD_DEFAULT);

						$userid = $verifyemail['id'];
							
						$passreset = $userManager->resetPassTokenInsert($userid, $token);
						
						//var_dump($verifyemail);
							
						if ($passreset ) {//var_dump($passreset);exit;	

							$pseudo = $verifyemail['pseudo'];
							
							PassRestEmail( $email,$pseudo, $userid,  $token);
										
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
			//unset($_SESSION['LINK_EMAIL'] );
			//unset($_SESSION['LINK_TOKEN'] );

			$fields = [
						'link_email' => 'required | email | unique: users,email',
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
				//var_dump($errors);
				//exit;
			}else{

				$action =$_SESSION['ACTION'];

				$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet
							
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

			// POUR TEST : A SUPPRIMER
			//$check_token = "ras";
		/*	var_dump($check_token);
			
			var_dump($_SERVER['HTTP_REFERER'] );exit;
						var_dump($token_name.'_token');
			var_dump($token_name.'_token_time');*/

			
			if($check_token)
			 {
					if($check_token == "ras"){
					
						unset($_SESSION[$token_name.'_token']);
						unset($_SESSION[$token_name.'_token_time']);

						$newpassword = $post['newpassword'];
						$confirmnewpassword =  $post['confirmnewpassword'];
						$link_token = $post['reset_link_token'];
						$email = $post['email'];

						//var_dump($link_token);
						//var_dump($confirmnewpassword);
						//$password =  $post['password'];
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

						//$data = [
							//'email' => $email
						//];

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
						//var_dump($confirmnewpassword);
			//var_dump($errors);
	//exit;
						$_SESSION['errors'] = $errors;
						header('location: passreinitnew.html');
						//require('passresetrequest.html');
						//var_dump($errors);
						}
					}else{

					
//echo 'entré'.$check_token.'oui';

					$action = "tokenlife";
						
				
			
			
					 initmessage($action,$check_token);
//var_dump($_SESSION['actionmessage']);
//exit;
					 unset($_SESSION[$token_name.'_token']);
					 unset($_SESSION[$token_name.'_token_time']);

					 require('view/frontend/passreinitView.php');
					// header('location: newpass.html');
					 //require('passresetrequest.html');
					 }
			 }
			
			if(empty($errors)){var_dump($confirmnewpassword);
			

					//if($post['newpassword'] == $post['confirmnewpassword']){
						$newpass = $data['newpassword'];
						$link_email = $data['email'];
						$link_token = $data['reset_link_token'];

						/*var_dump($newpass);
						var_dump($link_token);
			var_dump($link_email);*/
	//exit;

						//$userpassword = $user_password;

						$hash = password_hash($newpass,PASSWORD_DEFAULT);
						
						$newpass = $hash;

						$userManager = new \OC\PhpSymfony\Blog\Model\UserManager(); // Création d'un objet


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
					
					/*}else{
						$action = $_SESSION['ACTION'];
						initmessage($action,$insertNewPass = false);

						//require('view/frontend/passreinitView.php');
						header('Location: passreinitview.html');
					}*/
				//}
			 }
		}

			/**
	 * Sending  reset password token email  to user after password forgot
	 * @param  Parameter $email,$pseudo, $id, $token
	 * 
	 */

	function PassRestEmail( $email,$pseudo, $id, $passreset_token)
	{
		$subject = "Réinitialisation de votre Mot de Passe";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		//$message = "Bonjour " . $pseudo. ", Pour Réinitialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/index.php?action=passreinitialisation&email=". $email."&token=" . $passreset_token . "\r\n\r\n----------------------.";
		$message = "Bonjour " . $pseudo. ", Pour Réinitialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/passreinitialisation-". $email."-". $passreset_token . ".html\r\n\r\n----------------------.";
		//$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);
		//echo $activation_code;
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


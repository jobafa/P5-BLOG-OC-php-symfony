<?php

namespace Controllers;

require_once'Controllers/Controller.php';

use Inc\SessionManager;

class User extends Controller{

	
	private static $filters = array(
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
	);

	private $errors = [];
	

	protected $model;
	
    protected $modelName = \Models\UserManager::class;

   
    	/***********************************************
	 * Checking  contact form csrf token and sending the mail ***
	 * @param  Parameter $contact-post, $URL, $token_name***
	 * ***********************************************/
	 

	public function checkContactdata($post, $URL, $token_name){

		SessionManager::getInstance()->sessionvarUnset('errors');

		$action = SessionManager::getInstance()->get('ACTION');

		$cleanobject = new \Inc\Clean();
		$check_token = $cleanobject->check_token(600,  $URL.'accueil.html', $token_name);
		
		$name =  $post->get('name');
		$email =  $post->get('email');
		$subject =  $post->get('subject');
		$message =  $post->get('message');
		
		$inputs = [
			'name' => $name,
			'email' => $email,
			//'email' => '',
			'subject' => $subject,
			'message' => $message
			];

		$fields = [
			'name' => 'string',
			'email' => 'email',
			'subject' => 'string',
			'message' => 'string'
			
		];

		$rules = [
			'name' => 'required',
			'email' => 'required',
			'subject' => 'required',
			'message' => 'required'
		];

		$data = $this->CleanData($inputs, $fields, $rules, $check_token, $token_name, 'accueil', 'contact');
		
		if(empty($this->errors)){

			$this->sendContactEmail($data);

		}else{
			
		}
		
	}

		/**
	 * Sending  contact email  ****************
	 * @param   $post, post inputs data
	 * ***********************************/
	 

	public function sendContactEmail($data)

	{
		

		$name= $data['name'];
		$email = $data['email'];
		$subject = $data['subject'];
		$message = "<div style=width: 100%; text-align: center; font-weight: bold >Bonjour ".$name. "<BR><BR></div>\r\n";
		$message .= $data['message'];

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

		
		$action = SessionManager::getInstance()->get('ACTION');

			//generate a message according to the action processed
			
		initmessage($action,$email_ok);
		
		\Http::redirect('home.php#contact');
		
		
	}

			# **************
        # User Add View
        # **************

		public function adduserview($action){

			
			if(isset($action) && ($action == "signinview")){
						
					require'view/frontend/signinView.php';

			}elseif(isset($action) && ($action == "adduserview")){

					require'view/backend/adduserView.php';

			}

			
		}
        
		

		# *****************************
        # User Add : this function can be called
		# from both front and back end
		#@params $post, $URL, $token_name
        # *****************************

        public function adduser($post, $URL, $token_name) {
			
			//  errors is the array that contains data input validation errors
			
			SessionManager::getInstance()->sessionvarUnset('errors');

			$action = SessionManager::getInstance()->get('ACTION');

			if($action == 'usersignin'){
			
				$usertypeid = "3"; // USER ROLE

				$cleanobject = new \Inc\Clean();
				$check_token = $cleanobject->check_token(600,  $URL.'signinview-user.html', $token_name);
 
			}
			if($action == 'useradd'){ // for admin user

				$check_token = "ras";
				$usertypeid = $post->get('usertype_id'); // USER ROLE
			}
			
			$pseudo =  $post->get('pseudo');
			$email =  $post->get('email');
			$password =  $post->get('password');

			$inputs = [
				'pseudo' => $pseudo,
				'email' => $email,
				'password' => $password,
				'usertype_id' => $usertypeid
				];

			$fields = [
				'pseudo' => 'string',
				'email' => 'email',
				'password' => 'string',
				'usertype_id' => 'string'
				
			];
			
			$rules = [
				'pseudo' => 'required',
				//'email' => 'required',
				'email' => 'required|email|unique:user,email',
				'password' => 'required',
				//'password' => 'required | secure',
				'usertype_id' => 'required'
			];				
					
					

			$data = $this->CleanData($inputs, $fields, $rules, $check_token, $token_name, 'signinview', 'inscription');

			
			
			if(empty($this->errors)){ // NO ERRORS GO ON PROCESS
	
						
			// GET DATA FROM THE SANITIZED AND VALDATED POST DATA ARRAY

          	$pseudo =  $data['pseudo'];
			$email =  $data['email'];
			$password =  $data['password'];
			$usertypeid = $data['usertype_id'];
			
			
				
			// CHECKING FILE UPLOAD
			$request = new \Inc\Request();

			
			if( null !== $request->getFile()){

				$file = new \Inc\File();
				
				

				$status = $file->get('photo','error');
				$post_image = $file->get('photo');


				$photo = checkUploadStatus($status, $post_image);
				

				
				if($photo == false){

					$action = SessionManager::getInstance()->get('ACTION');

					$this->UserRedirect($action);	

				}
				
			} // END OF  FILE UPLOAD CHECKING

			try{
	
						/*****************
						** Initialize $usertype_id according to wether we are comming from the frontend or the backend  user AddForm		
						**********************/

					
						
						$is_enabled="1";

						// GENERATE TOKEN FOR ACCOUNT ACTIVATION ,MUST ADD TOKEN EXPIRATION DATE TO USER'S ACTIVATION 
						
						$token = $cleanobject->get_token('activation');
					   
						$result = $this->model->registerUser($usertypeid, $pseudo, $email, $password, $photo, $token);

						if ($result ) {
							
							
							$id = SessionManager::getInstance()->get('LASTUSERID');
							
							$this->UserActivationEmail( $email,$pseudo, $id,  $token);
							
						 }
						 
						
						$action = SessionManager::getInstance()->get('ACTION');

						initmessage($action,$result); //generate a message according to the action in process
						$this->UserRedirect($action);									 
						 
					}
					catch(Exception $e) {
						$errorMessage = $e->getMessage();
						require'view/errorView.php';
				}
				
	}
}
   
	/**
	 * Process data sanitize and validate 
	 * @ PAram $post : form data, $filters, $rules and $checktoken
	 * @ Return 
	 */

	 public function CleanData($inputs, $fields, $rules, $check_token, $token_name, $path, $form){

		
		$cleanobject = new \Inc\Clean();

		if($check_token)
		{
			
		   if($check_token == "ras"){ // WE HAVE A MATCH OF TOKENS 
			   
			   
			   SessionManager::getInstance()->sessionvarUnset($token_name.'_token');
			   SessionManager::getInstance()->sessionvarUnset($token_name.'_token_time');

			  
			   
			   // SANITIZE DATA
			   
			   $data = $cleanobject->sanitize_inputs($inputs, $fields, FILTER_SANITIZE_STRING, self::$filters, $trim = true);

			   
			   
			   //VALIDATE DATA
			   $this->errors = $cleanobject->validate($data, $rules);	
			   
			   if(!empty($this->errors)){
				   
				   SessionManager::getInstance()->set('errors', $this->errors);

				   // IF ADMIN LOGGED REDIRECT TO ADMIN ADDUSER VIEW
				   $action = SessionManager::getInstance()->get('ACTION');
				   $this->UserRedirect($action);	
				   
				   
			    }else{
					return $data;
				}
				   
		   }else{ // IN CASE OF CSRF PROBLEM


			   $action = "tokenlife";
			   
			   //  INITIATE DISPLAY MESSAGE
			   initmessage($action,$check_token);
			   SessionManager::getInstance()->sessionvarUnset($token_name.'_token');
			   SessionManager::getInstance()->sessionvarUnset($token_name.'_token_time');
			   

			   \Http::redirect("$path-user.html#$form");
		   }
	   }
	 }

	/**
	 * Sending  account activation email  to user after inscription
	 * @param   $post [pseudo, email]
	 * @param  string $activation_code  
	 */

	public function UserActivationEmail( $email,$pseudo, $id, $token)
	{
		$subject = "Activation de votre compte";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
				
		$message = "Bonjour " . $pseudo. ", bienvenue sur mon blog !\r\n\r\nPour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/poo/useractivation-".$id."-".$token. "-user.html\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";

		mail($email, $subject, $message, $headers);
		
	}

	 /** FUNCTION USED TO ACTIVATE USER'S ACCOUNT FROM MAIL LINK
	*	  ALSO USED TO ACTIVATE OR DISACIVATE USER'S ACCOUNT FROM ADMIN DASHBOARD
	* Get email and activation key from activation link and call userManager tocheck correspondance in database.
	*  Delete activation_code from database to activate user account.
	* @param   $userid, $email, $token, $isactivated
	* 
	*/

   public function userActivation($link_userid, $link_email, $link_token, $isactivated)
	{
	   
	   //  USER ACTIVATION PROCESSED FROM ADMIN DASHBOARD
	   
	   if( $link_token === NULL){
			   
			   

			   if($isactivated == 'on'){

				   $token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

				   $token = password_hash($token,PASSWORD_DEFAULT);
			   
			   }elseif($isactivated == 'off'){

				   $token = null;
				   
			   }

				$adminactivation = $this->model->userActivate($link_userid, $token); 

		   		
		   		\Http::redirect('index.php?action=usersadmin');

		   	 	// END OF USER' ACTIVATION FROM ADMIN DASHBOARD	

		
	   }else{ // USER ACTIVATION PROCESSED FROM USER'S MAIL LINK
		
				$getactivationcode = $this->model->getUseractivationcode($link_userid);
						   
	   }
		
	   // IF WE HAVE AN ACIVATION CODE MATCH : ACTIVATE ACCOUNT
	  
	   if ( ($getactivationcode) && ( $getactivationcode['is_activated'] === $link_token) )  {
			   
			   

			   $activatedaccount = $this->model->userActivate($link_userid);
		}

	 	$this->DisplayActivationMessage($getactivationcode, $activatedaccount);
	}   		

	 /**************************************************************************
	#DISPLAY ACCOUNT ACTIVATION MESSAGE
	#PARAMS $getactivationcode : GET ACTIVATION CODE, $activatedaccount : is account activated
	*****************************************************************************/

	public function DisplayActivationMessage($getactivationcode, $activatedaccount){

		// TEST ACTIVATION RESULT TO GENERATE ACTION MESSAGE TO BE DISPLAYAED TO USER

		if( isset($activatedaccount ) && ($activatedaccount == true)){ // USER'ACTIVATION FROM MAIL LINK IS OK

			SessionManager::getInstance()->set('actionmessage', 'F&eacute;licitations ! Votre compte vient d\'&ecirc;tre  activ&eacute;');
			SessionManager::getInstance()->set('alert_flag', 1);
			\Http::redirect('loginview-user.html#login');
			

		}elseif( isset($activatedaccount ) && ($activatedaccount == false)){

			SessionManager::getInstance()->set('actionmessage', 'D&eacute;sol&eacute; ! Probl&egrave;me lors de l\'activation de votre compte. <BR>Merci de contacter l\'administrateur via le formulaire de contact ');
			SessionManager::getInstance()->set('alert_flag', 0);
			\Http::redirect('signinview-user.html#inscription');
			
		}

		elseif(( $getactivationcode ) && ( $getactivationcode['is_activated'] == NULL )){

			SessionManager::getInstance()->set('actionmessage', 'Votre compte est d&egrave;j&agrave;  activ&eacute; ');
			SessionManager::getInstance()->set('alert_flag', 1);
			\Http::redirect('loginview-user.html#login');
			
		}
		elseif(( $getactivationcode ) && ( $getactivationcode['is_activated'] != NULL )){

			SessionManager::getInstance()->set('actionmessage', 'Mauvaise cl&eacute; d\'activativation');
			SessionManager::getInstance()->set('alert_flag', 0);
			\Http::redirect('signinview-user.html#inscription');
			
		}

	}

 
	# **************
    # Redirect User to Frontend or backend  signup form 
    # @Param $action : user action **************

	public function UserRedirect($action){
		
		if($action == 'usersignin'){
							 
			\Http::redirect(' signinview-user.html#inscription');

	   }elseif($action == 'useradd'){

		   \Http::redirect(' index.php?action=adduserview');

	   }elseif($action === "newpass"){

			\Http::redirect('passreinitnew-user.html#newpass');

	   }elseif($action === "passresetrequest"){

		\Http::redirect('passresetrequest-user.html#passresetrequest');
   	   }
	}


	
	# **************
    # Display user Login form
    # **************



        public function loginView( ) {

			require'view/frontend/loginView.php';

        }


		# *********************************************
        # Verify User  Login
		#$post : form data , $URL : to check referer , $token_name
        # ***********************************************



        public function verifyLogin($post, $URL, $token_name) {

			
			SessionManager::getInstance()->sessionvarUnset('errors');
			
			// CHECK LOGIN CSRF TOKEN
			$cleanobject = new \Inc\Clean();
			$check_token = $cleanobject->check_token(600,  $URL.'loginview-user.html', $token_name);
			
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

			

			$rules = [
				'email' => 'required',
				'password' => 'required'
				//'email' => 'required|email|unique:user,email'
			];
			
			$data = $this->CleanData($inputs, $fields, $rules, $check_token, $token_name, 'loginview','login');
			
			
			
			// VALIDATION OF FORM DATA IS OK / VERIFY USER  LOGIN AND PASSWORD
            

			if(empty($this->errors)){
			

				$action = SessionManager::getInstance()->get('ACTION');
                

				$result = $this->model->loginUser($email, $password) ;	
				
				if( ($result) &&  ($result == 'not_activated') ){ // USER ACCOUNT NOT ACTIVATED

					$result = 'account_not_activated';
					initmessage($action,$result);
					
					\Http::redirect('loginview-user.html#login');
					

				}elseif (($result) &&  ($result != 'not_activated')){ // LOGIN AND PASSWORD MATCH IN DB
				
					
					$this->SetUserSessionVars($result);

					

					if(SessionManager::getInstance()->get('actionmessage')) SessionManager::getInstance()->sessionvarUnset('actionmessage');

					$this->verifyType(); // Verify type of user ( level ) : Admin or Guest  and redirect to Post view or dashboard

				
				}else{ // LOGIN OR PASSWORD OR BOTH DON'T MATCH

					initmessage($action,$result);
					
					\Http::redirect('loginview-user.html#login');
					
				}
			}

		}

		/**
	 * set user's session variables (USERID, USERTYPEID, PSEUDO, PHOTO, RESULT)
	 * @param  ARRAY  $result
	 * @return void
	 */

	public function SetUserSessionVars($result)
	{
		

		SessionManager::getInstance()->set('USERID', $result['id']); 
		SessionManager::getInstance()->set('USERTYPEID', $result['usertype_id']); 
		SessionManager::getInstance()->set('PSEUDO', $result['pseudo']); 
		SessionManager::getInstance()->set('PHOTO', $result['photo']); 
		SessionManager::getInstance()->set('RESULT', $result); 

	}

        # ********************************
        # Verify type of user ( level ) : Admin or Guest 
		# and redirect to Post view or dashboard
        # *********************************

		public function verifyType(){

			
			$from = SessionManager::getInstance()->get('FROM');
			
			
			$pseudo = SessionManager::getInstance()->get('PSEUDO');
			$action = SessionManager::getInstance()->get('ACTION');
			$result = SessionManager::getInstance()->get('RESULT');

			if(SessionManager::getInstance()->get('USERTYPEID') == 3){ // IF GUEST 

					

					if(null !== SessionManager::getInstance()->get('POSTID')){// IF COMES FROM A POST VIEW THEN SEND BACK TO THE POST VIEW
						$postid = SessionManager::getInstance()->get('POSTID');
						
						\Http::redirect(' frontpost-'.$postid.'.html#post');
						
						
					}else{// IF  DOES NOT COME FROM A POST VIEW : SEND TO GUEST DASHBOARD PAGE

						\Http::redirect(' index.php?action=mycomments');
						
					}

					
				}elseif(SessionManager::getInstance()->get('USERTYPEID') == 1){ // IF ADMIN

					// IF COMES FROM A POST VIEW THEN SEND BACK TO THE POST VIEW

					if(null !== SessionManager::getInstance()->get('POSTID')){

						$postid = SessionManager::getInstance()->get('POSTID');
						
						
						\Http::redirect(' frontpost-'.$postid.'.html#post');
					}else{

						// IF DOES NOT COME FROM A POST VIEW : SEND TO ADMIN DASHBOARD PAGE

						\Http::redirect(' index.php?action=adminposts');
						
					}

					

				}
			
			
			
		}
	
        # **************
        # User logout
        # **************

		public function userlogout() {        
			
			if( SessionManager::getInstance() ){
				SessionManager::getInstance()->sessionvarUnset('USERID');
				SessionManager::getInstance()->sessionvarUnset('USERTYPEID');
				SessionManager::getInstance()->sessionUnset();
				SessionManager::getInstance()->sessionDestroy();

				\Http::redirect('accueil.html');
				
				
			}
			
        }

		# **************
        # Display user's password reset Requesting form
        # **************



        public function passresetRequest() {

			require'view/frontend/passresetView.php';

        }


		# **************
        # Display user's new password  form
        # **************



        public function passreinitNew() {

			require'view/frontend/passreinitView.php';

        }

	/***************************************************
	 * Get user's email 
	 * @param $post : form data, $URL to check referer, $token_name
	 ************************************************** */
	 

    public function passReset($post, $URL, $token_name) {

			// CHECK LOGIN CSRF TOKEN

			
			SessionManager::getInstance()->sessionvarUnset('errors');
		
			// CHECK LOGIN CSRF TOKEN
			$cleanobject = new \Inc\Clean();
			$check_token = $cleanobject->check_token(600,  $URL.'passresetrequest-user.html', $token_name);

			$email =  $post['email'];
					
			$inputs = [
				'email' => $email
				];

			$fields = [
				'email' => 'email'
				];

			

			$rules = [
				'email' => 'required'
				];

			
			$data = $this->CleanData($inputs, $fields, $rules, $check_token, $token_name, 'passresetrequest','passresetrequest');
									
			
			
			if(empty($this->errors)){
				
				$action = SessionManager::getInstance()->get('ACTION');

				$email =  $data['email'];
								
				// TESTS IF EMAIL  EXISTS
				$verifyemail = $this->model->VerifyUserEmail($email);
				

				if ($verifyemail) 
					{ 
						$token = $cleanobject->get_token('passreset');
						
						$userid = $verifyemail['id'];
							
						$passreset = $this->model->resetPassTokenInsert($userid, $token);
												
						if ($passreset ) {

							$pseudo = $verifyemail['pseudo'];
							
							$this->PassResetEmail( $email,$pseudo, $userid,  $token);
										
						}
						

				}else{
						$passreset = false;
						
				}
						initmessage($action,$passreset);// SET THE MESSAGE TO BE DISPLAYED TO THE USER
						
						\Http::redirect('passresetrequest-user.html#passresetrequest');
			}
        }


		/**
		 * Get email and reset password token from users email link and verify if the same token in user's record and if it is not expired
		 *if OK displays reset password form
		* @param  Parameter $link_email $link_token
		* 
		*/

        public function verifyPassresetToken($link_email,$link_token){
			
			
			$errors =array();
			
			$rules = [
						'link_email' => 'required',
						'link_token' => 'required'
						];

			$data = [
						'link_email' => $link_email,
						'link_token' => $link_token
					];
			
			$cleanobject = new \Inc\Clean();
			$errors = $cleanobject->validate($data, $rules);	
			
			if(!empty($errors)){
				
				SessionManager::getInstance()->set('errors', $errors);
				
				\Http::redirect('passresetrequest-user.html');
				
				
			}else{

				
							
				$verifyemailtoken = $this->model->VerifyEmailToken($link_email, $link_token);
				
				$action = SessionManager::getInstance()->get('ACTION');


				if ($verifyemailtoken === true){ 

						SessionManager::getInstance()->set('LINK_EMAIL', $link_email);
						SessionManager::getInstance()->set('LINK_TOKEN', $link_token);
						
						\Http::redirect('passreinitnew-user.html');
						
				}else{
						
						initmessage($action,$verifyemailtoken);
						
						\Http::redirect('passresetrequest-user.html');

				}

				
			}

        }

	/**
	 * Get data of new password form and verify if password and confirmed password are equal
	 * @param  Parameter $post : form data, $URL to check referer , $token_name
	 * 
	 */

    public function getNewPass($post, $URL, $token_name){

			
			SessionManager::getInstance()->sessionvarUnset('errors');
		
			// CHECK LOGIN CSRF TOKEN
			$cleanobject = new \Inc\Clean();
			$check_token = $cleanobject->check_token(600,  $URL.'passreinitnew-user.html', $token_name);
			
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

			

			$rules = [
				//'newpassword' => 'required | secure',
				'newpassword' => 'required',
				'confirmnewpassword' => 'required | same:newpassword'
				];

				$data = $this->CleanData($inputs, $fields, $rules, $check_token, $token_name, 'passreinitnew','newpass');
			
			
			if(empty($this->errors)){
			

					
						$newpass = $data['newpassword'];
						$link_email = $data['email'];
						$link_token = $data['reset_link_token'];

						
						$hash = password_hash($newpass,PASSWORD_DEFAULT);
						
						$newpass = $hash;

						$updateNewPass = $this->model->updatePass($newpass, $link_email, $link_token);

						
						$action  = SessionManager::getInstance()->get('ACTION');

						initmessage($action,$insertNewPass);

						if ($updateNewPass) 
						{ 
							
							$action  = SessionManager::getInstance()->get('ACTION');

							initmessage($action,$updateNewPass);
							
							\Http::redirect('loginview-user.html#login');

						}else{
						
						$action  = SessionManager::getInstance()->get('ACTION');
						initmessage($action,$updateNewPass = false);

						
						\Http::redirect('passreinitview-user.html#newpass');
					}
				}
		}

			/**
	 * Sending  reset password token email  to user after password forgot
	 * @param  Parameter $email,$pseudo, $id, $token
	 * 
	 */

	public function PassResetEmail( $email,$pseudo, $id, $passreset_token)
	{
		$subject = "Réinitialisation de votre Mot de Passe";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		
		$message = "Bonjour " . $pseudo. ", Afin de procéder à la Réinitialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/poo/passreinitialisation-". $email."-". $passreset_token . "-user.html\r\n\r\n----------------------.";
		
		mail($email, $subject, $message, $headers);
		
	}

    


}

<?php

namespace Controllers;

require_once'Controllers/Controller.php';

use Inc\SessionManager;
use Inc\Clean;
use Inc\FileUpload;

class User extends Controller{

	private $errors = [];
	protected $model;
	protected $modelName = \Models\UserManager::class;
	protected $messageDisplay;

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


	   
    /***********************************************
	 * Checking  contact form csrf token and sending the mail ***
	 * @param  Parameter $contact-post, $URL, $tokenName***
	 * ***********************************************/
	 
	public function checkContactData($post, $URL, $tokenName){

		SessionManager::getInstance()->sessionvarUnset('errors');
		$action = SessionManager::getInstance()->get('ACTION');

		$cleanObject = new \Inc\Clean();
		$checkToken = $cleanObject->checkToken(600,  $URL.'accueil.html', $tokenName);
		
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

		$data = $this->CleanData($inputs, $fields, $rules, $checkToken, $tokenName, 'accueil', 'contact');
		
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
			
		$this->$messageDisplay->initmessage($action,$email_ok);
		
		\Http::redirect('accueil.html#contact');
				
	}

	# **************
	# User Add View
	# **************

	public function addUserView($action){
		
		if(isset($action) && ($action == "signinview")){

			// GET THE HIDEN FIELD WITH CRSF TOKEN
			$signuptoken = new \Inc\Clean();
			$tokenField = $signuptoken->get_token_field('newuser');
		
			require'view/frontend/signinView.php';

		}elseif(isset($action) && ($action == "adduserview")){

			if( !$this->is_Admin() ) $this->redirectLogin();
			require'view/backend/adduserView.php';

		}
		
	}
    
	# *****************************
	# User Add : this function can be called
	# from both front and back end
	#@params $post, $URL, $tokenName
	# *****************************

	public function addUser($post, $URL, $tokenName) {
		
		//  errors is the array that contains data input validation errors
		
		$cleanObject = new \Inc\Clean();
		
		SessionManager::getInstance()->sessionvarUnset('errors');
		$action = SessionManager::getInstance()->get('ACTION');

		if($action == 'usersignin'){
		
			$userTypeId = "3"; // USER ROLE

			$checkToken = $cleanObject->check_token(600,  $URL.'signinview-user.html', $tokenName);

		}
		if($action == 'useradd'){ // for admin user

			$checkToken = "ras";
			$userTypeId = $post->get('usertype_id'); // USER ROLE
			
		}
		
		$pseudo =  $post->get('pseudo');
		$email =  $post->get('email');
		$password =  $post->get('password');

		$inputs = [
			'pseudo' => $pseudo,
			'email' => $email,
			'password' => $password,
			'usertype_id' => $userTypeId
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
		
		$data = $this->CleanData($inputs, $fields, $rules, $checkToken, $tokenName, 'signinview', 'inscription');
		
		if(empty($this->errors)){ // NO ERRORS GO ON PROCESS
			
		// GET DATA FROM THE SANITIZED AND VALDATED POST DATA ARRAY

			$pseudo =  $data['pseudo'];
			$email =  $data['email'];
			$password =  $data['password'];
			$userTypeId = $data['usertype_id'];

			// CHECKING FILE UPLOAD
			$request = new \Inc\Request();
			
			if( null !== $request->getFile()){

				$file = new \Inc\File();
				$fileUpload = new \Inc\FileUpload();

				$status = $file->get('photo','error');
				$postImage = $file->get('photo');

				$photo = $fileUpload->checkUploadStatus($status, $postImage);
				
				if($photo == false){

					$action = SessionManager::getInstance()->get('ACTION');

					$this->UserRedirect($action);	

				}
				
			} // END OF  FILE UPLOAD CHECKING

			try{

				// Initialize $usertype_id according to wether we are comming from the frontend or the backend  user AddForm		
													
				$isEnabled="1";

				// GENERATE TOKEN FOR ACCOUNT ACTIVATION ,MUST ADD TOKEN EXPIRATION DATE TO USER'S ACTIVATION 
				
				$token = $cleanObject->get_token('activation');
				
				$result = $this->model->registerUser($userTypeId, $pseudo, $email, $password, $photo, $token);

				if ($result ) {
									
					$id = SessionManager::getInstance()->get('LASTUSERID');
					
					$this->UserActivationEmail( $email,$pseudo, $id,  $token);
					
				}
								
				$action = SessionManager::getInstance()->get('ACTION');

				$this->messageDisplay->initmessage($action,$result); //generate a message according to the action in process
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

	 public function CleanData($inputs, $fields, $rules, $checkToken, $tokenName, $path, $form){
		
		$cleanObject = new \Inc\Clean();

		if($checkToken)
		{
			
		   if($checkToken == "ras"){ // WE HAVE A MATCH OF TOKENS 
			   			   
			   SessionManager::getInstance()->sessionvarUnset($tokenName.'_token');
			   SessionManager::getInstance()->sessionvarUnset($tokenName.'_token_time');
			   
			   // SANITIZE DATA
			   
			   $data = $cleanObject->sanitize_inputs($inputs, $fields, FILTER_SANITIZE_STRING, self::$filters, $trim = true);
			   
			   //VALIDATE DATA
			   $this->errors = $cleanObject->validate($data, $rules);	
			   
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
			   $this->messageDisplay->initmessage($action,$checkToken);

			   SessionManager::getInstance()->sessionvarUnset($tokenName.'_token');
			   SessionManager::getInstance()->sessionvarUnset($tokenName.'_token_time');
			   
			   if(SessionManager::getInstance()->get('ACTION') == 'userupdate'){
			   		\Http::redirect("$path-useradmin.html#$form");
			   }else{
					\Http::redirect("$path-user.html#$form");
			   }
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
		$message = "Bonjour " . $pseudo. ", bienvenue sur mon blog !\r\n\r\nPour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/poo/useractivation-".$id."-".$token. "-useradmin.html\r\n\r\n----------------------\r\n\r\nCeci est un mail automatique, Merci de ne pas y r&eacute;pondre.";

		mail($email, $subject, $message, $headers);
	}

	
	# **************
    # Redirect User to Frontend or backend  signup form 
    # @Param $action : user action **************

	public function UserRedirect($action){
		
		if($action == 'usersignin'){
							 
			\Http::redirect(' signinview-user.html#inscription');

	   }elseif($action == 'useradd'){

		\Http::redirect(' index.php?action=adduserview&controller=user');

	   }elseif($action == 'userupdate'){

		   \Http::redirect('index.php?action=myprofile&controller=useradmin&id='.SessionManager::getInstance()->get('UPDATEUSERID'));

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

		// GET THE HIDEN FIELD WITH CRSF TOKEN
		$loginToken = new \Inc\Clean();
		$tokenField = $loginToken->get_token_field('login');

		require'view/frontend/loginView.php';

	}

	# *********************************************
	# Verify User  Login
	#$post : form data , $URL : to check referer , $tokenName
	# ***********************************************

	public function verifyLogin($post, $URL, $tokenName) {

		$request =  new \Inc\Request;
		//$messageDisplay = new \Inc\MessageDisplay();
		SessionManager::getInstance()->sessionvarUnset('errors');
		
		// CHECK LOGIN CSRF TOKEN
		$cleanObject = new \Inc\Clean();
		$checkToken = $cleanObject->check_token(600,  $URL.'loginview-user.html', $tokenName);
		
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
		
		$data = $this->CleanData($inputs, $fields, $rules, $checkToken, $tokenName, 'loginview','login');
				
		// VALIDATION OF FORM DATA IS OK / VERIFY USER  LOGIN AND PASSWORD
		
		if(empty($this->errors)){
		
			$action = SessionManager::getInstance()->get('ACTION');
			
			$result = $this->model->loginUser($email, $password) ;	
			
			if( ($result) &&  ($result == 'not_activated') ){ // USER ACCOUNT NOT ACTIVATED

				$result = 'account_not_activated';
				$this->messageDisplay->initmessage($action,$result);
				
				\Http::redirect('loginview-user.html#login');
				
			}elseif (($result) &&  ($result != 'not_activated')){ // LOGIN AND PASSWORD MATCH IN DB
							
				$this->SetUserSessionVars($result);

				if(SessionManager::getInstance()->get('actionmessage')) SessionManager::getInstance()->sessionvarUnset('actionmessage');

				$this->verifyType(); // Verify type of user ( level ) : Admin or Guest  and redirect to Post view or dashboard
			
			}else{ // LOGIN OR PASSWORD OR BOTH DON'T MATCH

				$this->messageDisplay->initmessage($action,$result);
				
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

		if($this->is_Guest()){ // IF GUEST 

			if(null !== SessionManager::getInstance()->get('POSTID')){// IF COMES FROM A POST VIEW THEN SEND BACK TO THE POST VIEW
				$postid = SessionManager::getInstance()->get('POSTID');
				
				\Http::redirect(' frontpost-'.$postid.'.html#post');
								
			}else{// IF  DOES NOT COME FROM A POST VIEW : SEND TO GUEST DASHBOARD PAGE

				\Http::redirect(' index.php?action=mycomments&controller=commentadmin');
			}

		}elseif($this->is_Admin()){ // IF ADMIN

			// IF COMES FROM A POST VIEW THEN SEND BACK TO THE POST VIEW

			if(null !== SessionManager::getInstance()->get('POSTID')){

				$postid = SessionManager::getInstance()->get('POSTID');
								
				\Http::redirect(' frontpost-'.$postid.'.html#post');
			}else{// IF DOES NOT COME FROM A POST VIEW : SEND TO ADMIN DASHBOARD PAGE

				\Http::redirect(' index.php?action=adminposts&controller=postadmin');
				
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

	public function passResetRequest() {

		// GET THE HIDEN FIELD WITH CRSF TOKEN
		$loginToken = new \Inc\Clean();
		$tokenField = $loginToken->get_token_field('passreset');

		require'view/frontend/passresetView.php';
	}


	# **************
	# Display user's new password  form
	# **************



	public function passReinitNew() {
		
		$passReinitnewToken = new \Inc\Clean();
		//$messageDisplay = new \Inc\MessageDisplay();

		$tokenField = $passReinitnewToken->get_token_field('newpass');

		if((null !== SessionManager::getInstance()->get('LINK_EMAIL')) && (null !== SessionManager::getInstance()->get('LINK_TOKEN'))){
			$linkEmail = SessionManager::getInstance()->get('LINK_EMAIL');
			$linkToken = SessionManager::getInstance()->get('LINK_TOKEN');

		}
		require'view/frontend/passreinitView.php';

	}

	/***************************************************
	 * Get user's email 
	 * @param $post : form data, $URL to check referer, $tokenName
	 ************************************************** */
	 
    public function passReset($post, $URL, $tokenName) {

		SessionManager::getInstance()->sessionvarUnset('errors');
	
		// CHECK LOGIN CSRF TOKEN
		$cleanObject = new \Inc\Clean();
		$checkToken = $cleanObject->check_token(600,  $URL.'passresetrequest-user.html', $tokenName);

		$email =  $post['email'];
		//$email = $post->get('email');
				
		$inputs = [
			'email' => $email
			];

		$fields = [
			'email' => 'email'
			];

		$rules = [
			'email' => 'required'
			];
		
		$data = $this->CleanData($inputs, $fields, $rules, $checkToken, $tokenName, 'passresetrequest','passresetrequest');
				
		if(empty($this->errors)){
			
			$action = SessionManager::getInstance()->get('ACTION');
			$email =  $data['email'];
							
			// TESTS IF EMAIL  EXISTS
			$verifyEmail = $this->model->VerifyUserEmail($email);
			

			if ($verifyEmail) 
				{ 
					$token = $cleanObject->get_token('passreset');
					$userid = $verifyEmail['id'];
					$passreset = $this->model->resetPassTokenInsert($userid, $token);
											
					if ($passreset ) {

						$pseudo = $verifyEmail['pseudo'];
						$this->PassResetEmail( $email,$pseudo, $userid,  $token);
									
					}
			}else{
					$passreset = false;
			}
			$this->messageDisplay->initmessage($action,$passreset);// SET THE MESSAGE TO BE DISPLAYED TO THE USER
			
			\Http::redirect('passresetrequest-user.html#passresetrequest');
		}
    }


	/**
	 * Get email and reset password token from users email link and verify if the same token in user's record and if it is not expired
	 *if OK displays reset password form
	* @param  Parameter $linkEmail $linkToken
	* 
	*/

	public function verifyPassresetToken($linkEmail,$linkToken){
				
		$errors =array();
		
		$rules = [
					'linkEmail' => 'required',
					'linkToken' => 'required'
					];

		$data = [
					'linkEmail' => $linkEmail,
					'linkToken' => $linkToken
				];
		
		$cleanObject = new \Inc\Clean();
		$errors = $cleanObject->validate($data, $rules);	
		
		if(!empty($errors)){
			
			SessionManager::getInstance()->set('errors', $errors);
			
			\Http::redirect('passresetrequest-user.html');
						
		}else{			
						
			$verifyEmailToken = $this->model->VerifyEmailToken($linkEmail, $linkToken);
			
			$action = SessionManager::getInstance()->get('ACTION');

			if ($verifyEmailToken === true){ 

					SessionManager::getInstance()->set('LINK_EMAIL', $linkEmail);
					SessionManager::getInstance()->set('LINK_TOKEN', $linkToken);
					
					
					\Http::redirect('passreinitnew-user.html');
					
			}else{
					//$messageDisplay = new \Inc\MessageDisplay();
					$this->messageDisplay->initmessage($action,$verifyEmailToken);
					
					\Http::redirect('passresetrequest-user.html');

			}

			
		}

	}

	/**
	 * Get data of new password form and verify if password and confirmed password are equal
	 * @param  Parameter $post : form data, $URL to check referer , $tokenName
	 * 
	 */

    public function getNewPass($post, $URL, $tokenName){
			
			SessionManager::getInstance()->sessionvarUnset('errors');
		
			// CHECK LOGIN CSRF TOKEN
			$cleanObject = new \Inc\Clean();
			$checkToken = $cleanObject->checkToken(600,  $URL.'passreinitnew-user.html', $tokenName);
			
			/*$newPassword = $post->get('newpassword');
			$confirmNewPassword =  $post->get('confirmnewpassword');
			$linkToken = $post->get('reset_link_token');
			$email = $post->get('email');*/

			$newPassword = $post['newpassword'];
			$confirmNewPassword =  $post['confirmnewpassword'];
			$linkToken = $post['reset_link_token'];
			$email = $post['email'];

			$inputs = [
				'newpassword' => $newPassword,
				'confirmnewpassword' => $confirmNewPassword,
				'email' => $email,
				'reset_link_token' => $linkToken
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

			$data = $this->CleanData($inputs, $fields, $rules, $checkToken, $tokenName, 'passreinitnew','newpass');
						
			if(empty($this->errors)){
					
				$newPass = $data['newpassword'];
				$linkEmail = $data['email'];
				$linkToken = $data['reset_link_token'];
				
				$hash = password_hash($newPass,PASSWORD_DEFAULT);
				
				$newPass = $hash;

				$updateNewPass = $this->model->updatePass($newPass, $linkEmail, $linkToken);
				
				//$action  = SessionManager::getInstance()->get('ACTION');

				//$this->$messageDisplay->initmessage($action,$updateNewPass);

				//$messageDisplay = new \Inc\MessageDisplay();
				$action  = SessionManager::getInstance()->get('ACTION');

				if ($updateNewPass) 
				{ 
					
					$this->messageDisplay->initmessage($action,$updateNewPass);
					
					\Http::redirect('loginview-user.html#login');

				}else{
				
					//$action  = SessionManager::getInstance()->get('ACTION');
					$this->messageDisplay->initmessage($action,$updateNewPass = false);

				
				\Http::redirect('passreinitview-user.html#newpass');
				}
			}
		}

	/**
	 * Sending  reset password token email  to user after password forgot
	 * @param  Parameter $email,$pseudo, $id, $token
	 * 
	 */

	public function PassResetEmail( $email,$pseudo, $id, $passResetToken)
	{
		$subject = "Réinitialisation de votre Mot de Passe";
		$headers = "From: " . CF_EMAIL . "\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		$message = "Bonjour " . $pseudo. ", Afin de procéder à la Réinitialisation de votre Mot de Passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur Internet.\r\n\r\nhttps://ocblog.capdeco.com/poo/passreinitialisation-". $email."-". $passResetToken . "-user.html\r\n\r\n----------------------.";
		
		mail($email, $subject, $message, $headers);
		
	}
}
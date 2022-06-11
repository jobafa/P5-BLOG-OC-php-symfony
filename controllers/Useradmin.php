<?php

namespace Controllers;

require_once'Controllers/Controller.php';

use Inc\SessionManager;
use Inc\MessageDisplay;
use Inc\FileUpload;
use Controllers\User;

class Useradmin extends Controller{

	private $errors = [];
	protected $model;
	protected $modelName = \Models\UserManager::class;

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



    /******************************
    * Get user's information for update.
    * @param $post data inputs, $userId
    *  *****************************/
   

    public function userUpdate($post,$userId)
    {        
        if( !$this->is_Admin() && !$this->is_Guest()) $this->redirectLogin();

       SessionManager::getInstance()->set('UPDATEUSERID', $userId);// for userRedirect function 

       // CHECKING FILE UPLOAD
	
		$user = new \Controllers\User();

        $lastName =  $post['lastname'];
        $firstName =  $post['firstname'];
        $phoneNumber =  $post['phone_number'];
        $pseudo =  $post['pseudo'];
        $email =  $post['email'];

        $inputs = [
            'lastname' => $lastName,
            'firstname' => $firstName,
            'phonenumber' => $phoneNumber,
            'pseudo' => $pseudo,
            'email' => $email
            //'password' => $password,
            ];

        $fields = [
            'lastname' => 'string',
            'firstname' => 'string',
            'phonenumber' => 'string',
            'pseudo' => 'string',
            'email' => 'email'
            //'password' => 'string',
            ];
            
        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'phonenumber' => 'required | numeric |size:10',
            'pseudo' => 'required',
            //'email' => 'required',
            'email' => 'required | email'
            //'password' => 'required',
            //'password' => 'required | secure',
       ];

        if($user->is_Admin()){
           
            $userTypeId = $post['usertype_id'];
            $inputs['usertype_id'] = $userTypeId;

            $fields['usertype_id'] = 'string';                                     

            $rules['usertype_id'] = 'required';
                    
        }  
        $data = $user->CleanData($inputs, $fields, $rules, $checkToken = 'ras', $tokenName, 'userupdate', 'userupdate');
       
        if(empty($this->errors)){ // NO ERRORS GO ON PROCESS
            $file = new \Inc\File();
            $fileupload = new \Inc\FileUpload();

            if( $file->get('photo','error') !== 4){    // NO FILE UPLOADED
                
                $status = $file->get('photo','error');
                $userPhoto = $file->get('photo');

                $photo = $fileupload->checkUploadStatus($status, $userPhoto, $userId);
                
                if($photo == false){

                    \Http::redirect('index.php?action=myprofile&controller=useradmin&id='.$userId);
                    
                }
                
            }else{
                $photo = SessionManager::getInstance()->get('photo');
                
            } // END OF  FILE UPLOAD CHECKING


            try{

                $updateprofile = $this->model->userUpdateProfile($userId, $post, $photo);
                
                $action = SessionManager::getInstance()->get('ACTION');

                //generate a message according to the action in process
                $messagedisplay = new \Inc\MessageDisplay();
                $messagedisplay->initmessage($action,$updateprofile);
                
                /*if($user->is_Admin()){
                                        
                    \Http::redirect('index.php?action=usersadmin&controller=useradmin');
                }*/
                
                \Http::redirect("index.php?action=myprofile&id=$userId&controller=useradmin");

            }

            catch(Exception $e) {
                    $errorMessage = $e->getMessage();
                    require('view/errorView.php');
            }
        }

   }

    /*************************************
	 * Get user's information for Profile update.
	 * @param  Parameter $userId
	 *************************************/ 
	 
	public function userProfile($userId)
    {
        if( !$this->is_Admin() && !$this->is_Guest()) $this->redirectLogin();

        $profile = $this->model->getUser($userId);
        SessionManager::getInstance()->set('photo', $profile['photo']);
        
        $title = 'Edition Utilisateur';
        $messageDisplay = new \Inc\MessageDisplay();

        require('view/backend/usereditView.php');	
    }

   /*****************
    * Get user's  for,Admin.
     ******************/

    public function usersAdmin()
    {
        if( !$this->is_Admin() ) $this->redirectLogin();

        $getusers = $this->model->getUser();
        $title = 'Gestion des Utilisateurs';
        
        $cleanobject = new \Inc\Clean();// FOR ESCAPE OUTPUT FUNCTION

        require('view/backend/listusersView.php');	
    }

    # **************************************
    # Delete USER 
    #@PARAMS $userId, $usertypeid : is admin or not
    # ****************************************

    public function userDelete($userId, $usertypeid) {

        if( !$this->is_Admin() ) $this->redirectLogin();
    
        // DELETE USER'S COMMENTS

        $commentManager = new \Models\CommentManager();
        $deletecomment = $commentManager->deleteCommentPost($postId =null,$idcomment = null, $userId);
        
        // IF ADMIN DELETE USER'S POSTS

        if($usertypeid == 1){

            $postManager = new \Models\PostManager();
            $deletepost = $postManager->deletePost($postId = null, $userId);

        }
        
        // DELETE USER

        $deleteuser = $this->model->deleteUser($userId);
        
        $action = SessionManager::getInstance()->get('ACTION');

            //generate a message according to the action in process

        $messagedisplay = new \Inc\MessageDisplay();    
        $messagedisplay->initmessage($action,$deleteuser);
               
        //header('Location: index.php?action=usersadmin');
        \Http::redirect('index.php?action=usersadmin&controller=useradmin');
        
    }

     /** FUNCTION USED TO ACTIVATE USER'S ACCOUNT FROM MAIL LINK
	*	  ALSO USED TO ACTIVATE OR DISACIVATE USER'S ACCOUNT FROM ADMIN DASHBOARD
	* Get email and activation key from activation link and call userManager tocheck correspondance in database.
	*  Delete activation_code from database to activate user account.
	* @param   $userid, $email, $token, $isActivated
	* 
	*/

   public function userActivation($linkUserId, $linkEmail, $linkToken, $isActivated)
	{
        //if( !$this->is_Admin() ) $this->redirectLogin();
	   //  USER ACTIVATION PROCESSED FROM ADMIN DASHBOARD
	   
	   if( $linkToken === NULL){
			   
			   if($isActivated == 'on'){

				   $token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

				   $token = password_hash($token,PASSWORD_DEFAULT);
			   
			   }elseif($isActivated == 'off'){

				   $token = null;
				   
			   }

				$adminActivation = $this->model->userActivate($linkUserId, $token); 

		   		
		   		\Http::redirect('index.php?action=usersadmin&controller=useradmin');

		   	 	// END OF USER' ACTIVATION FROM ADMIN DASHBOARD	

		
	   }else{ // USER ACTIVATION PROCESSED FROM USER'S MAIL LINK
		
				$getActivationCode = $this->model->getUseractivationcode($linkUserId);
						   
	   }
		
	   // IF WE HAVE AN ACIVATION CODE MATCH : ACTIVATE ACCOUNT
	  
	   if ( ($getActivationCode) && ( $getActivationCode['is_activated'] === $linkToken) )  {
			   
			   $activatedAccount = $this->model->userActivate($linkUserId);
		}

	 	$this->DisplayActivationMessage($getActivationCode, $activatedAccount);
	}   		

	 /**************************************************************************
	#DISPLAY ACCOUNT ACTIVATION MESSAGE
	#PARAMS $getActivationCode : GET ACTIVATION CODE, $activatedAccount : is account activated
	*****************************************************************************/

	public function DisplayActivationMessage($getActivationCode, $activatedAccount){

		// TEST ACTIVATION RESULT TO GENERATE ACTION MESSAGE TO BE DISPLAYAED TO USER

		if( isset($activatedAccount ) && ($activatedAccount == true)){ // USER'ACTIVATION FROM MAIL LINK IS OK

			SessionManager::getInstance()->set('actionmessage', 'F&eacute;licitations ! Votre compte vient d\'&ecirc;tre  activ&eacute;');
			SessionManager::getInstance()->set('alert_flag', 1);
			\Http::redirect('loginview-user.html#login');
			
		}elseif( isset($activatedAccount ) && ($activatedAccount == false)){

			SessionManager::getInstance()->set('actionmessage', 'D&eacute;sol&eacute; ! Probl&egrave;me lors de l\'activation de votre compte. <BR>Merci de contacter l\'administrateur via le formulaire de contact ');
			SessionManager::getInstance()->set('alert_flag', 0);
			\Http::redirect('signinview-user.html#inscription');
			
		}

		elseif(( $getActivationCode ) && ( $getActivationCode['is_activated'] == NULL )){

			SessionManager::getInstance()->set('actionmessage', 'Votre compte est d&egrave;j&agrave;  activ&eacute; ');
			SessionManager::getInstance()->set('alert_flag', 1);
			\Http::redirect('loginview-user.html#login');
			
		}
		elseif(( $getActivationCode ) && ( $getActivationCode['is_activated'] != NULL )){

			SessionManager::getInstance()->set('actionmessage', 'Mauvaise cl&eacute; d\'activativation');
			SessionManager::getInstance()->set('alert_flag', 0);
			\Http::redirect('signinview-user.html#inscription');
			
		}

	}
}
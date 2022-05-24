<?php


#CHECK IS LOGGED ADMIN

*****************************************/

function is_admin(){

	if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){

		return true;

	}else{

		return false;

	}
}

/*****************************************
#CHECK IS LOGGED 

*****************************************/

function is_logged(){

	if(isset($_SESSION['USERTYPEID'])){

		return true;

	}else{

		return false;

	}
}



/*****************************************
#CHECK UPLOAD : IF OK MOVE TO UPLOADS FOLDER
@PARAM : $post_image
*****************************************/

function addImage($post_image,$id = 0){

	if (isset($post_image) && ($post_image['error'] == 0))
			{

				$allowed_image_extension = array(	"png","jpg","jpeg");
				
				// Get image file extension
				$file_extension = pathinfo($post_image["name"], PATHINFO_EXTENSION);
	
							
				// Validate file input to check if is with valid extension
				if (! in_array($file_extension, $allowed_image_extension)) {

					$_SESSION['actionmessage'] = 'Echec de l\'upload : Extension non authoris&eacute; !';
					$_SESSION['alert_flag'] = 0;
					
				}else if (($post_image["size"] > 2000000)) {// Validate  file size
					
					$_SESSION['actionmessage'] = 'Taille du fichier superieur &agrave; 2 Mo'; // ERROR MESSAGE
					$_SESSION['alert_flag'] = 0;
					
				}else {
					$dossier = "uploads/images/" ;
					$file_name = basename($post_image["name"]);
					$target = $dossier.$file_name;

					if (file_exists($target)) {// RENAME FILE IF EXISTS IN TARGET
						
						$timestamp=time();
						$file_name = $timestamp.'-'.$file_name;
						$target = $dossier.$file_name;

				    }
				}


					if (isset($_SESSION['alert_flag'])){
						$action = $_SESSION['ACTION'];

							switch ($action) {

										case 'updatepost':

											header('Location: index.php?action=modifypost&id=' . $id);
											exit;
	  
											break;
										case 'myprofile':
											
											header('Location: index.php?action=myprofile&id=' . $id);
											exit;
											
											break;

										case 'adduserview':
										
											header('Location: index.php?action=myprofile&id=' . $id);
											exit;
										
										break;

										case 'usersignin':
										
											//header('Location: index.php?action=myprofile&id=' . $id);

											header('Location: signinview-user.html#inscription');

											exit;
										
										break;

										default:
											
											
											
											break;
							}


						
						
					}else{
						if (move_uploaded_file($post_image["tmp_name"], $target)) {
							return $file_name;
						} else {

							return false;
							
						}
					}
			
			}
}

//**************************************************************************//
//*Cheks if upload is ok returns the uploaded file if no error*/
/*if not initiates the alert message var*/
//*@param $status,$post_image,$id
 /* @return  string**/

function checkUploadStatus($status,$post_image,$id = 0){


						if($status == UPLOAD_ERR_OK){
							
								
								$photo = addImage($post_image,$id);
								
								return $photo;

						}elseif	($status == UPLOAD_ERR_NO_FILE){ // NO FILE UPLOADED : NO PHOTO

						
								$photo = 'undraw_profile.svg';
								return $photo;
						}else {// THERE ARE UPLOAD ERRORS : CHECKING ERRORS
								
							switch ($status) {

								case UPLOAD_ERR_INI_SIZE:

									
									$_SESSION['actionmessage'] = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
									$_SESSION['alert_flag'] = 0;
									
									break;
								case UPLOAD_ERR_FORM_SIZE:
									
									$_SESSION['actionmessage'] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
									$_SESSION['alert_flag'] = 0;
									
									break;
								case UPLOAD_ERR_PARTIAL:
									
									$_SESSION['actionmessage'] = "The uploaded file was only partially uploaded";
									$_SESSION['alert_flag'] = 0;
									break;
								case UPLOAD_ERR_NO_FILE:
									
								 $_SESSION['actionmessage'] = "No file was uploaded";
									$_SESSION['alert_flag'] = 0;
									break;
								case UPLOAD_ERR_NO_TMP_DIR:
									
									$_SESSION['actionmessage'] = "Missing a temporary folder";
									$_SESSION['alert_flag'] = 0;
									break;
								case UPLOAD_ERR_CANT_WRITE:
									
									$_SESSION['actionmessage'] = "Failed to write file to disk";
									$_SESSION['alert_flag'] = 0;
									break;
								case UPLOAD_ERR_EXTENSION:
									
									$_SESSION['actionmessage'] = "Echec de l\'upload :Extension non authoris&eacute; !";
									$_SESSION['alert_flag'] = 0;
									break;

								default:
									
									$_SESSION['actionmessage'] = "Unknown upload error";
									$_SESSION['alert_flag'] = 0;
									break;


							}// END OF SWITCH

								return false;	
									
									 
						} // END OF ULOADS ERRORS CHECKING
}

//**************************************************************************//
//*Cheks if registred token is set and returns it*/
/*if not generates the token and returns it*/
//*@param string $name
 /* @return  string**/

function get_token($nom = '')
{
	
	if (isset($_SESSION[$nom.'_token'])) {
		return $_SESSION[$nom.'_token'];
    }

	 if($nom == 'activation'){ // GENERATE TOKEN FOR ACCOUNT ACTIVATION
		$token = bin2hex(random_bytes(35));
		return $token;
	 }
	// GENERATE CSRF TOKEN 
	$token = bin2hex(random_bytes(35));
	$_SESSION[$nom.'_token'] = $token;
	$_SESSION[$nom.'_token_time'] = time();
	return $token;
}


//**************************************************************************//
//*Cheks if registred token, lifetime token and received token are set, if tokens are equal and if lifetime is not over*/
/*Then if referer is the same as the one passed in params and returns true*/
//*@param int  string $temps, string $ , string $name
 /* @return  bool**/


function check_token($temps, $referer, $nom = '')
{
		$expiredtime = (time() - $temps);

		$_SESSION['expired_time'] = $expiredtime;
		$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
		if(isset($_SESSION[$nom.'_token']) && isset($_SESSION[$nom.'_token_time']) && isset($token)){
			if($_SESSION[$nom.'_token'] == $token){
				if($_SESSION[$nom.'_token_time'] >= $expiredtime){
					
					
						if($_SERVER['HTTP_REFERER'] == $referer){
							$error = "ras";
							return $error ;
						}else{
							
							$error = "referer";
							return $error;
						}
				}else{

						
					$error = "expiredtoken";
					return $error;
				}
			}else{
				$error = "token";
				return $error;
			}
		}else{

			return false;
		}
}

//**************************************************************************//
//*generates the hidden input field for the token*/
//*@param string $name
 /* @return string **/

function get_token_field($nom) {
	
	$token = get_token($nom);
	return '<input type="hidden" name="token" value="' . $token . '">';
   
}

//**************************************************************************//
//*sanitize $GET data*/
//*@param string $data = $_GET
 /* @return ARRAY **/


function sanitize_get_data($data){
				
		if (filter_has_var(INPUT_GET, 'action')) {

		
			// sanitize action
			$clean_action = filter_var($data['action'], FILTER_SANITIZE_STRING);
			$data['action'] = $clean_action ;
			

		}
		if (filter_has_var(INPUT_GET, 'from')) {

			// sanitize from
			$clean_action = filter_var($data['from'], FILTER_SANITIZE_STRING);
			$data['from'] = $clean_action ;

		}

		if (filter_has_var(INPUT_GET, 'controller')) {

			// sanitize from
			$clean_action = filter_var($data['controller'], FILTER_SANITIZE_STRING);
			$data['controller'] = $clean_action ;

		}

		if (filter_has_var(INPUT_GET, 'email')) {

			// sanitize email
			$clean_action = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
			$data['email'] = $clean_action ;

		}
		if (filter_has_var(INPUT_GET, 'token')) {

			// sanitize email
			$clean_action = filter_var($data['token'], FILTER_SANITIZE_STRING);
			$data['token'] = $clean_action ;

		}
		if (filter_has_var(INPUT_GET, 'id')) {

			// sanitize id
			$clean_id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

			if ($clean_id) {
				// validate id with options
				$id = filter_var($clean_id, FILTER_VALIDATE_INT, ['options' => [
					'min_range' => 1
				]]);

				$data['id'] = $id ;
				
			}
			
		}

		if (filter_has_var(INPUT_GET, 'page')) {

			// sanitize page
			$clean_page = filter_var($data['page'], FILTER_SANITIZE_NUMBER_INT);

			if ($clean_page) {
				// validate page with options
				$page = filter_var($clean_page, FILTER_VALIDATE_INT, ['options' => [
					'min_range' => 1
				]]);

				$data['page'] = $page ;
				
			}
			
		}
		return $data;

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
					$_SESSION['actionmessage'] =  'l\adresse email existe d&egrave;j&agrave; !';
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

					$_SESSION['actionmessage'] = 'l\adresse email existe d&egrave;j&agrave; !';
					$_SESSION['alert_flag'] = 0;
				 }
				elseif ( ($result != 1) && ($result != 'email')) {

					$_SESSION['actionmessage'] = 'Failed to Add User !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'verifylogin':

					if (($result) && ($result == 'account_not_activated')) {

					$_SESSION['actionmessage'] = 'Votre compte n\'est pas activ&eacute;. Merci de verifier votre messagerie pour l\'activer !';
					$_SESSION['alert_flag'] = 0;

				 }elseif(!$result ) {

					$_SESSION['actionmessage'] = 'Failed Bad Login Or Password !';
					$_SESSION['alert_flag'] = 0;
				 }
				else {

					$_SESSION['actionmessage'] = 'Bienvenue !';
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
					$_SESSION['actionmessage'] = 'Profile Utilistaeur mis &aacute; jour !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{

					$_SESSION['actionmessage'] = 'probl&eacute;me lors de la mise à jour !';

					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'userdelete':
				if ( $result ) {
					$_SESSION['actionmessage'] = 'Utilistaeur supprim&eacute; !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{
					$_SESSION['actionmessage'] = 'probl&egrave;me lors de la Suppression !';
					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'passreset':
				if ( $result ) {
					$_SESSION['actionmessage'] = ' Pour r&eacute;initialiser votre Mot de Passe. Merci de cliquer sur le lien  qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .   !';
					$_SESSION['alert_flag'] = 1;
				 }
				else{

					$_SESSION['actionmessage'] = "l'adresse email n'existe pas !";

					$_SESSION['alert_flag'] = 0;
				 }
                
                break;

				case 'passreinitialisation':

				if ( $result === "emailissue" ) {
					$_SESSION['actionmessage'] = " l'adresse email n'existe pas  !";
					$_SESSION['alert_flag'] = 0;
				 }
				elseif( !$result || ($result === "tokenissue") ){

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
						
					}elseif($result == 'expiredtoken' ){
						
						$_SESSION['actionmessage'] = ' Votre session est expir&eacute;e .  Merci recharger la page et de recommencer !';
						$_SESSION['alert_flag'] = 0;
					}
					
				 }else{
						$_SESSION['actionmessage'] = ' Probl&egrave;me de token .  Merci de recommencer !';
						$_SESSION['alert_flag'] = 0;
				 }
				
                break;
			 
			}
			
        }

		/**************************************************************************
		#DISPLAY ACCOUNT ACTIVATION MESSAGE
		#PARAMS $getactivationcode : GET ACTIVATION CODE, $activatedaccount : is account activated
		*****************************************************************************/

		function DisplayActivationMessage($getactivationcode, $activatedaccount){

			// TEST ACTIVATION RESULT TO GENERATE ACTION MESSAGE TO BE DISPLAYAED TO USER

			if( isset($activatedaccount ) && ($activatedaccount == true)){ // USER'ACTIVATION FROM MAIL LINK IS OK
				
					$_SESSION['actionmessage'] = 'F&eacute;licitations ! Votre compte vient d\'&ecirc;tre  activ&eacute;';
					$_SESSION['alert_flag'] = 1;

					header('Location: loginview-user.html#login');


			}elseif( isset($activatedaccount ) && ($activatedaccount == false)){
				$_SESSION['actionmessage'] = 'D&eacute;sol&eacute; ! Probl&egrave;me lors de l\'activation de votre compte. <BR>Merci de contacter l\'administrateur via le formulaire de contact ';
				$_SESSION['alert_flag'] = 1;

				header('Location: signinview-user.html#inscription');
			}

			elseif(( $getactivationcode ) && ( $getactivationcode['is_activated'] == NULL )){

				$_SESSION['actionmessage'] = 'Votre compte est d&egrave;j&agrave;  activ&eacute;';
				$_SESSION['alert_flag'] = 1;
				header('Location: loginview-user.html#login');

			}
			elseif(( $getactivationcode ) && ( $getactivationcode['is_activated'] != NULL )){

					$_SESSION['actionmessage'] = 'Mauvaise cl&eacute; d\'activativation';
					$_SESSION['alert_flag'] = 0;

					header('Location: signinview-user.html#inscription');

			}

		}


	# CHECK IF THERE IS AN ALERT MESSAGE TO DISPLAY
	# AND RETURN THE MESSAGE
	# returns $message

	function is_alertMessage(){

		$message = "";
		
		// CHECKS IF THERE IS A MESSAGE : ( ALERT ) TO BE DISPLAYED 

		if (isset($_SESSION['alert_flag']) &&  ($_SESSION['alert_flag'] == 0)){
			
			$classe = "alert-danger";

		}else if(isset($_SESSION['alert_flag']) &&  ($_SESSION['alert_flag'] == 1)){
			
			$classe = "alert-success";
		}
    
		if(isset($_SESSION['actionmessage']) && isset($_SESSION['alert_flag'])) {

			$actionmessage = $_SESSION['actionmessage'];

			ob_start();

		?>
		  
			<div class="alert <?= $classe ?> my-2 mx-2  alert-dismissible fade show" role="alert">
			  <em><?= $actionmessage ?></em>
			  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>

		<?php

			$message = ob_get_clean();

		}
		
		return $message;
}




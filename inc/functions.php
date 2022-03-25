<?php

function addImage($post_image){

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
					$_SESSION['actionmessage'] = 'File upload stopped by extension';
					$_SESSION['alert_flag'] = 0;
					require('view/backend/addpostView.php');
					//header('Location: index.php?action=addpostview');
					//exit;
					//echo $result;
				}   
				
				// Validate image file size
				else if (($post_image["size"] > 2000000)) {
					//throw new Exception('Image size exceeds 2MB!');
					$_SESSION['actionmessage'] = 'File upload stopped Size issue';
					$_SESSION['alert_flag'] = 0;
					//$message = "File upload Size issue";
					//header('Location: index.php?action=addpostview');
					require('view/backend/addpostView.php');
					//exit;
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
						$_SESSION['actionmessage'] = $message;
						$_SESSION['alert_flag'] = 0;
						require('view/backend/addpostView.php');
						//header('Location: index.php?action=addpostview&message='.$message);
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


function checkUploadStatus($status){
	
	// an error occurs
						if($status == UPLOAD_ERR_OK){
						   
								$post_image = $_FILES["photo"];
								
								$photo=addImage($post_image);
								return $photo;
									
						}elseif	($status == UPLOAD_ERR_NO_FILE){ // NO FILE UPLOADED : NO PHOTO
								$photo = 'undraw_profile.svg';
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
										
										$_SESSION['actionmessage'] = "File upload stopped by extension";
										$_SESSION['alert_flag'] = 0;
										break;

									default:
										
										$_SESSION['actionmessage'] = "Unknown upload error";
										$_SESSION['alert_flag'] = 0;
										break;
								}// END OF SWITCH

									
									 //header('Location: index.php?action=addpostview&message='.$message);

									 if(isset($_SESSION['USERTYPEID'] ) && ($_SESSION['USERTYPEID']  == 1)){
											//require('view/backend/addpostView.php');
											require('view/backend/adduserView.php');
											exit;
									 }else{
											require('view/frontend/signinView.php');
											exit;
									 }
						} // END OF ULOADS ERRORS CHECKING
}

//**************************************************************************//
//*Cheks if registred token is set and returns it*/
/*if not generates the token and returns it*/
//*@param string $name
 /* @return  string**/

function get_token($nom = '')
{
	//session_start();
	 if (isset($_SESSION[$nom.'_token'])) {
		//var_dump($nom);
		// unset($_SESSION[$nom.'_token']);
//unset($_SESSION[$nom.'_token_time']);
		 //var_dump($_SESSION[$nom.'_token']);
        return $_SESSION[$nom.'_token'];
    }
	//$token = uniqid(rand(), true);
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
{//var_dump($nom);
//session_start();
		$expiredtime = (time() - $temps);
		$_SESSION['expired_time'] = $expiredtime;
		$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
//var_dump($_SESSION[$nom.'_token']);
		if(isset($_SESSION[$nom.'_token']) && isset($_SESSION[$nom.'_token_time']) && isset($token)){
			if($_SESSION[$nom.'_token'] == $token){
				if($_SESSION[$nom.'_token_time'] >= $expiredtime){
					
					
						if($_SERVER['HTTP_REFERER'] == $referer){
							$error = "ras";
							return $error ;
						}else{
							//var_dump($_SERVER['HTTP_REFERER']);
							//exit;
							$error = "referer";
							return $error;
						}
				}else{//echo $_SESSION['blogcontact_token'].'<BR>'.$_POST['token'].'<BR>'.$_SESSION['blogcontact_token_time'].'<BR>'.$_SESSION['expired_time'] ;

						//exit;
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
	//var_dump($nom);
	//exit;
    //$token = create_csrf_token();
	$token = get_token($nom);
	return '<input type="hidden" name="token" value="' . $token . '">';
    //return '<input type="hidden" name='. $nom.'_token" value="' . $token . '">';
}

//**************************************************************************//
//*sanitize $GET data*/
//*@param string $data = $_GET
 /* @return ARRAY **/


function sanitize_get_data($data){
		//$res = filter_input(INPUT_GET, $data, FILTER_SANITIZE_SPECIAL_CHARS);
		//$res = filter_input(INPUT_GET, $data, FILTER_SANITIZE_ENCODED);

		
		if (filter_has_var(INPUT_GET, 'action')) {

			// sanitize action
			$clean_action = filter_var($data['action'], FILTER_SANITIZE_STRING);
			$data['action'] = $clean_action ;
//var_dump($clean_action);
		}
		if (filter_has_var(INPUT_GET, 'from')) {

			// sanitize from
			$clean_action = filter_var($data['from'], FILTER_SANITIZE_STRING);
			$data['from'] = $clean_action ;
//var_dump($clean_action);
		}
		if (filter_has_var(INPUT_GET, 'email')) {

			// sanitize email
			$clean_action = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
			$data['email'] = $clean_action ;
//var_dump($clean_action);
		}
		if (filter_has_var(INPUT_GET, 'token')) {

			// sanitize email
			$clean_action = filter_var($data['token'], FILTER_SANITIZE_STRING);
			$data['token'] = $clean_action ;
//var_dump($clean_action);
		}
		if (filter_has_var(INPUT_GET, 'id')) {

			// sanitize id
			$clean_id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
//var_dump($clean_id);
			if ($clean_id) {
				// validate id with options
				$id = filter_var($clean_id, FILTER_VALIDATE_INT, ['options' => [
					'min_range' => 1
				]]);
//var_dump($id);
				$data['id'] = $id ;
				// show the id if it's valid
				//echo $id === false ? 'Id article doit être au moins = 1' : $id;

			}
			/*else {
				 echo  $clean_id .'id est invalide.';
			}*/
		}

		if (filter_has_var(INPUT_GET, 'page')) {

			// sanitize id
			$clean_page = filter_var($data['page'], FILTER_SANITIZE_NUMBER_INT);
//var_dump($clean_page);
			if ($clean_page) {
				// validate id with options
				$page = filter_var($clean_page, FILTER_VALIDATE_INT, ['options' => [
					'min_range' => 1
				]]);
//var_dump($page);
				$data['page'] = $page ;
				// show the id if it's valid
				//echo $id === false ? 'Id article doit être au moins = 1' : $id;

			}
			/*else {
				 echo  $clean_id .'id est invalide.';
			}*/
		}
		return $data;
//EXIT;
}


/**
 *  Messages associated with the upload error code
 */
const MESSAGES = [
    UPLOAD_ERR_OK => 'File uploaded successfully',
    UPLOAD_ERR_INI_SIZE => 'File is too big to upload',
    UPLOAD_ERR_FORM_SIZE => 'File is too big to upload',
    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server',
    UPLOAD_ERR_CANT_WRITE => 'File is failed to save to disk.',
    UPLOAD_ERR_EXTENSION => 'File is not allowed to upload to this server',
];

function test_input($data) {

  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  //$data = htmlentities($data, ENT_QUOTES, 'UTF-8');

  return $data;
}




/*
function sanitize_my_data($data){

//ECHO 'DATA : <BR>'.$_SERVER['REQUEST_METHOD'].'<BR>';

	if(! is_array($data)){
//ECHO ' <BR>REQUEST : '.$_SERVER['REQUEST_METHOD'].'<BR>';
		foreach ($data as $key => $value) {
//echo $key.'  =  '.$value.'  Type :  '.gettype($value).'<BR>';
			//$result[$key] = sanitize_my_data($value);
			//$result[$key] = test_input($value);
			echo  $data[$key].'<BR>';
			
		}
		exit;
	}else{//ECHO '<BR>'.$data.'<BR>';
					//echo 'var  =  '.$data.'  Type :  '.gettype($data).'<BR>';
			//var_dump($type);
			if($_SERVER['REQUEST_METHOD'] == 'POST' ){
				
				ECHO 'POST';

				foreach ($data as $key => $value) {
					echo gettype($value), "\n";
//echo $key.'  =  '.$value.'  Type :  '.gettype($value).'<BR>';
			//$result[$key] = sanitize_my_data($value);
			//$result[$key] = test_input($value);
			//echo  $data[$key].'<BR>';
			$res = trim($key);
			echo ' <BR>'.$res.' <BR>avant  filter_input<BR>';
				$res = filter_input(INPUT_POST, $res, FILTER_SANITIZE_STRING);
				echo ' <BR>'.$res.' <BR>avant  filter_input FILTER_SANITIZE_SPECIAL_CHARS<BR>';
				//$res = filter_input(INPUT_POST, $res, FILTER_SANITIZE_SPECIAL_CHARS);
//echo ' <BR>'.$res.' <BR>avant  filter_input FILTER_SANITIZE_ENCODED<BR>';
				//$res = filter_input(INPUT_POST, $res, FILTER_SANITIZE_ENCODED);
				$result[$key] = $res;
				//$type = INPUT_POST;
				echo $res.'apres   filter_input<BR>';
		}

				
				//echo $res.'apres   filter_input<BR>';
				return $result;

			}elseif($_SERVER['REQUEST_METHOD'] == 'GET' ){//ECHO 'OUI';
				$res = trim($data);
				$res = filter_input(INPUT_GET, $data, FILTER_SANITIZE_SPECIAL_CHARS);
				$res = filter_input(INPUT_GET, $data, FILTER_SANITIZE_ENCODED);
				//$type = INPUT_GET;
				//echo '<BR>'.$res.'<BR>';
				return $res;
			}	
			//$result = trim($data);
			
			
	}
	return $result;
	
}

*/
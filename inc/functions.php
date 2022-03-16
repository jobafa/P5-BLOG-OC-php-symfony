<?php
//**************************************************************************//
//*Cheks if registred token is set and returns it*/
/*if not generates the token and returns it*/
//*@param string $name
 /* @return  string**/

function get_token($nom = '')
{
	//session_start();
	 if (isset($_SESSION[$nom.'_token'])) {
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
{
//session_start();
		$expiredtime = (time() - $temps);
		$_SESSION['expired_time'] = $expiredtime;

		if(isset($_SESSION[$nom.'_token']) && isset($_SESSION[$nom.'_token_time']) && isset($_POST['token'])){
			if($_SESSION[$nom.'_token'] == $_POST['token']){
				if($_SESSION[$nom.'_token_time'] >= $expiredtime){
					
					
						if($_SERVER['HTTP_REFERER'] == $referer){
							$error = "ras";
							return $error ;
						}else{
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
    //$token = create_csrf_token();
	$token = get_token($nom);
	return '<input type="hidden" name="token" value="' . $token . '">';
    //return '<input type="hidden" name='. $nom.'_token" value="' . $token . '">';
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

/**
 * Return a mime type of file or false if an error occurred
 *
 * @param string $filename
 * @return string | bool
 */
function get_mime_type(string $filename)
{
    $info = finfo_open(FILEINFO_MIME_TYPE);
    if (!$info) {
        return false;
    }

    $mime_type = finfo_file($info, $filename);
    finfo_close($info);

    return $mime_type;
}

/**
 * Return a human-readable file size
 *
 * @param int $bytes
 * @param int $decimals
 * @return string
 */
function format_filesize(int $bytes, int $decimals = 2): string
{
    $units = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $units[(int)$factor];
}


/**
 * Redirect user with a session based flash message
 * @param string $message
 * @param string $type
 * @param string $name
 * @param string $location
 * @return void
 */
function redirect_with_message(string $message, string $type=FLASH_ERROR, string $name='upload', string $location='index.php'): void
{
    flash($name, $message, $type);
    header("Location: $location", true, 303);
    exit;
}
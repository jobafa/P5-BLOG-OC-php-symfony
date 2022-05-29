<?php
namespace Models;


require_once('Models/Model.php');
use Inc\SessionManager;

class UserManager extends Model {
		
		/*private $usertype_id;
		private $pseudo;
		private $email;
		private $token;
		private $password;
		private $userId;
		private $id;
		private $is_enabled ;*/


		/**
	 * Register new user 
	 * @param  post sign in  form  data
	 * @return object User
	 */

		public function registerUser($usertype_id, $pseudo, $email, $user_password, $photo, $token) {
		$is_enabled = 1;
		

		$userpassword = $user_password;

        $hash = password_hash($userpassword,PASSWORD_DEFAULT);
        
        $userpassword = $hash;
try
				{
        $sql = 'INSERT INTO user(usertype_id, pseudo, email, password, photo, is_enabled, is_activated, creation_date, update_date) VALUES (:usertype_id, :pseudo, :email, :userpassword, :photo,  :is_enabled, :token, NOW(), NOW())';

        $req = $this->db->prepare($sql);

        $resultat = $req->execute(array(
			'usertype_id' => $usertype_id,
            'pseudo' => $pseudo,
            'email' => $email,
            'userpassword' => $userpassword,
			'photo' => $photo,
            'is_enabled' => $is_enabled,
			'token'=> $token
        )); 
		}
				catch (Exception $e)
				{
				echo 'Connexion échouée : ' . $e->getMessage();
				}			
		
		
		SessionManager::getInstance($_SESSION)->set('LASTUSERID', $this->db->lastInsertId());
		
		return $resultat;
    }

    public function loginUser($post_email, $post_password) {

        $userEntry = $this->VerifyUserEmail($post_email);

        if (!$userEntry){ 
			return false;

		}else{ // if user login exists check if user account is not activated then return its value

			
					if($userEntry['is_activated'] != NULL){
						
						$is_activated = 'not_activated';
						return $is_activated;
						
					}
		}
		
        //Get password and hash

        //$password = $post_password;
        $hash = $userEntry['password'];

        //Verify password

        if (password_verify($post_password, $hash)){
			
			return $userEntry;
		}else{
		
        //Otherwise return false
        return false;
    }
	}

	/**
	 * Get  user's Activation token with userId 
	 * @param  int $userId 
	 * @return object User
	 */

	
	public function getUseractivationcode($userid)
	{
		
	try
	{
		 $req = $this->db->prepare('SELECT id, is_activated FROM user WHERE  id = :userid');
         
		 $resultat =$req->execute(array(":userid" => $userid)); 
	}
	catch (Exception $e)
	{
		echo 'Connexion échouée : ' . $e->getMessage();
	}	
		 $resultat = $req->fetch();
		
		return $resultat;

	}

	/**
	 * Activate user account = Delete activation code from user's row
	 * @param  string $id 
	 */

	public function userActivate($userid, $token = NULL)
	{
		
	 try
	{
		
		
			
			$sql = 'UPDATE user SET is_activated = :token WHERE user.id = :userid';

		

		$req = $this->db->prepare($sql);

        $resultat = $req->execute(array('userid' => $userid,
										'token' => $token
										)); 
	}
	catch (Exception $e)
	{
		echo 'Connexion échouée : ' . $e->getMessage();
	}	
	
	return $resultat;
	}

	
	/**
	 * Check if  new user email exists in database
	 * @param  string $email
	 * @return request's result]
	 */

    public function VerifyUserEmail($email) {


		
    try
	{
		$sql = 'SELECT * FROM user WHERE email = :email ';
        

        $obj = $this->db->prepare($sql);

        $obj->execute(array('email' => $email));
	}
				catch (Exception $e)
				{
				echo 'Connexion �chou�e : ' . $e->getMessage();
				}	
        $result = $obj->fetch();
		
        return $result;
    }

	/**
	 * Check if  new user reset password token exists in database and if not expired
	 * @param  string $link_email $link_token
	 * @return result]
	 */

    public function VerifyEmailToken($link_email, $link_token) {


		
    try
	{
		$sql = 'SELECT pass_resetcode, pass_resetdate FROM user WHERE email = :link_email ';
        

        $obj = $this->db->prepare($sql);

        $obj->execute(array('link_email' => $link_email));
	
        $result = $obj->fetch();

		if($result){

			if($result['pass_resetcode'] == $link_token){
				$curDate = date("Y-m-d H:i:s");
				if( $result['pass_resetdate'] >= $curDate ){
					$result = true;
				}else{
					$result = false;
				}
			}else{

				$result = "tokenissue";
			}
		}else{

			$result = "emailissue";
		}
		

        return $result;
		}
		catch (Exception $e)
		{
			echo 'Connexion �chou�e : ' . $e->getMessage();
		}	
    }

	 /**
	 * Insert into user reset password token and its expiring time
	 * @param  string $email
	 * @return request's result]
	 */

	 public function resetPassTokenInsert($userid, $token)
		{

			
			try
			{
					$sql = 'UPDATE user SET pass_resetcode = ?, pass_resetdate = ? WHERE id = ? ';


					$expFormat = mktime(
					 date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
					 );
				 
					$expDate = date("Y-m-d H:i:s",$expFormat);
 
					$req = $this->db->prepare($sql);
					$resultat = $req->execute(array($token,$expDate,$userid));
	
					return $resultat;
			}
			catch (Exception $e)
			{
			echo 'Connexion échouée : ' . $e->getMessage();
			}			
			
			
		}


		/**
	 * UPDATE PASSWORD IN USER'S RECORD
	 * @param  ParameterS  NEWPASS, EMAIL AND TOKEN
	 * 
	 */
	public function updatePass($newpass, $link_email, $link_token)
	{
		

			
		try
		{
			$req = 'UPDATE user SET password = :newpass where email = :link_email AND pass_resetcode = :link_token ';
		
			$res = $this->db->prepare($req);
			$result = $res->execute(array(
															'newpass' => $newpass,
															'link_email' => $link_email,
															'link_token' => $link_token
														)); 
			
		}
		catch (Exception $e)
		{
			echo 'Connexion �chou�e : ' . $e->getMessage();
		}	
		
		
		return $result;
		
	}

	/*GET USER'S RECORD FOR EDITING PROFILE
	@PARAM USER ID*/

	public function getUser($userid = null)
	{

		
	try
	{
		$sql = ('
			SELECT 
				user.id, 
				user.usertype_id,
				user_type.usertype,
				user.lastname, 
				user.firstname, 
				user.phone_number, 
				user.pseudo , 
				user.email, 
				user.photo, 
				user.is_enabled, 
				user.is_activated, 
				DATE_FORMAT(user.creation_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS creation_date_fr 
			FROM user 
			JOIN user_type 
			ON user.usertype_id = user_type.id  '
			);
		if($userid != null){
			$sql.= 'WHERE user.id = :id ';
			$req = $this->db->prepare($sql);
			$req->execute(array(':id' => $userid));
			$result = $req->fetch();
		}else{
			$sql.= ' ORDER BY creation_date DESC ';
			$req = $this->db->query($sql);
			return $req;
			
		}
	}
	catch (Exception $e)
	{
	echo 'Connexion échouée : ' . $e->getMessage();
	}	
		
		return $result;
	}


	/**
	 * Update user infos in database
	 * @param  Parameter $post [pseudo, first_name, last_name, birth_date, home, mobile, website...]
	 * @return void
	 */

	public function userUpdateProfile($userid, $post, $photo)
	{
			
		try
		{
			$req = 'UPDATE user SET  ';

			foreach ($post as $key => $value) {

				if ($value != '') {

					$req .= ' ' . $key . '="' . $value . '", ';
				}
			}
			
			$req .= 'photo = "'.$photo. '"';
			$req .= ' WHERE id = ?';
			$res = $this->db->prepare($req);
			
			$result = $res->execute(array( $userid));
		}
		catch (Exception $e)
		{
			echo 'Connexion échouée : ' . $e->getMessage();
		}	
	
		return $result;
		
	}

	/****DELTE USER***

	@PARAM USERID****/

	public function deleteUser($userid) {

            
			
		try
		{
			$query = 'DELETE FROM user WHERE user.id = ? ';
			$deleteuser = $this->db->prepare($query);
			$resultat = $deleteuser->execute(array($userid));
			}
				catch (Exception $e)
				{
				echo 'Connexion �chou�e : ' . $e->getMessage();
				}	
			
            return $resultat;
        }
   
}// END CLASS

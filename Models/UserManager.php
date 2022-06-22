<?php
namespace Models;


require_once'Models/Model.php';
use Inc\SessionManager;

class UserManager extends Model {
				
	/**
	 * Register new user 
	 * @param  post sign in  form  data
	 * @return object User
	 */

		public function registerUser($userTypeId, $pseudo, $email, $userPassword, $photo, $token) {
		$is_enabled = 1;
		
        $hash = password_hash($userPassword,PASSWORD_DEFAULT);
        
        $userPassword = $hash;
		try{
			$sql = 'INSERT INTO user(usertype_id, pseudo, email, password, photo, is_enabled, is_activated, creation_date, update_date) VALUES (:userTypeId, :pseudo, :email, :userPassword, :photo,  :is_enabled, :token, NOW(), NOW())';

			$req = $this->db->prepare($sql);

			$resultat = $req->execute(array(
				'userTypeId' => $userTypeId,
				'pseudo' => $pseudo,
				'email' => $email,
				'userPassword' => $userPassword,
				'photo' => $photo,
				'is_enabled' => $is_enabled,
				'token'=> $token
			)); 
		}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}			
				
		SessionManager::getInstance()->set('LASTUSERID', $this->db->lastInsertId());
		
		return $resultat;
    }

    public function loginUser($postEmail, $postPassword) {

        $userEntry = $this->VerifyUserEmail($postEmail);

        if (!$userEntry){ 
			return false;

		}else{ // if user login exists check if user account is not activated then return its value
			
			if($userEntry['is_activated'] != NULL){
				
				$isActivated = 'not_activated';
				return $isActivated;
				
			}
		}
		
        //Get password and hash

        $hash = $userEntry['password'];

        //Verify password

        if (password_verify($postPassword, $hash)){
			
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

	
	public function getUseractivationcode($userId)
	{
		
	try{
		 $req = $this->db->prepare('SELECT id, is_activated FROM user WHERE  id = :userId');
         
		 $resultat =$req->execute(array(":userId" => $userId)); 
	}
	catch (Exception $e){
		
		$errorMessage = $e->getMessage();
    	\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
		
	}	
		$resultat = $req->fetch();
		
		return $resultat;

	}

	/**
	 * Activate user account = Delete activation code from user's row
	 * @param  string $id 
	 */

	public function userActivate($userId, $token = NULL)
	{
		
	 try{
			
			$sql = 'UPDATE user SET is_activated = :token WHERE user.id = :userId';
			$req = $this->db->prepare($sql);

			$resultat = $req->execute(array('userId' => $userId,
											'token' => $token
											)); 
	}
	catch (Exception $e){
		
		$errorMessage = $e->getMessage();
    	\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
		
	}	
	
	return $resultat;
	}

	
	/**
	 * Check if  new user email exists in database
	 * @param  string $email
	 * @return request's result]
	 */

    public function VerifyUserEmail($email) {

    try{
		$sql = 'SELECT * FROM user WHERE email = :email ';
        $obj = $this->db->prepare($sql);
        $obj->execute(array('email' => $email));
	}
	catch (Exception $e){
		
		$errorMessage = $e->getMessage();
		\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
		
	}	
        $result = $obj->fetch();
		
        return $result;
    }

	/**
	 * Check if  new user reset password token exists in database and if not expired
	 * @param  string $linkEmail $linkToken
	 * @return result]
	 */

    public function VerifyEmailToken($linkEmail, $linkToken) {
    
		try{

			$sql = 'SELECT pass_resetcode, pass_resetdate FROM user WHERE email = :linkEmail ';
			

			$obj = $this->db->prepare($sql);

			$obj->execute(array('linkEmail' => $linkEmail));
		
			$result = $obj->fetch();

			if($result){

				if($result['pass_resetcode'] == $linkToken){
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
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}	
    }

	 /**
	 * Insert into user reset password token and its expiring time
	 * @param  string $email
	 * @return request's result]
	 */

	 public function resetPassTokenInsert($userId, $token)
		{			
			try{
				$sql = 'UPDATE user SET pass_resetcode = ?, pass_resetdate = ? WHERE id = ? ';

				$expFormat = mktime(
					date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
					);
				
				$expDate = date("Y-m-d H:i:s",$expFormat);

				$req = $this->db->prepare($sql);
				$resultat = $req->execute(array($token,$expDate,$userId));

				return $resultat;
			}
			catch (Exception $e){
				
				$errorMessage = $e->getMessage();
				\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
				
			}			
			
			
		}


	/**
	 * UPDATE PASSWORD IN USER'S RECORD
	 * @param  ParameterS  newPass, EMAIL AND TOKEN
	 * 
	 */
	public function updatePass($newPass, $linkEmail, $linkToken)
	{
		try{
			$req = 'UPDATE user SET password = :newPass, pass_resetcode = NULL where email = :linkEmail AND pass_resetcode = :linkToken ';
		
			$res = $this->db->prepare($req);
			$result = $res->execute(array(
											'newPass' => $newPass,
											'linkEmail' => $linkEmail,
											'linkToken' => $linkToken
										)); 
			
		}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}	
		
		
		return $result;
		
	}

	/*GET USER'S RECORD FOR EDITING PROFILE
	@PARAM USER ID*/

	public function getUser($userId = null)
	{
		try{

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
			if($userId != null){
				$sql.= 'WHERE user.id = :id ';
				$req = $this->db->prepare($sql);
				$req->execute(array(':id' => $userId));
				$result = $req->fetch();
			}else{
				$sql.= ' ORDER BY creation_date DESC ';
				$req = $this->db->query($sql);
				return $req;
				
			}
		}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}	
			
			return $result;
	}


	/**
	 * Update user infos in database
	 * @param  Parameter $post [pseudo, first_name, last_name, birth_date, home, mobile, website...]
	 * @return void
	 */

	public function userUpdateProfile($userId, $post, $photo)
	{
		try{
			$req = 'UPDATE user SET  ';

			foreach ($post as $key => $value) {

				if ($value != '') {

					$req .= ' ' . $key . '="' . $value . '", ';
				}
			}
			
			$req .= 'photo = "'.$photo. '"';
			$req .= ' WHERE id = ?';
			$res = $this->db->prepare($req);
			
			$result = $res->execute(array( $userId));
		}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}	
	
		return $result;
		
	}

	/****DELTE USER***

	@PARAM USERID****/

	public function deleteUser($userId) {
		
		try{
			$query = 'DELETE FROM user WHERE user.id = ? ';
			$deleteUser = $this->db->prepare($query);
			$resultat = $deleteUser->execute(array($userId));
		}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}	
	
		return $resultat;
    }
   
}// END CLASS

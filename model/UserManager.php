<?php
namespace OC\PhpSymfony\Blog\Model;


require_once("model/Manager.php");

class UserManager extends Manager {
		
		private $usertype_id;
		private $pseudo;
		private $email;
		private $password;

		public function registerUser($usertype_id, $pseudo, $email, $user_password) {

		$db = $this->dbConnect();

		/*// TESTER SI L'EMAIL EXISTE DANS LA BDD SI OUI EXIT AVEC MESSAGE
		
		$verifyemail = $this->VerifyUserEmail($email);
		if ($verifyemail) return false;
		*/

        $userpassword = $user_password;

        $hash = password_hash($userpassword,PASSWORD_DEFAULT);
        
        $userpassword = $hash;

        $sql = 'INSERT INTO user(usertype_id, pseudo, email, password,  is_enabled, creation_date, update_date) VALUES (:usertype_id, :pseudo, :email, :password, :is_enabled, NOW(), NOW())';

        $obj = $db->prepare($sql);

        $resultat = $obj->execute(array(
			'usertype_id' => $usertype_id,
            'pseudo' => $pseudo,
            'email' => $email,
            'password' => $userpassword,
            'is_enabled' => 1,
        )); 
		return $resultat;
    }

    public function loginUser($post_email, $post_password) {
//echo ' ENTRE LOGINUSER CLASS ';
			//EXIT;

		$db = $this->dbConnect();

        //Get userEntry from DB
        $userEntry = $this->VerifyUserEmail($post_email);

        //Check if user exists (and early return if not)

        if (!$userEntry){
				return false;
		}
//echo ' EMAIL EXIST CLASS ';
			//EXIT;
	   /*if (!$userEntry) {
echo ' le mail failed CLASS ';
exit;
}*/

        //Get password and hash
        $password = $post_password;
        $hash = $userEntry['password'];

        //Remove hashed password from $userEntry
        //unset($userEntry['password']);
        //unset($hash);

        //Add users fullname to $userEntry
        //$userEntry['fullname'] = $userEntry['firstname'] . ' ' . $userEntry['lastname'];

        //Verify password
        if (password_verify($password, $hash)) return $userEntry;
		//ECHO $userEntry['usertype_id'].$userEntry['pseudo'];

        //Otherwise return false
        return false;
    }

    public function VerifyUserEmail($email) {

		$db = $this->dbConnect();
        
		$sql = 'SELECT * FROM user WHERE email = :email LIMIT 1';
        

        $obj = $db->prepare($sql);

        $obj->execute(array(
            'email' => $email
        ));

        $result = $obj->fetch();
		//$result = $obj->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

   
}

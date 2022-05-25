<?php


require_once __DIR__.'/autoload.php';

require_once __DIR__.'/Inc/functions.php';

require_once __DIR__.'/config/config.php';


/**/
if( ! isset($session) ) {
	session_start();
	$session = new \Inc\SessionManager(); // create session instance
}



if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'GET')){
	
	
	$requestmethod = 'GET';
	
	// SANITIZE AND VALIDATE DATA

	$cleandata = new \Inc\Clean();
	$data_inputs = $cleandata->sanitize_get_data( $_GET);

	

	$_GET = $data_inputs;
}elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST')){

	
	$post = new \Inc\Method($_POST);
	
	
	$requestmethod = 'POST';

}

$controller = \Application::process();
$get = new \Inc\Method($_GET);



if($get->get('action')){
	
	$action = strtolower($get->get('action'));
	$session->set('ACTION', $action);
	
}


if ( ( $get->get('id') ) && ( $get->get('id') > 0 )){

	$id = $get->get('id');
	
	
}


if($get->get('email')){
	
	
	$link_email = $get->get('email');
	
		
}


if($get->get('token')){
	
	
	$link_token = $get->get('token');
	
		
}


if ( ( $get->get('usertypeid') ) && ( $get->get('usertypeid') > 0 )){
	
	
	$usertypeid = $get->get('usertypeid');
			
}


// flag to check , after connection, where the user comes from and redirect him : post view or dashboard


if($get->get('from')){
	$from = $session->set('FROM', $get->get('from'));
	
			
}


if ( ( $get->get('idcomment') ) && ( $get->get('idcomment') > 0 )){
	
	
	$id = $get->get('usertypeid');
	
}


if ( ( $get->get('idpost') ) && ( $get->get('idpost') > 0 )){
	
	
	$id = $get->get('idpost');

}


if ( ( $get->get('page') ) && ( $get->get('page') > 0 )){

	$getpage = $get->get('page');
	

}else{

	$getpage =  1;
}

if ($session->get('PSEUDO')){
	
	$pseudo =  $session->get('PSEUDO');
}

if ($session->get('USERID')){
	
	$userid = $session->get('USERID');
	
}


try {
    if ( isset($action) )
		{

				if ($action == 'listposts') {
					
					$controller->listposts($from, $getpage);
				}
				elseif (($action == 'frontpost') || ($action == 'post')) {
					if (isset($id) && $id > 0) {
						
						$controller->post($id, $getpage);
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				
				// CONTACT FORM
				elseif ( ($action == 'contactform')) {

					$controller->checkContactdata($post, $URL, 'blogcontact');
				}
				elseif ($action == 'addcomment') {
					
					if (isset($id) && $id > 0) {

						if (!empty($pseudo) && !empty($post->get('comment'))) {
							
							$controller->addComment($id, $pseudo, $post->get('comment'), $userid );

							
						}
						else {
							throw new Exception('Tous les champs ne sont pas remplis !');
						}
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				elseif ($action == 'loginview') {

					
					$controller->loginView();

						
				}
				elseif ($action == 'verifylogin') {
					
					$controller->verifyLogin($post->all(), $URL, 'login');
						
				}
				elseif ($action == 'userlogout') {
					
					$controller = new \Controllers\User();
					$controller->userLogout();
						
				}

				elseif ($action == 'passresetrequest') {
					
					$controller->passresetRequest();
				}

				elseif ($action == 'passreset') {
					
					$controller->passReset($post, $URL, 'passreset');
					
				}

				elseif ($action == 'passreinitialisation') {

					if(isset($link_token) && isset($link_email)){
						
						$controller->verifyPassresetToken($link_email,$link_token);
						
					}
					
						
				}
				
				elseif ($action == 'passreinitview') {
					
					
					$controller->passreinitView();
				}

				elseif ($action == 'passreinitnew') {
					
					
					$controller->passreinitNew();
				}

				elseif ($action == 'newpass') {

					if(isset($post)){
						
					$controller->getNewPass($post, $URL, 'newpass');
						
					}
					
						
				}
				
				
				/*****************************************************/ 
				/*********** Common Routing **************/
				/*****************************************************/

				elseif (($action == 'adduserview') || ($action == 'signinview')) {
					
					
					$controller->adduserview($action);
				}
				elseif (($action == 'useradd') || ($action == 'usersignin')) {
					
					
					$controller->adduser($post, $URL, 'newuser');
				}

				elseif ($action == 'useractivation') {
						
						
					if (isset($_GET['token'])){ // MEANS WE ARE COMING FROM USER'S ACTIVATION LINK
						$token = $_GET['token'];
						$isactivated = NULL;
					}elseif(isset($_GET['isactivated'])){ // MEANS WE ARE COMING FROM ADMIN DASHBOARD
						$isactivated = $_GET['isactivated'];		
						$token  = NULL;
					}
					
					
					$controller->userActivation($id, $link_email, $token, $isactivated);
					
			}
		


				/*****************************************************/ 
						/*********** Backend Routing **************/
						/*****************************************************/
				

				// GET USER'S COMMENTS BY ID
				elseif ($action == 'mycomments') {
						
						listCommentsValidate();
				}
				elseif ($action == 'backblogmanage') {
					
					
						verifytype();
				}
				elseif ($action == 'adminposts') {
					
					listPostsUpdate();
				}
				elseif ($action == 'adminmyposts') {
						if(! isset($id)){
						$userid = $_SESSION['USERID'];
					}

					listPostsUpdate($userid);
				}
				elseif ($action == 'addpostview') {
					addPostView();
				}
				elseif ($action == 'postsupdate') {
					listPostsUpdate();
				}
				elseif ($action == 'addpost') {
					 if (isset($_POST) && !empty($_POST)) {

							addPost();
							
					 }
					 else {
						throw new Exception('probleme envoi formulaire');
					 }
				}

				elseif ($action == 'modifypost') {

					if (isset($id) && $id > 0) {
						modifyPostView($id);
					}
					else {
						throw new Exception('Aucun identifiant de post envoyé');
					}
				}
				elseif ($action == 'updatepost') {
					 if (isset($id) && $id > 0) {

							updatePost($id);
					 }else {
						throw new Exception('Aucun identifiant de billet envoyé');
					 }

				}
				elseif ($action == 'postactivation') {
						$ispublished = $_GET['ispublished'];
						if($ispublished == 'on'){
							$ispublished = '0';
						}else{
							$ispublished = '1';
						}
						postpublish($id, $ispublished);
				}
				elseif ($action == 'postactivation') {
					
						deletepost($id);
				}
				elseif ($action == 'commentdelete') {

						
						deleteComment($id);
				}
				

				elseif ($action == 'usersadmin') {
					
						usersAdmin();
				}

				elseif ($action == 'myprofile') {
					
					if(! isset($id)){
						$userid = $_SESSION['USERID'];
					}else{
						$userid = $id;
					}
						userProfile($userid);
				}

				elseif ($action == 'userupdate') {
				
						$post = $_POST;
						userUpdate($post,$id);
				}

				elseif ($action == 'userdelete') {
					
						userdelete($id, $usertypeid);
				}

				elseif ($action == 'commentsadmin') {
					listCommentsValidate();
				}

				elseif ($action == 'commentvalidate') {
					 if (isset($id) && $id > 0) {
					     CommentValidate($id);
					 }
				}
	
	}else {
					
					header('Location: accueil.html');
			}
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}


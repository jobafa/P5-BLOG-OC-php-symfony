<?php

//if( ! isset($_SESSION) ) session_start();


require_once __DIR__.'/autoload.php';
//require_once('inc/SessionManager.php');
require_once __DIR__.'/Inc/functions.php';
//require_once __DIR__.'/inc/sanitization.php';
//require_once __DIR__.'/inc/validation.php';
require_once __DIR__.'/config/config.php';
//require_once __DIR__.'/controllers/backend.php';
//require_once __DIR__.'/controllers/frontend.php';
//require_once __DIR__.'/controllers/Post.php';
//require_once __DIR__.'/controllers/Comment.php';
//use Inc\SessionManager;

//\Application::process();

if( ! isset($_SESSION) ) {
	session_start();
	$session = new \Inc\SessionManager($_SESSION); // create session instance
}

/*if( ! isset($request) ) {
	
	$request = new \Inc\Request(); // create request instance
}*/

//$session = new \Inc\SessionManager($_SESSION); // create session instance
//$id =  $_GET['id'];
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'GET')){
	
	//var_dump($_GET);
	 //EXIT;
	//$data_inputs = $_GET;
	$requestmethod = 'GET';
	
	// SANITIZE AND VALIDATE DATA

	$cleandata = new \Inc\Clean();
	$data_inputs = $cleandata->sanitize_get_data( $_GET);

	//$get = new \Inc\Method($data_inputs);

	$_GET = $data_inputs;
}elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST')){

	//$post = $_POST;
	$post = new \Inc\Method($_POST);
	//$controller = \Application::process();
	
	$requestmethod = 'POST';

}

$controller = \Application::process();
$get = new \Inc\Method($_GET);


/*if($get->get('controller')){
	//var_dump($get->get('action'));
	//exit;
	//$action = strtolower($_GET['action']);
	$controller = \Application::process();
}*/
//if(isset($_GET['action'])){
if($get->get('action')){
	//var_dump($get->get('action'));
	//exit;
	//$action = strtolower($_GET['action']);
	$action = strtolower($get->get('action'));
	$session->set('ACTION', $action);
	//$action  = $session->get('ACTION');
}

//if (isset($_GET['id']) && ($_GET['id'] > 0)){
if ( ( $get->get('id') ) && ( $get->get('id') > 0 )){

	$id = $get->get('id');
	
	//$id =  $_GET['id'];
}

//if (isset($_GET['email'])){
if($get->get('email')){
	
	//$link_email =  $_GET['email'];
	$link_email = $get->get('email');

	
		
}


//if (isset($_GET['token'])){
if($get->get('token')){
	
	//$link_token =  $_GET['token'];
	$link_token = $get->get('token');

	
		
}


//if (isset($_GET['usertypeid']) && ($_GET['usertypeid'] > 0)){
if ( ( $get->get('usertypeid') ) && ( $get->get('usertypeid') > 0 )){
	
	//$usertypeid =  $_GET['usertypeid'];
	$usertypeid = $get->get('usertypeid');
			
}


// flag to check , after connection, where the user comes from and redirect him : post view or dashboard

//if (isset($_GET['from'])){
if($get->get('from')){
	$from = $session->set('FROM', $get->get('from'));
	//$_SESSION['FROM'] =  $_GET['from'];
	//$from = $_SESSION['FROM'];
			
}

//if (isset($_GET['idcomment']) && ($_GET['idcomment'] > 0)){
if ( ( $get->get('idcomment') ) && ( $get->get('idcomment') > 0 )){
	
	//$id =  $_GET['idcomment'];
	$id = $get->get('usertypeid');
	
}

//if (isset($_GET['idpost']) && ($_GET['idpost'] > 0)){
if ( ( $get->get('idpost') ) && ( $get->get('idpost') > 0 )){
	
	//$id =  $_GET['idpost'];
	$id = $get->get('idpost');

}

//if (isset($_GET['page']) && ($_GET['page'] > 0)){
if ( ( $get->get('page') ) && ( $get->get('page') > 0 )){

	$getpage = $get->get('page');
	//$getpage =  $_GET['page'];

}else{

	$getpage =  1;
}

if ($session->get('PSEUDO')){
	//$session->set('ACTION', $action);
	//$action  = $session->get('PSEUDO');
	$pseudo =  $session->get('PSEUDO');
}

if ($session->get('USERID')){
	//$session->set('ACTION', $action);
	//$action  = $session->get('PSEUDO');
	$userid = $session->get('USERID');
	
}



try {
    if ( isset($action) )
		{

				if ($action == 'listposts') {

					//$controller = new \Controllers\Post();
					//\Application::process();
					$controller->listposts($from, $getpage);
				}
				elseif (($action == 'frontpost') || ($action == 'post')) {
					if (isset($id) && $id > 0) {
						//$controller = new \Controllers\Post();
						//$is_published = '1';
						//\Application::process();
						$controller->post($id, $getpage);

					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}

				/*elseif ( ($action == 'post')) {

					if (isset($id) && $id > 0) {
						$is_published = null;
						post($id, $is_published);
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}

				}*/
				// CONTACT FORM
				elseif ( ($action == 'contactform')) {

					$controller->checkContactdata($post, $URL, 'blogcontact');

				}
				elseif ($action == 'addcomment') {
					
					if (isset($id) && $id > 0) {

						if (!empty($pseudo) && !empty($_POST['comment'])) {

							//$userid = $_SESSION['USERID'] ;
							//$usrid = $session->get('USERID');
							
							//$controller = new \Controllers\Comment();

							//\Application::process();
							//exit;
							$controller->addComment($id, $pseudo, $_POST['comment'],$userid );

							

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

					//$controller = new \Controllers\User();
					//\Application::process();
					$controller->loginView();

						
				}
				elseif ($action == 'verifylogin') {
					//echo ' ENTRE INDEX';
					//$controller = new \Controllers\User();
					//$controller = \Application::process();
					//var_dump($controller);
					//exit;
					$controller->verifyLogin($post->all(), $URL, 'login');
						
				}
				elseif ($action == 'userlogout') {
					//echo ' ENTRE INDEX';
					$controller = new \Controllers\User();
					$controller->userLogout();
						
				}

				elseif ($action == 'passresetrequest') {
					//echo ' ENTRE INDEX';
					//$controller = new \Controllers\User();
					$controller->passresetRequest();
				}

				elseif ($action == 'passreset') {
					//$controller = new \Controllers\User();
					$controller->passReset($post, $URL, 'passreset');
					
				}

				elseif ($action == 'passreinitialisation') {

					if(isset($link_token) && isset($link_email)){
						//$controller = new \Controllers\User();
						$controller->verifyPassresetToken($link_email,$link_token);
						
					}
					
						
				}
				
				elseif ($action == 'passreinitview') {
					
					//$controller = new \Controllers\User();
					$controller->passreinitView();
				}

				elseif ($action == 'passreinitnew') {
					
					//$controller = new \Controllers\User();
					$controller->passreinitNew();
				}

				elseif ($action == 'newpass') {

					if(isset($post)){
						//$controller = new \Controllers\User();
					$controller->getNewPass($post, $URL, 'newpass');
						
					}
					
						
				}
				/*elseif ($action == 'modifycomment') {

					 if (isset($id) && $id > 0) {
						
						  modifyComment($id);
						 						  
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}

				elseif ($action == 'updatecomment') {
					 if (isset($id) && $id > 0) {

							$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
						
						   
						
							$affectedLines = $commentManager->updateComment($_POST['comment'], $id  );
							 if ($affectedLines === false) {
								throw new Exception('Impossible de mettre à jour le commentaire !');
							}
							else{
								//header('Location: index.php?action=post&id=' . $id .'&post_id='.$_GET['post_id'] );
								header('Location: index.php?action=post&id=' . $_GET['post_id'] );
							}
					 }
					  else {
						throw new Exception('Aucun identifiant de billet envoyé');
					 }

				}*/
				
				/*****************************************************/ 
						/*********** Common Routing **************/
						/*****************************************************/

				elseif (($action == 'adduserview') || ($action == 'signinview')) {
					
					//\Application::process();
					//$controller = new \Controllers\User();
					$controller->adduserview($action);

				}
				elseif (($action == 'useradd') || ($action == 'usersignin')) {
					
					//$controller = new \Controllers\User();	
					//\Application::process();
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
					
					//\Application::process();
					//$controller = new \Controllers\User();
					$controller->userActivation($id, $link_email, $token, $isactivated);
					
			}
		


				elseif ($action == 'useractivation') {
						
						
					if (isset($_GET['token'])){ // MEANS WE ARE COMING FROM USER'S ACTIVATION LINK
						$token = $_GET['token'];
						$isactivated = NULL;
					}elseif(isset($_GET['isactivated'])){ // MEANS WE ARE COMING FROM ADMIN DASHBOARD
						$isactivated = $_GET['isactivated'];		
						$token  = NULL;
					}
					
					//\Application::process();
					//$controller = new \Controllers\User();
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
					//echo ' ENTRE INDEX';
					
						verifytype();
				}
				elseif ($action == 'adminposts') {
					//verifytype();
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

						//$commentid = $id;
						deleteComment($id);
				}

				/*elseif (($action == 'adduserview') || ($action == 'signinview')) {
					
					//\Application::process();
					//$controller = new \Controllers\User();
					$controller->adduserview($action);
				}
				elseif (($action == 'useradd') || ($action == 'usersignin')) {
					
					//$controller = new \Controllers\User();	
					//\Application::process();
					$controller->adduser($post, $URL, 'newuser');
				}*/
				


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


<?php

require_once __DIR__.'/autoload.php';
require_once __DIR__.'/config/config.php';

use Inc\SessionManager;
//use EXCEPTION;

$controller = \Application::process();
$request =  new \Inc\Request;

if( null !== $request->getSession()) {
	session_start();
}

if (null !== $request->getGet()->all()){
	
	// SANITIZE AND VALIDATE DATA

	$cleanData = new \Inc\Clean();
	$dataInputs = $cleanData->sanitize_get_data( $request->getget()->all());
	
}elseif (null !== $request->getPost()->all()){

}


if($request->getGet()->get('action')){
	
	$action = strtolower($request->getGet()->get('action'));
	sessionmanager::getinstance()->set('ACTION', $action);

}


if ( ( $request->getGet()->get('id') ) && ( $request->getGet()->get('id') > 0 )){

	$id = $request->getGet()->get('id');

}


if($request->getGet()->get('email')){
	
	$linkEmail = $request->getGet()->get('email');
}

if($request->getGet()->get('token')){
	
	$linkToken = $request->getGet()->get('token');
}

if ( ( $request->getGet()->get('usertypeid') ) && ( $request->getGet()->get('usertypeid') > 0 )){
	
	$userTypeId = $request->getGet()->get('usertypeid');
			
}


// flag to check , after connection, where the user comes from and redirect him : post view or dashboard

if($request->getGet()->get('from')){
	$from = sessionmanager::getinstance()->set('FROM', $request->getGet()->get('from'));
	
}


if ( ( $request->getGet()->get('idcomment') ) && ( $request->getGet()->get('idcomment') > 0 )){

	$id = $request->getGet()->get('idcomment');
	
}

if ( ( $request->getGet()->get('idpost') ) && ( $request->getGet()->get('idpost') > 0 )){

	$id = $request->getGet()->get('idpost');
}

if ( ( $request->getGet()->get('page') ) && ( $request->getGet()->get('page') > 0 )){

	$getPage = $request->getGet()->get('page');
	
}else{

	$getPage =  1;
}

if (sessionmanager::getinstance()->get('PSEUDO')){

	$pseudo =  sessionmanager::getinstance()->get('PSEUDO');
}

if (sessionmanager::getinstance()->get('USERID')){
	
	$userId = sessionmanager::getinstance()->get('USERID');
	
}

try {
    if ( isset($action) )
		{

			if ($action == 'listposts') {

				$controller->listposts($from, $getPage);
			}
			elseif (($action == 'frontpost') || ($action == 'post')) {
				if (isset($id) && $id > 0) {
					
					$controller->post($id, $getPage);
				}
				else {
					throw new Exception('Aucun identifiant de billet envoyé');
				}
			}			
			elseif ( ($action == 'contactform')) {// CONTACT FORM
				//$controller = new \Controllers\User();
				$controller->checkContactData($request->getPost()->all(), $URL, 'blogcontact');
			}
			elseif ($action == 'addcomment') {
				
				if (isset($id) && $id > 0) {
	
						$controller->addComment($id, $pseudo, $request->getPost()->get('comment'),$userId );
				}
				else{
					throw new Exception('Aucun identifiant de billet envoyé');
				}
			}
			elseif ($action == 'loginview') {

				$controller->loginView();		
			}
			elseif ($action == 'verifylogin') {
				
				$controller->verifyLogin($request->getPost()->all(), $URL, 'login');
					
			}
			elseif ($action == 'userlogout') {
				
				$controller = new \Controllers\User();
				$controller->userLogout();		
			}

			elseif ($action == 'passresetrequest') {
				
				$controller->passResetRequest();
			}

			elseif ($action == 'passreset') {

				$controller->passReset($request->getPost()->all(), $URL, 'passreset');
				
			}

			elseif ($action == 'passreinitialisation') {

				if(isset($linkToken) && isset($linkEmail)){

					$controller->verifyPassresetToken($linkEmail,$linkToken);
					
				}
				
					
			}
			
			elseif ($action == 'passreinitview') {

				$controller->passreinitView();
			}

			elseif ($action == 'passreinitnew') {
		
				$controller->passReinitNew();
			}

			elseif ($action == 'newpass') {

				if(null !== $request->getPost()->all()){
		
					$controller->getNewPass($request->getPost()->all(), $URL, 'newpass');
					
				}
				
					
			}
			
			/*****************************************************/ 
					/*********** Common Routing **************/
					/*****************************************************/

			elseif (($action == 'adduserview') || ($action == 'signinview')) {
			
				$controller->addUserView($action);
			}
			elseif (($action == 'useradd') || ($action == 'usersignin')) {
				
				$controller->addUser($request->getPost(), $URL, 'newuser');
			}

			elseif ($action == 'useractivation') {
					
				if (null !== $request->getGet()->get('token')){ // MEANS WE ARE COMING FROM USER'S ACTIVATION LINK
					$token = $request->getGet()->get('token');
					$isActivated = NULL;
				}elseif(null !== $request->getGet()->get('isactivated')){ // MEANS WE ARE COMING FROM ADMIN DASHBOARD
					$isActivated = $request->getGet()->get('isactivated');		
					$token  = NULL;
				}
				
				$controller->userActivation($id, $linkEmail, $token, $isActivated);
					
			}
		


			/*****************************************************/ 
			/*********** Backend Routing **************/
			/*****************************************************/
			

			// GET USER'S COMMENTS BY ID
			elseif ($action == 'mycomments') {
					
				$controller->listCommentsValidate();
			}
			elseif ($action == 'backblogmanage') {
				
				$controller->verifyType();
			}
			elseif ($action == 'adminposts') {

				$controller->listPostsUpdate();
			}
			elseif ($action == 'adminmyposts') {
				if(! isset($id)){

					sessionmanager::getinstance()->set('USERID', $userId);
				}
				$controller->listPostsUpdate($userId);
			}
			elseif ($action == 'addpostview') {
				$controller->addPostView();
			}
			elseif ($action == 'postsupdate') {
				$controller->listPostsUpdate();
			}
			elseif ($action == 'addpost') {
					if ((null !== $request->getPost()->all())) {


						$controller->addPost($request->getPost()->all());
						
					}else{
					throw new Exception('probleme envoi formulaire');
					}
			}

			elseif ($action == 'modifypost') {

				if (isset($id) && $id > 0) {
					$controller->modifyPostView($id);
				}
				else {
					throw new Exception('Aucun identifiant de post envoyé');
				}
			}
			elseif ($action == 'updatepost') {
					if (isset($id) && $id > 0) {

						$controller->updatePost($id, $request->getPost()->all());
					}else {
					throw new Exception('Aucun identifiant de billet envoyé');
					}

			}
			elseif ($action == 'postactivation') {
					$isPublished = $request->getGet()->get('ispublished');
					if($isPublished == 'on'){
						$isPublished = '0';
					}else{
						$isPublished = '1';
					}
					$controller->postPublish($id, $isPublished);
			}
			elseif ($action == 'deletepost') {
				
				$controller->deletePost($id);
			}
			elseif ($action == 'commentdelete') {

					$controller->deleteComment($id);
			}

			elseif ($action == 'usersadmin') {
				
				$controller->usersAdmin();
			}

			elseif ($action == 'myprofile') {
				
				if(! isset($id)){

					sessionmanager::getinstance()->set('USERID', $userId);
				}else{
					$userId = $id;
				}

				$controller->userProfile($userId);
			}

			elseif ($action == 'userupdate') {
			
					$controller->userUpdate($request->getPost()->all(),$id);
					
			}

			elseif ($action == 'userdelete') {
				
				$controller->userDelete($id, $userTypeId);
			}

			elseif ($action == 'commentsadmin') {
				$controller->listCommentsValidate();
			}

			elseif ($action == 'commentvalidate') {
					if (isset($id) && $id > 0) {
					$controller->CommentValidate($id);
					}
			}
	
	}else{
					

			\Http::redirect('accueil.html');
		}
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require'view/errorView.php';
}

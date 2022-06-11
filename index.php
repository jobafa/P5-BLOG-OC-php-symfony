<?php

//if( ! isset($_SESSION) ) session_start();


require_once __DIR__.'/autoload.php';
//require_once('inc/SessionManager.php');
//require_once __DIR__.'/Inc/functions.php';
//require_once __DIR__.'/inc/sanitization.php';
//require_once __DIR__.'/inc/validation.php';
require_once __DIR__.'/config/config.php';
//require_once __DIR__.'/Controllers/backend.php';
//require_once __DIR__.'/controllers/frontend.php';
//require_once __DIR__.'/controllers/Post.php';
//require_once __DIR__.'/controllers/Comment.php';
use Inc\SessionManager;
$request =  new \Inc\Request;
//\Application::process();
$controller = \Application::process();

//$get = new \Inc\Method($_GET);
//$post = new \Inc\Method($_POST);

if( null !== $request->getSession()) {
	session_start();
	//$session = new \Inc\SessionManager($_SESSION); // create session instance
}

/*if( ! isset($request) ) {
	
	$request = new \Inc\Request(); // create request instance
}*/

//$session = new \Inc\SessionManager($_SESSION); // create session instance
//$id =  $_GET['id'];
//if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'GET')){
	
if (null !== $request->getGet()->all()){
	
	//var_dump($request->getGet()->all());
	 //EXIT;
	//$dataInputs = $_GET;
	//$requestmethod = 'GET';
	
	// SANITIZE AND VALIDATE DATA

	$cleanData = new \Inc\Clean();
	$dataInputs = $cleanData->sanitize_get_data( $request->getget()->all());
	
	//$get = new \Inc\Method($dataInputs);

	//$get->all() = $dataInputs;
//}elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST')){

}elseif (null !== $request->getPost()->all()){

	//$post = $_POST;
	//$post = new \Inc\Method($_POST);
	//$controller = \Application::process();
	
	//$requestmethod = 'POST';

}




/*if($get->get('controller')){
	//var_dump($get->get('action'));
	//exit;
	//$action = strtolower($_GET['action']);
	$controller = \Application::process();
}*/
//if(isset($_GET['action'])){
if($request->getGet()->get('action')){
	//var_dump($get->get('action'));
	//exit;
	//$action = strtolower($_GET['action']);
	$action = strtolower($request->getGet()->get('action'));
	sessionmanager::getinstance()->set('ACTION', $action);
	//$action  = sessionmanager::getinstance()->get('ACTION');
}

//if (isset($_GET['id']) && ($_GET['id'] > 0)){
if ( ( $request->getGet()->get('id') ) && ( $request->getGet()->get('id') > 0 )){

	$id = $request->getGet()->get('id');

	
	//$id =  $_GET['id'];
}

//if (isset($_GET['email'])){
if($request->getGet()->get('email')){
	
	//$linkEmail =  $_GET['email'];
	$linkEmail = $request->getGet()->get('email');

	
		
}


//if (isset($_GET['token'])){
if($request->getGet()->get('token')){
	
	//$linkToken =  $_GET['token'];
	$linkToken = $request->getGet()->get('token');

	
		
}


//if (isset($_GET['usertypeid']) && ($_GET['usertypeid'] > 0)){
if ( ( $request->getGet()->get('usertypeid') ) && ( $request->getGet()->get('usertypeid') > 0 )){
	
	//$userTypeId =  $_GET['usertypeid'];
	$userTypeId = $request->getGet()->get('usertypeid');
			
}


// flag to check , after connection, where the user comes from and redirect him : post view or dashboard

//if (isset($_GET['from'])){
if($request->getGet()->get('from')){
	$from = sessionmanager::getinstance()->set('FROM', $request->getGet()->get('from'));
	//$_SESSION['FROM'] =  $_GET['from'];
	//$from = $_SESSION['FROM'];
			
}

//if (isset($_GET['idcomment']) && ($_GET['idcomment'] > 0)){
if ( ( $request->getGet()->get('idcomment') ) && ( $request->getGet()->get('idcomment') > 0 )){
	
	//$id =  $_GET['idcomment'];
	$id = $request->getGet()->get('idcomment');
	
}

//if (isset($_GET['idpost']) && ($_GET['idpost'] > 0)){
if ( ( $request->getGet()->get('idpost') ) && ( $request->getGet()->get('idpost') > 0 )){
	
	//$id =  $_GET['idpost'];
	$id = $request->getGet()->get('idpost');

}

//if (isset($_GET['page']) && ($_GET['page'] > 0)){
if ( ( $request->getGet()->get('page') ) && ( $request->getGet()->get('page') > 0 )){

	$getPage = $request->getGet()->get('page');
	//$getPage =  $_GET['page'];

}else{

	$getPage =  1;
}

if (sessionmanager::getinstance()->get('PSEUDO')){
	//$session->set('ACTION', $action);
	//$action  = $session->get('PSEUDO');
	$pseudo =  sessionmanager::getinstance()->get('PSEUDO');
}

if (sessionmanager::getinstance()->get('USERID')){
	//$session->set('ACTION', $action);
	//$action  = $session->get('PSEUDO');
	$userId = sessionmanager::getinstance()->get('USERID');
	
}



try {
    if ( isset($action) )
		{

			if ($action == 'listposts') {

				//$controller = new \Controllers\Post();
				//\Application::process();
				$controller->listposts($from, $getPage);
			}
			elseif (($action == 'frontpost') || ($action == 'post')) {
				if (isset($id) && $id > 0) {
					//$controller = new \Controllers\Post();
					//$is_published = '1';
					//\Application::process();
					$controller->post($id, $getPage);

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
				$controller->verifyLogin($request->getPost()->all(), $URL, 'login');
					
			}
			elseif ($action == 'userlogout') {
				//echo ' ENTRE INDEX';
				$controller = new \Controllers\User();
				$controller->userLogout();
					
			}

			elseif ($action == 'passresetrequest') {
				//echo ' ENTRE INDEX';
				//$controller = new \Controllers\User();
				$controller->passResetRequest();
			}

			elseif ($action == 'passreset') {
				//$controller = new \Controllers\User();
				$controller->passReset($request->getPost()->all(), $URL, 'passreset');
				
			}

			elseif ($action == 'passreinitialisation') {

				if(isset($linkToken) && isset($linkEmail)){
					//$controller = new \Controllers\User();
					$controller->verifyPassresetToken($linkEmail,$linkToken);
					
				}
				
					
			}
			
			elseif ($action == 'passreinitview') {
				
				//$controller = new \Controllers\User();
				$controller->passreinitView();
			}

			elseif ($action == 'passreinitnew') {
				
				//$controller = new \Controllers\User();
				$controller->passReinitNew();
			}

			elseif ($action == 'newpass') {

				if(null !== $request->getPost()->all()){
					//$controller = new \Controllers\User();
				$controller->getNewPass($request->getPost()->all(), $URL, 'newpass');
					
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
				$controller->addUserView($action);
			}
			elseif (($action == 'useradd') || ($action == 'usersignin')) {
				
				//$controller = new \Controllers\User();	
				//\Application::process();
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
				
				//\Application::process();
				//$controller = new \Controllers\User();
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
				//echo ' ENTRE INDEX';
				
				$controller->verifyType();
			}
			elseif ($action == 'adminposts') {
				//verifytype();
				$controller->listPostsUpdate();
			}
			elseif ($action == 'adminmyposts') {
					if(! isset($id)){
					//$userId = $_SESSION['USERID'];
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

					//$commentid = $id;
					
					$controller->deleteComment($id);
			}

			/*elseif (($action == 'adduserview') || ($action == 'signinview')) {
				
				//\Application::process();
				//$controller = new \Controllers\User();
				$controller->addUserView($action);
			}
			elseif (($action == 'useradd') || ($action == 'usersignin')) {
				
				//$controller = new \Controllers\User();	
				//\Application::process();
				$controller->addUser($post, $URL, 'newuser');
			}*/
			


			elseif ($action == 'usersadmin') {
				
				$controller->usersAdmin();
			}

			elseif ($action == 'myprofile') {
				
				if(! isset($id)){
					//$userId = $_SESSION['USERID'];
					sessionmanager::getinstance()->set('USERID', $userId);
				}else{
					$userId = $id;
				}

				$controller->userProfile($userId);
			}

			elseif ($action == 'userupdate') {
			
					//$post = $_POST;
					$controller->userUpdate($request->getPost()->all(),$id);
					//userUpdate($post,$id);
					
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
					
			//header('Location: accueil.html');
			\Http::redirect('accueil.html');
		}
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require'view/errorView.php';
}

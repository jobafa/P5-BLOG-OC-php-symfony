<?php
if( ! isset($_SESSION) ) session_start();

require_once __DIR__.'/inc/functions.php';
require_once __DIR__ .'/inc/sanitization.php';
require_once __DIR__ . '/inc/validation.php';
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/controller/backend.php';
require_once __DIR__.'/controller/frontend.php';


$id =  $_GET['id'];
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'GET')){

	$data_inputs = $_GET;
	$method = 'GET';

	$data_inputs = sanitize_get_data( $data_inputs);
	$_GET = $data_inputs;
}elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST')){

	$post = $_POST;
	$method = 'POST';

}


if (isset($_GET['action'])){
	
	$action = strtolower($_GET['action']);
	$_SESSION['ACTION'] = $action;
}

if (isset($_SESSION['PSEUDO'])){
	
	$pseudo =  $_SESSION['PSEUDO'];
}

if (isset($_GET['id']) && ($_GET['id'] > 0)){

	$id =  $_GET['id'];
}

if (isset($_GET['email'])){
	
	$link_email =  $_GET['email'];
	
		
}

if (isset($_GET['token'])){
	
	$link_token =  $_GET['token'];
	
		
}

if (isset($_GET['usertypeid']) && ($_GET['usertypeid'] > 0)){
	
	$usertypeid =  $_GET['usertypeid'];
			
}


// flag to check , after connection, where the user comes from and redirect him : post view or dashboard

if (isset($_GET['from'])){
	
	$_SESSION['FROM'] =  $_GET['from'];
	$from = $_SESSION['FROM'];
			
}

if (isset($_GET['idcomment']) && ($_GET['idcomment'] > 0)){
	
	$id =  $_GET['idcomment'];
	
}

if (isset($_GET['idpost']) && ($_GET['idpost'] > 0)){
	
	$id =  $_GET['idpost'];

}

if (isset($_GET['page']) && ($_GET['page'] > 0)){
	
					$getpage =  $_GET['page'];
	
					}else{
						$getpage =  1;
					}

try {
    if ( isset($action) )
		{

				if ($action == 'listposts') {
										
					listPosts($from = '', $getpage);
				}
				elseif (($action == 'frontpost')) {
					if (isset($id) && $id > 0) {
						
						$is_published = '1';
						post($id, $is_published );
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				elseif ( ($action == 'post')) {
					if (isset($id) && $id > 0) {
						$is_published = null;
						post($id, $is_published);
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				// CONTACT FORM
				elseif ( ($action == 'contactform')) {

					checkContactdata($post, $URL, 'blogcontact');
				}
				elseif ($action == 'addcomment') {
					
					if (isset($id) && $id > 0) {

						if (!empty($pseudo) && !empty($_POST['comment'])) {
							$userid = $_SESSION['USERID'] ;
							addComment($id, $pseudo, $_POST['comment'],$userid );
						}
						else {
							throw new Exception('Tous les champs ne sont pas remplis !');
						}
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				elseif ($action == 'modifycomment') {
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
				elseif (($action == 'adduserview') || ($action == 'signinview')) {
					
						adduserView($action);
				}
				elseif (($action == 'useradd') || ($action == 'usersignin')) {
					
						
						addUser($post, $URL, 'newuser');
				}
				elseif ($action == 'useractivation') {
						
						
						if (isset($_GET['token'])){ // MEANS WE ARE COMING FROM USER'S ACTIVATION LINK
							$token = $_GET['token'];
							$isactivated = NULL;
						}elseif(isset($_GET['isactivated'])){ // MEANS WE ARE COMING FROM ADMIN DASHBOARD
							$isactivated = $_GET['isactivated'];		
							$token  = NULL;
						}

						userActivation($id, $link_email, $token, $isactivated);
				}
				elseif ($action == 'loginview') {
					
						loginView();
				}
				elseif ($action == 'verifylogin') {
					//echo ' ENTRE INDEX';
					
						verifyLogin($post, $URL, 'login');
				}
				elseif ($action == 'userlogout') {
					//echo ' ENTRE INDEX';
					
						userLogout();
				}

				elseif ($action == 'passresetrequest') {
					//echo ' ENTRE INDEX';
					
						passresetRequest();
				}

				elseif ($action == 'passreset') {
					
						passReset($post, $URL, 'passreset');
					
				}

				elseif ($action == 'passreinitialisation') {

					if(isset($link_token) && isset($link_email)){
						verifyPassresetToken($link_email,$link_token);
						
					}
					
						
				}
				
				elseif ($action == 'passreinitview') {
					
						passreinitVew();
				}

				elseif ($action == 'passreinitnew') {
					
						passreinitNew();
				}

				elseif ($action == 'newpass') {

					if(isset($post)){
						getNewPass($post, $URL, 'newpass');
						
					}
					
						
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


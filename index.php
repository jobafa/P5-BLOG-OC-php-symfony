<?php
session_start();

//$_SESSION['actionmessage'] = 1;
require_once __DIR__.'/config/config.php';
//require('config/config.php');
//require('controller/frontend.php');


require_once __DIR__.'/controller/backend.php';
require_once __DIR__.'/controller/frontend.php';


/* ECRIRE FONCTION POUR NETTOYER TOUTES LES DONNNES RECUES PAR _GET OU _POST ET VERIFIER SI LES CHAMPS SONT RENSEIGNES*/


//var_dump($_SERVER['REQUEST_URI']);
if (isset($_POST)){
	
	$post = $_POST;
	
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
	//var_dump($id);
		
}

if (isset($_GET['email'])){
	
	$link_email =  $_GET['email'];
	//var_dump($id);
		
}

if (isset($_GET['token'])){
	
	$link_token =  $_GET['token'];
	//var_dump($id);
		
}

if (isset($_GET['usertypeid']) && ($_GET['usertypeid'] > 0)){
	
	$usertypeid =  $_GET['usertypeid'];
	//var_dump($id);
		
}

if (isset($_GET['from'])){

	
	$_SESSION['FROM'] =  $_GET['from'];
	$from = $_SESSION['FROM'];
	
	//var_dump($id);
		
}

if (isset($_GET['idcomment']) && ($_GET['idcomment'] > 0)){
	
	$id =  $_GET['idcomment'];
	//var_dump($id);
	//var_dump($action);
			//exit;
}

if (isset($_GET['idpost']) && ($_GET['idpost'] > 0)){
	
	$id =  $_GET['idpost'];
	//var_dump($id);
	//var_dump($action);
			//exit;
}



//echo $id;
//exit;


try {
    if ( isset($action) )
		{

				//$_GET['action']=strtolower($_GET['action']);
				//$action = $_GET['action'];

				if ($action == 'listposts') {

					listPosts($from);
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
						post($id, $is_published );
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				// CONTACT FORM
				elseif ( ($action == 'contactform')) {
					$contact_post = $post;
					
						sendcontactemail($contact_post);
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
						// $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
						
						  // $comment= $commentManager->getComment($id);
						  modifyComment($id);
						   //require('view/frontend/commenteditView.php');
						  
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
					//echo ' ENTRE INDEX';
					
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

							doAdd();
							
					 }
					 else {
						throw new Exception('probleme envoi formulaire');
					 }
				}

				elseif ($action == 'modifypost') {
					if (isset($id) && $id > 0) {
						modifyPost($id);
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
					
					
						addUser($_POST);
				}
				elseif ($action == 'useractivation') {
						
						$id = $_GET['id'];
						if (isset($_GET['token'])){
							$token = $_GET['token'];
							$isactivated = null;
						}elseif(isset($_GET['isactivated'])){
							$isactivated = $_GET['isactivated'];		
							$token  = null;
						}

						userActivation($id, $link_email, $token, $isactivated);
				}
				elseif ($action == 'loginview') {
					
						loginView();
				}
				elseif ($action == 'verifylogin') {
					//echo ' ENTRE INDEX';
					
						verifyLogin();
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
					//echo ' ENTRE INDEX';
					if(isset($_POST['password-reset']) && isset($_POST['email'])){
						$postemail = $_POST['email'];
					
						passReset($postemail);
					}
				}

				elseif ($action == 'passreinitialisation') {
//echo $action.' ENTRE INDEX';
					if(isset($link_token) && isset($link_email)){
						verifyPassresetToken($link_email,$link_token);
						//$postemail = $_POST['email'];
					}
					
						
				}

				elseif ($action == 'newpass') {
//echo $action.' ENTRE INDEX';
					if(isset($post)){
						getNewPass($post);
						//$postemail = $_POST['email'];
					}
					
						
				}


				elseif ($action == 'usersadmin') {
					//echo ' ENTRE INDEX';
						//$userid = $_SESSION['USERID'];
						usersAdmin();
				}

				elseif ($action == 'myprofile') {
					//echo ' ENTRE INDEX';
					if(! isset($id)){
						$userid = $_SESSION['USERID'];
					}else{
						$userid = $id;
					}
						userProfile($userid);
				}

				elseif ($action == 'userupdate') {
					//echo ' ENTRE INDEX';
						
						$post = $_POST;
						userupdate($post,$id);
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
					//listPosts();
					//$action = $_SESSION['ACTION'];
					header('Location: home.php');
			}
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}


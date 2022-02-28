<?php
session_start();

//$_SESSION['actionmessage'] = 1;
require('config/config.php');
require('controller/frontend.php');

if (isset($_SESSION['PSEUDO'])){
	
	$pseudo =  $_SESSION['PSEUDO'];
}

if (isset($_GET['id']) && ($_GET['id'] > 0)){
	
	$id =  $_GET['id'];
}

//echo $id;
//exit;


try {
    if (isset($_GET['action'])) 
		{

				$_GET['action']=strtolower($_GET['action']);
				$action = $_GET['action'];

				if ($_GET['action'] == 'listPosts') {
					listPosts();
				}
				elseif ($_GET['action'] == 'post') {
					if (isset($_GET['id']) && $_GET['id'] > 0) {
						post();
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				elseif ($_GET['action'] == 'addcomment') {
					
					if (isset($_GET['id']) && $_GET['id'] > 0) {
						if (!empty($pseudo) && !empty($_POST['comment'])) {
							addComment($_GET['id'], $pseudo, $_POST['comment']);
						}
						else {
							throw new Exception('Tous les champs ne sont pas remplis !');
						}
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}
				elseif ($_GET['action'] == 'modifycomment') {
					 if (isset($_GET['id']) && $_GET['id'] > 0) {
						// $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
						
						  // $comment= $commentManager->getComment($_GET['id']);
						  modifyComment($_GET['id']);
						   //require('view/frontend/commenteditView.php');
						  
					}
					else {
						throw new Exception('Aucun identifiant de billet envoyé');
					}
				}

				elseif ($_GET['action'] == 'updatecomment') {
					 if (isset($_GET['id']) && $_GET['id'] > 0) {

							$commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
						
						   
						
							$affectedLines = $commentManager->updateComment($_POST['comment'], $_GET['id']  );
							 if ($affectedLines === false) {
								throw new Exception('Impossible de mettre à jour le commentaire !');
							}
							else{
								//header('Location: index.php?action=post&id=' . $_GET['id'] .'&post_id='.$_GET['post_id'] );
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

				elseif ($_GET['action'] == 'backblogmanage') {
					//echo ' ENTRE INDEX';
					
						verifytype();
				}
				elseif ($_GET['action'] == 'adminposts') {
					listPostsUpdate();
				}
				elseif ($_GET['action'] == 'addpostview') {
					addPostView();
				}
				elseif ($_GET['action'] == 'postsupdate') {
					listPostsUpdate();
				}
				elseif ($_GET['action'] == 'addpost') {
					 if (isset($_POST) && !empty($_POST)) {

							doAdd();
							
					 }
					 else {
						throw new Exception('probleme envoi formulaire');
					 }
				}

				elseif ($_GET['action'] == 'modifypost') {
					if (isset($_GET['id']) && $_GET['id'] > 0) {
						modifyPost($_GET['id']);
					}
					else {
						throw new Exception('Aucun identifiant de post envoyé');
					}
				}
				elseif ($_GET['action'] == 'updatepost') {
					 if (isset($_GET['id']) && $_GET['id'] > 0) {

							$postManager = new \OC\PhpSymfony\Blog\Model\PostManager();

							$action = $_GET['action'];
							$id = $_GET['id'];
							$post = $_POST;

							$affectedLines = $postManager->updatePost($id, $post);
							//echo $action;
							//gerate a message according to the action process
							initmessage($action,$affectedLines);
//echo 'init '.$action.' '.$_SESSION['actionmessage'].' '.$_SESSION['alert_flag'];
//exit;
							/*if (isset($id) && (! $affectedLines )) {
								//$message = 0;
								$_SESSION['updatemessage'] = 0;
							}else if (isset($id) && ($affectedLines )) {
								//$message = 1;
								$_SESSION['updatemessage'] = 1;
							}*/

							 if ($affectedLines === false) {
								throw new Exception('Impossible de mettre à jour le post !');
							 }
							 else{
								//header('Location: index.php?action=post&id=' . $_GET['id'] .'&post_id='.$_GET['post_id'] );
								header('Location: index.php?action=postsupdate');// REVOYER SUR LISTE DES POSTS ADMIN
							 }
					 }
					 else {
						throw new Exception('Aucun identifiant de billet envoyé');
					 }
				

				}
				elseif ($_GET['action'] == 'deletepost') {
					
						deletepost($id);
				}
				elseif ($_GET['action'] == 'adduserview') {
					
						adduserView();
				}
				elseif ($_GET['action'] == 'useradd') {
					
						addUser();
				}
				elseif ($_GET['action'] == 'loginview') {
					
						loginView();
				}
				elseif ($_GET['action'] == 'verifylogin') {
					//echo ' ENTRE INDEX';
					
						verifyLogin();
				}
				elseif ($_GET['action'] == 'userlogout') {
					//echo ' ENTRE INDEX';
					
						userLogout();
				}

				elseif ($_GET['action'] == 'commentsadmin') {
					listCommentsValidate();
				}

				elseif ($_GET['action'] == 'commentvalidate') {
					 if (isset($_GET['id']) && $_GET['id'] > 0) {
					     CommentValidate($_GET['id']);
					 }
				}
	
	}else {
					listPosts();
			}
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}


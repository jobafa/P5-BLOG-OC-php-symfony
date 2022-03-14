<?php


// Chargement des classes


require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');

function listPosts($from)
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager(); // Création d'un objet

	/*if($ispublished == 'on'){

		$ispublished = '0';

	}elseif($ispublished == 'off'){
		$ispublished = '1';
	}*/
	
    $posts = $postManager->getPosts($userid = null, $from, $ispublished = '1'); // Appel d'une fonction de cet objet

	
    require('view/frontend/listPostsView.php');
}

function post($id, $is_published)
{
    $postManager = new \OC\PhpSymfony\Blog\Model\PostManager();
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();
	
    $post = $postManager->getPost($id, $is_published );

	if(isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1)){
		if($_SESSION['ACTION'] != 'frontpost'){
			require('view/backend/postView.php');
			
		}else{

			$comments = $commentManager->getComments($id,'1');
			require('view/frontend/postView.php');
			exit;
		}
	}else{
		$comments = $commentManager->getComments($id,'1');

		require('view/frontend/postView.php');
		exit;
	}
}

function addComment($postId, $author, $comment, $userid)
{
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $affectedLines = $commentManager->postComment($postId, $author, $comment, $userid);

	$action = $_GET['action'];

			//generate a message according to the action processed
			initmessage($action,$affectedLines);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=frontpost&id=' . $postId);
    }
}


		/**
	 * Sending  contact email  
	 * @param  Parameter $contact-post
	 * 
	 */

	function sendcontactemail($post)

	{
		$email = $post['email'];
		$subject = $post['subject'];
		$message = $post['message'];
		//$subject = "Réinitialisation de votre Mot de Passe";
		$headers = "From: Blog Abderrahim Fathi" . $email . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text; charset=UTF-8\r\n";
		//$message = ;

		//$message = wordwrap($message, 70, "\r\n");
		if(mail(CF_EMAIL, $subject, $message, $headers)){

			$email_ok = true;

		}else{

			$email_ok = false;	
		}

		$action = $_GET['action'];

			//generate a message according to the action processed
			
		initmessage($action,$email_ok);
		header('location: home.php');
		//echo $activation_code;
		
	}

// EXERCICE TP COURS A SUPPRIMER

function modifyComment($commentId)
{
    $commentManager = new \OC\PhpSymfony\Blog\Model\CommentManager();

    $comment = $commentManager->getComment($commentId);

	require('view/frontend/commenteditView.php');

    /*if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }*/
}

// FIN EXERCICE TP COURS


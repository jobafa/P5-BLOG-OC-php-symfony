<?php 
$title = htmlentities($post['title']); 

// CHEKS IF USER IS CONNECTED

if(isset($_SESSION['PSEUDO'])){ // ENABLE COMMENTS FORM
	
	$pseudo = $_SESSION['PSEUDO'];
	$formstatus = '';
	$placeholder = '';
}else
{ // DISABLE COMMENTS FORM

	$_SESSION['POSTID'] = $post['id']; // REGISTER POSTID TO SEND THE USER BACK TO THE POST VIEW AFTER CONNECTION
	
	$pseudo = '';
	$placeholder = "placeholder=\"Vous devez être connecté pour publier des commentaires\" ";
	$formstatus = " disabled";	
}

?>

<?php ob_start(); 


	if($post['photo'] == Null){
		$photo = "undraw_profile.svg";
	}else{
		$photo = $post['photo'];
	}
?>
<div class="container-fluid" id = "post">
<div class="row">
<div class="card shadow-bluedev mb-4">
		<div class="card-header my-3 py-3">

<h5 class="text-capitalize text-bluedev"><?= htmlentities($post['title']) ?>	</h5>
        <em class="text-sm-start text-muted">par <img class="rounded-circle mx-2" src="uploads/images/<?= $photo ?> "width="40"><span class="fw-bold"><?= $post['author'] ?></span> le <?= $post['update_date_fr'] ?></em><p><a class="text-secondary mx-2 " href="listposts.html?#posts"><!-- <a href="index.php?action=listposts">-->Retour à la liste des billets</a> </p>
</div>
		<div class="card-body ">

<div class="col mb-5">

<div class="profile-card mb-lg-4 justify-content-left">
                <div class=".profile-content justify-content-left">
				<div class="profile-img">
	<IMG SRC="uploads/images/<?= htmlentities($post['image']) ?>"   BORDER=0 ALT="">
</div>

								
									
</div>
</div><div class="fw-bold mt-3 pt-3">
						<?= htmlentities($post['lede']) ?>	
						<!-- <em>le <?= $data['creation_date_fr'] ?></em> -->
					
											
										</div>
<div class="news">
    
    
    <p>
        <?= nl2br(htmlentities($post['content'])) ?>
    </p>

<div>
</div>
<div>

<h2>Commentaires</h2>
<?php

// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

$message = is_alertMessage();

if (($message) && ($message != "")){

	echo $message;

	unset($_SESSION['actionmessage']);
	unset($_SESSION['alert_flag']);
}
//}
?>
<form action="index.php?action=addcomment&amp;id=<?= $post['id'] ?>" method="post">
     <div class="form-group">
        <label for="author">Auteur</label><br />
        <input type="text" class="form-control"  id="author" name="author" value="<?= $pseudo ?>" <?= $placeholder ?>  disabled />
    </div>
     <div class="form-group">
        <label for="comment">Commentaire</label><br />
        <textarea id="comment" class="form-control" name="comment" <?= $placeholder ?> <?= $formstatus ?> required></textarea>
    </div>
     <div class="form-group">
        <input type="submit" class="btn btn-primary my-lg-2" <?= $formstatus ?> />
    </div>
</form>

<?php
if(! isset($_SESSION['PSEUDO'])){
?>
<div class="content-fluid alert-info my-3">
	<div class=" col py-2 px-2 justify-content-right">
		<!-- <a class="text-secondary " href="index.php?action=loginview">Se Connecter</a> -->
		<a class="text-secondary " href="loginview.html#login">Se Connecter</a>
	<!-- </div>
	<div class="  col-6 py-2 px-2 justify-content-right"> -->
		<em>Vous n'avez pas encore de compte <!-- <a class=" text-secondary mx-2" href="index.php?action=signinview">Inscrivez Vous</a> --><a class=" text-secondary mx-2" href="signinview.html#inscription">Inscrivez Vous</a></em>
	</div>
</div>
<div class="container mt-5">

    <div class="d-flex justify-content-left  row">

        <div class="col-md-8">

            <div class="d-flex flex-column comment-section">
<?php
}
while ($comment = $comments->fetch())
{
	if($comment['photo'] == Null){
		$photo = "undraw_profile.svg";
	}else{
		$photo = $comment['photo'];
	}
?>

<!-- <div class="bg-gray p-2"> -->
					<div class="card mt-2 bg-white">

                    <div class="d-flex flex-row user-info mx-3 my-3">
							<img class="rounded-circle mx-2" src="uploads/images/<?= $photo ?> "width="40">
							
      <div class="d-flex flex-column justify-content-start ml-2">
											<span class="d-block font-weight-bold name"><?= htmlentities($comment['author']) ?></span>
											<span class="date text-black-50"> le <?= $comment['comment_date_fr'] ?></span>
									</div>
                    
					</div>
                   
					<div class="mx-3 mt-2">
					
       <p class="comment-text"><?= nl2br(htmlentities($comment['comment'])) ?>
							</p>
                    
					</div>
				</div>
            
    <!-- <p>Posté par <strong><?= htmlentities($comment['author']) ?></strong> le <?= $comment['comment_date_fr'] ?></p>
    <p class="mx-3"><?= nl2br(htmlentities($comment['comment'])) ?></p> -->
<?php
}
?>
</div>
			
   
			</div >
 <p class="my-3 mx-2"><a class="text-secondary  " href="listposts-front-<?= $getpage; ?>.html#posts">Retour à la liste des billets</a><!-- <a href="index.php?action=listposts">Retour à la liste des "</a> --></p>       
		</div>
    
	</div>

	</div>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>


                   
				 

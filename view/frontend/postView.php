<?php 

use Inc\SessionManager;
use Inc\MessageDisplay;

$cleanObject = new \Inc\Clean();
$messageDisplay = new \Inc\MessageDisplay();

// CHEKS IF USER IS CONNECTED

if(null !== SessionManager::getInstance()->get('PSEUDO')){ // IF USER CONNECTED ENABLE COMMENTS FORM
	
	$pseudo = SessionManager::getInstance()->get('PSEUDO');
	$formstatus = '';
	$placeholder = '';
}else
{ // DISABLE COMMENTS FORM
	
	SessionManager::getInstance()->Set('POSTID', $post['id']); // REGISTER POSTID TO SEND THE USER BACK TO THE POST VIEW AFTER CONNECTION
	$pseudo = '';
	$placeholder = "placeholder=\"Vous devez être connecté pour publier des commentaires\" ";
	$formstatus = " disabled";	
}

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

		<h5 class="text-capitalize text-bluedev"><?= $cleanObject->escapeoutput($post['title']) ?>	</h5>
				<em class="text-sm-start text-muted">par <img class="rounded-circle mx-2" src="uploads/images/<?= $photo ?> "width="40"><span class="fw-bold"><?= $cleanObject->escapeoutput($post['author']) ?></span> le <?= $post['update_date_fr'] ?></em><p><a class="text-secondary mx-2 " href="listposts-front-<?= $page;?>-post.html#posts">Retour à la liste des billets</a> </p>
		</div>
		<div class="card-body ">
			<div class="col mb-5">
				<div class="profile-card mb-lg-4 justify-content-left">
					<div class=".profile-content justify-content-left">
						<div class="profile-img img-fluid justify-content-left">
							<IMG SRC="uploads/images/<?= $cleanObject->escapeoutput($post['image']) ?>"   BORDER=0 ALT="">
						</div>
					</div>
				</div>
				<div class="fw-bold mt-3 pt-3">
					<?= $cleanObject->escapeoutput($post['lede']) ?>	
				</div>
				<div class="news">
					<p>
						<?= nl2br($cleanObject->escapeoutput($post['content'])) ?>
					</p>
				</div>	
				<div id="commentaires">
					<h2>Commentaires</h2>
					<?php
					// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE
					$message = $messageDisplay->is_alertMessage();

					if (($message) && ($message != "")){
						echo $message;

						SessionManager::getInstance()->sessionvarUnset('actionmessage');
						SessionManager::getInstance()->sessionvarUnset('alert_flag');
					}
					?>
					<form action="index.php?action=addcomment&amp;id=<?= $cleanObject->escapeoutput($post['id']) ?>&amp;controller=Comment" method="post">
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
					if(null === SessionManager::getInstance()->get('PSEUDO')){ // IF USER NOT CONNECTED
					?>
					<div class="content-fluid alert-info my-3">
						<div class=" col py-2 px-2 justify-content-right">
							
							<a class="text-secondary " href="loginview-user.html#login">Se Connecter</a>
						
							<em>Vous n'avez pas encore de compte <a class=" text-secondary mx-2" href="signinview-user.html#inscription">Inscrivez Vous</a></em>
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

									<div class="card mt-2 bg-white">
										<div class="d-flex flex-row user-info mx-3 my-3">
											<img class="rounded-circle mx-2" src="uploads/images/<?= $cleanObject->escapeoutput($photo) ?> "width="40">
											<div class="d-flex flex-column justify-content-start ml-2">
												<span class="d-block font-weight-bold name"><?= $cleanObject->escapeoutput($comment['author']) ?></span>
												<span class="date text-black-50"> le <?= $cleanObject->escapeoutput($comment['comment_date_fr']) ?></span>
											</div>
										</div>
										<div class="mx-3 mt-2">
											<p class="comment-text"><?= nl2br($cleanObject->escapeoutput($comment['comment'])) ?></p>
										</div>
									</div>
									<?php
									}
									?>
								</div>
							</div >
							<p class="my-3 mx-2">
									<a class="text-secondary  " href="listposts-front-<?= $page;?>-post.html#posts">Retour à la liste des billets</a>
							</p>       
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
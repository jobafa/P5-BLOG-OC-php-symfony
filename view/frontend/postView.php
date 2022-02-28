<?php 
$title = htmlspecialchars($post['title']); 
//$USERTYPEID  $_SE =SSION['USERTYPEID'];
if(isset($_SESSION['PSEUDO'])){
	
	$pseudo = $_SESSION['PSEUDO'];
	$formstatus = '';
	$placeholder = '';
}else
{
	$_SESSION['POSTID'] = $post['id'];
	$pseudo = '';
	$placeholder = "placeholder=\"Vous devez être connecté pour publier des commentaires\" ";
	$formstatus = " disabled";	
}

?>

<?php ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->
<p><a href="index.php">Retour à la liste des billets</a></p>
<div class="col-md-6 col-lg-4 mb-5">
<h1>
        <?= htmlspecialchars($post['title']) ?>
        
    </h1><em>par <?= $post['author'] ?> le <?= $post['creation_date_fr'] ?></em>
<div class="profile-card mb-lg-5 justify-content-left">
                <div class="profile-img">
	<IMG SRC="uploads/images/<?= htmlspecialchars($post['image']) ?>"  width="620" height="550" BORDER=0 ALT="">
</div>
</div>
</div>
<div class="news">
    
    
    <p>
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>
</div>

<h2>Commentaires</h2>

<form action="index.php?action=addcomment&amp;id=<?= $post['id'] ?>" method="post">
     <div class="form-group">
        <label for="author">Auteur</label><br />
        <input type="text" class="form-control" id="author" name="author" value="<?= $pseudo ?>" <?= $placeholder ?> disabled />
    </div>
     <div class="form-group">
        <label for="comment">Commentaire</label><br />
        <textarea id="comment" class="form-control" name="comment" <?= $placeholder ?> <?= $formstatus ?>></textarea>
    </div>
     <div class="form-group">
        <input type="submit" class="btn btn-primary my-lg-2" <?= $formstatus ?> />
    </div>
</form>

<?php
if(! isset($_SESSION['PSEUDO'])){
?>
<a class="btn btn-primary mb-lg-3" href="index.php?action=loginview">Se Connecter</a>
<?php
}
while ($comment = $comments->fetch())
{
?>
    <p>Posté par <strong><?= htmlspecialchars($comment['author']) ?></strong> le <?= $comment['comment_date_fr'] ?><!-- <em>(<a href="index.php?action=modifycomment&amp;id=<?= $comment['id'] ?>&amp;post_id=<?= $_GET['id']  ?>">modifier</a>)</em> --></p>
    <p class="mx-3"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
<?php
}
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>

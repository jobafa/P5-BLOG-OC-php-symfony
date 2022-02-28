<?php ?>

<?php ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->

<h2>Mise à jour >Article</h2><BR><BR>
<?php
//$data = $post->fetch();

?>
<form action="index.php?action=updatepost&amp;id=<?= $post['id'] ?>" method="post">
     <div class="form-group">
        <label for="author">Auteur</label><br />
        <input type="text" class="form-control" id="author" name="author" value="<?=  htmlspecialchars($post['author']) ?>" />
    </div>
	  <div class="form-group">
	<label for="title">titre</label><br />
        <input type="text" class="form-control" id="title" name="title" value="<?=  htmlspecialchars($post['title']) ?>" />
    </div>
	 <div class="form-group">
	<label for="lede">Châpo</label><br />
        <input type="text" class="form-control" id="lede" name="lede" value="<?=  htmlspecialchars($post['lede']) ?>" />
    </div>
     <div class="form-group">
        <label for="content">Article</label><br />
        <textarea class="form-control" id="content" name="content"><?= nl2br(htmlspecialchars($post['content']) )?></textarea>
    </div>
     <div class="form-group my-2">
        <input   type="submit" class="btn btn-primary" />
    </div>
</form>


<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
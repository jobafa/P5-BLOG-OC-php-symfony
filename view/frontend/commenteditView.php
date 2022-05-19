<?php ?>

<?php ob_start(); ?>
<h1>Mon super blog !</h1>
<!-- <p><a href="index.php">Retour à la liste des billets</a></p>

<div class="news">
    <h3>
        <?= htmlspecialchars($post['title']) ?>
        <em>le <?= $post['creation_date_fr'] ?></em>
    </h3>
    
    <p>
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>
</div>
 -->
<h2>Commentaire</h2>
<?php
$data = $comment->fetch();

?>
<form action="index.php?action=updatecomment&amp;id=<?= $data['id'] ?>&post_id=<?= $data['post_id'] ?>" method="post">
    <div>
        <label for="author">Auteur</label><br />
        <input type="text" id="author" name="author" value="<?=  htmlspecialchars($data['author']) ?>" />
    </div>
    <div>
        <label for="comment">Commentaire</label><br />
        <textarea id="comment" name="comment"><?= nl2br(htmlspecialchars($data['comment']) )?></textarea>
    </div>
    <div>
        <input type="submit" />
    </div>
</form>


<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
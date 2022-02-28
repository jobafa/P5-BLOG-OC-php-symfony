<?php $title = 'Mon blog'; ?>

<?php ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->
<p>Derniers Articles du blog :</p>


<?php
while ($data = $posts->fetch())
{

$texte=nl2br(htmlspecialchars($data['content']));
$texte = substr($texte, 0, 150);
?>
<div class="row">
<div class="col-md-6 col-lg-4 mb-5">
<div class="profile-card mb-lg-5 justify-content-left">
                <div class="profile-img">
	<IMG SRC="uploads/images/<?= htmlspecialchars($data['image']) ?>"  width="320" height="250" BORDER=0 ALT="">
</div>
</div>
</div>
    <div class="news col-md-6 col-lg-8 mb-5">
        <h3>
            <?= htmlspecialchars($data['title']) ?>
            <em>le <?= $data['creation_date_fr'] ?></em>
        </h3>
		<h6>
            
             <em>Par <?= $data['author'] ?></em>
        </h6>
		<h6>
            <?= htmlspecialchars($data['lede']) ?>
            <!-- <em>le <?= $data['creation_date_fr'] ?></em> -->
        </h6>
        
        <p>
            <?= $texte ?>....
            <br />
            <em><a href="index.php?action=post&amp;id=<?= $data['id'] ?>">Lire la suite</a></em>
        </p>
    </div>
</div>
<?php
}
$posts->closeCursor();
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>

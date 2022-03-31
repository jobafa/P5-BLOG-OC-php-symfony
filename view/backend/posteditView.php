<?php 

ob_start(); 
?>
<!-- <h1>Mon super blog !</h1> -->



<!-- <h1>Mon super blog !</h1> -->
<!-- Begin Page Content -->
<div class="container-fluid">
 <div class="card shadow mb-4">
<div class="card-header py-3">
	<h3 class="m-0 font-weight-bold text-info">Mise à jour  Article</h3>
</div>
<div class="container">

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
  <div class="card card-login mx-auto my-3 px-0">
      <div class="card-body">
<form enctype="multipart/form-data" action="index.php?action=updatepost&amp;id=<?= $post['id'] ?>" method="post">
    <div class="form-group">
        <label for="author" class="form-label">Auteur</label><br />
        <input type="text"  class="form-control" id="author" name="author" value="<?=  htmlspecialchars($post['author']) ?>" required />
    </div>
    <div class="form-group" class="form-label">
	<label for="title">titre</label><br />
        <input type="text" class="form-control" id="title" name="title" value="<?=  htmlspecialchars($post['title']) ?>" required />
    </div>
	<div class="form-group" class="form-label">
	<label for="lede">Châpo</label><br />
        <input type="text" class="form-control" id="lede" name="lede" value="<?=  htmlspecialchars($post['lede']) ?>" required />
    </div >
    <div class="form-group">
        <label for="content" class="form-label">Article</label><br />
        <textarea class="form-control" id="content" name="content" required><?= nl2br(htmlspecialchars($post['content']) )?></textarea>
    </div >
	<div class="form-group">
        <label for="pimage" class="form-label">Image</label>
        <input type="file" class="form-control py-1" id="pimage" name="pimage" />
    </div>
    <div class="form-group my-3">
        <input   type="submit" class="btn btn-primary" />
    </div>
</form>
</div>
    </div>
  </div>
</div>
</div>

<?php $content = ob_get_clean(); 
require('admintemplate.php');
?>

<?php 
//echo $action.' '.$_SESSION['actionmessage'].' '.$_SESSION['alert_flag'];
if(isset($_SESSION['actionmessage'])) {
	//echo $_SESSION['message'];
	$actionmessage = $_SESSION['actionmessage'];

}
if(isset($_SESSION['alert_flag'])) {
	//echo $_SESSION['message'];
	$alert_flag = $_SESSION['alert_flag'];

}


/*if(isset($_SESSION['updatemessage'])) {
	//echo $_SESSION['message'];
	$actionmessage = $_SESSION['updatemessage'];
}
*/


ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->
<!-- Begin Page Content -->
<div class="container-fluid">
 <div class="card shadow mb-4">
<div class="card-header py-3">
	<h3 class="m-0 font-weight-bold text-info">Ajout Article</h3>
</div>
<div class="container">

<?php
	//if(isset($_GET['message']) && !empty($_GET['message'])){
		if (isset($alert_flag) &&  ($alert_flag == 0)){
			//$affichage = "Deleting post issue !";
			$classe = "alert-danger";
		}else if(isset($alert_flag) &&  ($alert_flag == 1)){
			//$affichage = "Success ! Post was Deleted !";
			$classe = "alert-success";
		}
    //}
//echo  $actionmessage ;
if(isset($actionmessage) && ($alert_flag == 0 || $alert_flag == 1)) {
?>
<div class="alert <?= $classe ?>" role="alert">
  <?= $actionmessage ?>
</div>
<?php
unset($_SESSION['actionmessage']);
unset($_SESSION['alert_flag']);
}
?>
  <div class="card card-login mx-auto my-3 px-0">
      <div class="card-body">
<form enctype="multipart/form-data" action="index.php?action=addpost" method="post">
    <div class="form-group">
        <label for="author" class="form-label">Auteur</label><br />
        <input type="text"  class="form-control" id="author" name="author" value="<?php if(isset($post))  echo htmlspecialchars($post['author']); ?>" required />
    </div>
    <div class="form-group" class="form-label">
	<label for="title">titre</label><br />
        <input type="text" class="form-control" id="title" name="title" value="<?php if(isset($post))  echo  htmlspecialchars($post['title']); ?>" required />
    </div>
	<div class="form-group" class="form-label">
	<label for="lede">Châpo</label><br />
        <input type="text" class="form-control" id="lede" name="lede" value="<?php if(isset($post))  echo htmlspecialchars($post['lede']); ?>" required />
    </div >
    <div class="form-group">
        <label for="content" class="form-label">Article</label><br />
        <textarea class="form-control" id="content" name="content" required><?php if(isset($post))  echo htmlspecialchars($post['content']); ?></textarea>
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

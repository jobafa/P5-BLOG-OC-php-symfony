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

<h2>Ajout Utilisateur</h2><BR><BR>
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
<div class="alert <?= $classe ?>" alert-dismissible fade show role="alert">
  <?= $actionmessage ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
unset($_SESSION['actionmessage']);
unset($_SESSION['alert_flag']);
}
?>

<form enctype="multipart/form-data" action="index.php?action=useradd" method="post">
    <div class="form-group">
        <label for="pseudo" class="form-label">Pseudo</label><br />
        <input type="text"  class="form-control" id="pseudo" name="pseudo" value="" />
    </div>
    <div class="form-group" class="form-label">
	<label for="email">Email</label><br />
        <input type="text" class="form-control" id="email" name="email" value="" />
    </div>
	<div class="form-group" class="form-label">
	<label for="password">Mot de Passe</label><br />
        <input type="text" class="form-control" id="password" name="password" value="" />
    </div >
	<div class="form-group" class="form-label">
	<label for="usertype_id">Rôle Utilisateur</label><br />
		<select class="form-select" name="usertype_id" aria-label="Default select example">
			<option selected>choisir le rôle Utilisateur</option>
			<option value="1">Administrateur</option>
			<option value="2">Editeur</option>
		  <option value="3">Visiteur</option>
		</select>
	</div >

   <!--  <div class="form-group">
        <label for="content" class="form-label">Article</label><br />
        <textarea class="form-control" id="content" name="content"></textarea>
    </div >
	<div class="form-group">
        <label for="pimage" class="form-label">Image</label>
        <input type="file" class="form-control" id="pimage" name="pimage" />
    </div> -->
    <div class="form-group my-3">
        <input   type="submit" class="btn btn-primary" />
    </div>
</form>


<?php $content = ob_get_clean(); 
require('template.php');
?>

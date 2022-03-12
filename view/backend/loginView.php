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

<h2>Authentification  Utilisateur</h2><BR><BR>
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
<div class="alert <?= $classe ?> my-2" alert-dismissible fade show role="alert">
  <em><?= $actionmessage ?></em>
  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
unset($_SESSION['actionmessage']);
unset($_SESSION['alert_flag']);
}
?>


 <div class="container">
    <div class="card card-login mx-auto px-0">
      <div class="card-body">
        <form action="index.php?action=verifylogin" method="post">
          <div class="form-group">
            <label for="email">identifiant</label>
            <input type="email" class="form-control" type="text" placeholder="Entrer votre identifiant" name="email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" type="password" placeholder="Mot de passe" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary my-3">Se connecter</button>
		  <div class="content-fluid alert-info  py-1 px-1 ">
		  <p><small><a class="text-secondary mx-2 " href="index.php?action=passresetrequest">J'ai oubli&eacute; mon Mot de Passe</a>Vous n'avez pas encore de compte <a class=" text-secondary mx-2" href="index.php?action=signinview">Inscrivez Vous</a></small></p>

		   </div>


        </form>
	<!-- 	<div class="row  col-6 py-2 px-2 justify-content-right"> -->
<!-- </div> -->
      </div>
    </div>
  </div>
 <!--  <div class="content-fluid alert-info my-3">
<div class="row col-2 py-2 px-2 justify-content-right">
<a class="btn btn-primary " href="index.php?action=loginview">Se Connecter</a>
</div>
<div class="row  col-6 py-2 px-2 justify-content-right">
<em>Vous n'avez pas encore de compte <a class=" text-secondary mx-2" href="index.php?action=signinview">Inscrivez Vous</a></em>
</div>
</div> -->


<?php $content = ob_get_clean(); 
require('template.php');
?>

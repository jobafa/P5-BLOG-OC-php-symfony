<?php
// GET THE HIDEN FIELD WITH CRSF TOKEN
$token_field = get_token_field('newuser');

//echo $action.' '.$_SESSION['actionmessage'].' '.$_SESSION['alert_flag'];
if(isset($_SESSION['actionmessage'])) {
	//echo $_SESSION['message'];
	$actionmessage = $_SESSION['actionmessage'];

}
if(isset($_SESSION['alert_flag'])) {
	//echo $_SESSION['message'];
	$alert_flag = $_SESSION['alert_flag'];

}

ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->
<!-- Begin Page Content -->
<div class="container-fluid" id = "inscription">
 <div class="card shadow mb-4">
<div class="card-header py-3">
	<h3 class="m-0 font-weight-bold text-info">Inscription Utilisateur</h3>
</div>
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
<div class="alert <?= $classe ?> mx-2 my-2 alert-dismissible fade show" role="alert">
  <?= $actionmessage ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
unset($_SESSION['actionmessage']);
unset($_SESSION['alert_flag']);
}
?>


<div class="container">
    <div class="card card-login mx-auto my-3 px-0">
      <div class="card-body">
			<?php
					if(isset($_SESSION['errors'] )){
				?>
						<div class="alert  alert-danger my-2 alert-dismissible fade show" role="alert">
						  <em>
						  <?php
								foreach($_SESSION['errors'] as $key=>$value){

									echo $value.'<BR>';
								}
							?>
						  </em>
					  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
			<?php	
			}
			?>
			<form enctype="multipart/form-data" action="index.php?action=usersignin" method="post">
				<div class="form-group">
					<label for="pseudo" class="form-label"><i class="fas fa-user mx-1"></i>Pseudo</label><br />
					<input type="text"  class="form-control" id="pseudo" name="pseudo" value="" required />
				</div>
				<div class="form-group" class="form-label">
				<label for="email"><i class="fas fa-envelope mx-1"></i>Email</label><br />
					<input type="text" class="form-control" id="email" name="email" value="" required />
				</div>
				<div class="form-group" class="form-label">
				<label for="password"><i class="fas fa-lock mx-1"></i>Mot de Passe</label><br />
					<input type="password" class="form-control" id="password" name="password" value=""  required/>
				</div >
				<div class="form-group">
					<label for="photo" class="form-label">Photo ( Avatar )</label>
					<input type="file" class="form-control py-1" id="photo" name="photo" />
				</div>
				<div class="g-recaptcha" data-sitekey="6LcRt9UeAAAAANoCcOoFihVp2eShv5YpYQia9Aw1"></div>
				<?php
						echo $token_field;
				?>
				<div class="form-group my-3">
					<input   type="submit" class="btn btn-info" />
				</div>
			</form>
</div>
    </div>
  </div>
</div>
</div>
<?php $content = ob_get_clean(); 
require('template.php');
?>

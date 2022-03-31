<?php 
// GET THE HIDEN FIELD WITH CRSF TOKEN
$token_field = get_token_field('login');
//var_dump($action);
//var_dump($token_field);

//exit;
//GET REQUIRED ACTION MESSAGE
if(isset($_SESSION['actionmessage'])) {
	
	$actionmessage = $_SESSION['actionmessage'];

}
if(isset($_SESSION['alert_flag'])) {
	
	$alert_flag = $_SESSION['alert_flag'];

}



ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->

<!-- <h2>Authentification  Utilisateur</h2><BR><BR> -->
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

?>
<div class="container-fluid" id = "login">
<div class="row justify-content-center  my-3" id=login">
  <div class="col-lg-10 "> 
	<div class="card shadow">
		<div class="card-header py-2">
			<h5 class=" text-capitalize  m-0 font-weight-bold text-info">Authentification  Utilisateur</h5>
		</div>
 <!-- <div class="container" id="loginform">
    <div class="card card-login mx-auto px-0"> -->
      <div class="card-body">

		 <?php if(isset($actionmessage) && ($alert_flag == 0 || $alert_flag == 1)) {
			?>
			<div class="alert <?= $classe ?> my-2 alert-dismissible fade show" role="alert">
			  <em><?= $actionmessage ?></em>
			  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php
			unset($_SESSION['actionmessage']);
			unset($_SESSION['alert_flag']);
			}

			if(isset($_SESSION['errors'] )){
				?>
				<div class="alert  text-danger my-2 alert-dismissible fade show" role="alert">
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

        <form action="index.php?action=verifylogin" method="post">
          <div class="form-group">
            <label for="email">identifiant</label>
            <input type="email" class="form-control" type="text" placeholder="Entrer votre identifiant" name="email" required>
			<!-- <?php
				if(isset($emailErr) && ($emailErr != '')) {
			?>
			<span class = "text-danger">* <?php echo $emailErr;?></span>
			<?php
				}
			?> -->
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" type="password" placeholder="Mot de passe" name="password" required>
			<!-- <?php
				if(isset($passordlErr) && ($passordlErr != '')) {
			?>
			<span class = "danger">* <?php echo $passordlErr;?></span>
			<?php
				}
			?> -->
			
          </div>
		  <?php


										echo $token_field;
							?>
          <button type="submit" class="btn btn-primary my-3">Se connecter</button>
		  <div class="content-fluid alert-info  py-1 px-1 ">
		  <p><small><!-- <a class="text-secondary mx-2 " href="index.php?action=passresetrequest"> --><a class="text-secondary mx-2 " href="passresetrequest.html">J'ai oubli&eacute; mon Mot de Passe</a>Vous n'avez pas encore de compte <!-- <a class=" text-secondary mx-2" href="index.php?action=signinview"> --><a class=" text-secondary mx-2" href="signinview.html#inscription">Inscrivez Vous</a></small></p>

		   </div>


        </form>
	<!-- 	<div class="row  col-6 py-2 px-2 justify-content-right"> -->
<!----> </div> 
      </div>
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

<?php 
// GET THE HIDEN FIELD WITH CRSF TOKEN
$token_field = get_token_field('newpass');
//var_dump($_SESSION['errors'] );
		//	exit;
if(isset($_SESSION['LINK_EMAIL']) &&  isset($_SESSION['LINK_TOKEN'])){
	$link_email = $_SESSION['LINK_EMAIL'] ;
	$link_token = 	$_SESSION['LINK_TOKEN'] ;
}
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

<!-- <h3  class="text-muted">Réinitialisation Mot de Passe</h3> -->
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


 <div class="container">
    <div class="card card-login mx-auto px-0">
		<div class="card-header text-left"><h5  class="text-bluedev">Réinitialisation Mot de Passe</h5>
              <!-- Merci de saisir et confirmer votre mot de passe  -->
        </div>
       <div class="card-body">
		<?php
					if(isset($_SESSION['errors'] )){
				?>
						<div class="alert  text-danger my-2 alert-dismissible fade show" role="alert">
						  <em>
						  <?php
								if($_SESSION['errors']){
									foreach($_SESSION['errors'] as $key=>$value){

										echo $value.'<BR>';
									}
								}
						//$_SESSION['errors'] = '';
					?>
						  </em>
					  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
			<?php	
			}
			if(isset($actionmessage) && ($alert_flag == 0 || $alert_flag == 1)) {
			?>
				<div class="alert <?= $classe ?> my-2 alert-dismissible fade" show role="alert">
				  <em><?= $actionmessage ?></em>
				  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php
				unset($_SESSION['actionmessage']);
				unset($_SESSION['alert_flag']);
			}
			?>
        <form action="index.php?action=newpass" method="post">
				  <input type="hidden" name="email" value="<?= $link_email;?>">
				  <input type="hidden" name="reset_link_token" value="<?= $link_token;?>">
				 <div class="form-group">
				  <label for="newpassword">Mot de Passe </label>
				  <input type="password" placeholder="Entrer votre mot de passe" name="newpassword" class="form-control" id="newpassword" required>
				  
				</div>
				 <div class="form-group">
				  <label for="confirmnewpassword">Confirmer Mot de Passe </label>
				  <input type="password" placeholder="Confirmer votre mot de passe" name="confirmnewpassword" class="form-control" id="confirmnewpassword" required>
				  
				</div>
				<?php


										echo $token_field;
							?>
                <input type="submit" name="password-reinit" class="btn btn-primary my-2">
          
        </form>
      </div>
    </div>
  </div>
  

<?php $content = ob_get_clean(); 
require('template.php');
?>



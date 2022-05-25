<?php 

// GET THE HIDEN FIELD WITH CRSF TOKEN

//$token_field = get_token_field('newpass');
$passreinitnewtoken = new \Inc\Clean();
//var_dump($signuptoken);
$token_field = $passreinitnewtoken->get_token_field('newpass');

$SessionManager = new \Inc\SessionManager($_SESSION);
//if(isset($_SESSION['LINK_EMAIL']) &&  isset($_SESSION['LINK_TOKEN'])){
if((null !== $SessionManager->get('LINK_EMAIL')) && (null !== $SessionManager->get('LINK_TOKEN'))){
	$link_email = $SessionManager->get('LINK_EMAIL');
	$link_token = $SessionManager->get('LINK_TOKEN');
	//$link_email = $_SESSION['LINK_EMAIL'] ;
	//$link_token = 	$_SESSION['LINK_TOKEN'] ;

}

ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->


 <div class="container" id="newpass">

    <div class="card card-login mx-auto px-0">
		<div class="card-header text-left"><h5  class="text-bluedev">Réinitialisation Mot de Passe</h5>
              <!-- Merci de saisir et confirmer votre mot de passe  -->
        </div>
       <div class="card-body">
		<?php

		//if(isset($_SESSION['errors'] )){
			if(null !== $SessionManager->get('errors')){
				?>
					<div class="alert  text-danger my-2 alert-dismissible fade show" role="alert">
					    <em>
					  <?php
							//if($_SESSION['errors']){
								//foreach($_SESSION['errors'] as $key=>$value){
								foreach($SessionManager->get('errors') as $key=>$value){
		
									echo $value.'<BR>';
								}
							//}
						?>
			  			</em>
		  				<button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>

		<?php
		}

		// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

		$message = is_alertMessage();

		if (($message) && ($message != "")){

			echo $message;


			$SessionManager->sessionvarUnset('actionmessage');
			$SessionManager->sessionvarUnset('alert_flag');
			//unset($_SESSION['actionmessage']);
			//unset($_SESSION['alert_flag']);
		}
		//}
		?>
        <form action="index.php?action=newpass&controller=user" method="post">
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
		
			<?= $token_field;?>
					
			<input type="submit" name="password-reinit" class="btn btn-primary my-2">

          
        </form>
      </div>
    </div>
  </div>
  

<?php $content = ob_get_clean(); 
require('template.php');
?>



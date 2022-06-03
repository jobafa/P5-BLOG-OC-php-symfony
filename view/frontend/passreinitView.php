<?php 
use Inc\SessionManager;
// GET THE HIDEN FIELD WITH CRSF TOKEN

$passreinitnewtoken = new \Inc\Clean();
//use Inc\MessageDisplay;
$messagedisplay = new \Inc\MessageDisplay();

$token_field = $passreinitnewtoken->get_token_field('newpass');

//$SessionManager = new \Inc\SessionManager($_SESSION);

if((null !== SessionManager::getInstance()->get('LINK_EMAIL')) && (null !== SessionManager::getInstance()->get('LINK_TOKEN'))){
	$link_email = SessionManager::getInstance()->get('LINK_EMAIL');
	$link_token = SessionManager::getInstance()->get('LINK_TOKEN');

}

ob_start(); ?>


 <div class="container" id="newpass">
    <div class="card card-login mx-auto px-0">
		<div class="card-header text-left"><h5  class="text-bluedev">Réinitialisation Mot de Passe</h5>
              
        </div>
       <div class="card-body">
		<?php
		
			if(null !== SessionManager::getInstance()->get('errors')){
				?>
					<div class="alert  text-danger my-2 alert-dismissible fade show" role="alert">
					    <em>
					  <?php
							
								foreach(SessionManager::getInstance()->get('errors') as $key=>$value){
		
									echo $value.'<BR>';
								}
							
						?>
			  			</em>
		  				<button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>

		<?php
		}
		// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

		$message = $messagedisplay->is_alertMessage();

		if (($message) && ($message != "")){

			echo $message;

			//$SessionManager->sessionvarUnset('actionmessage');
			//$SessionManager->sessionvarUnset('alert_flag');
			SessionManager::getInstance()->sessionvarUnset('actionmessage');
			SessionManager::getInstance()->sessionvarUnset('alert_flag');
			
		}
		
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



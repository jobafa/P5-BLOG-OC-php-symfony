<?php 

use Inc\SessionManager;
// GET THE HIDEN FIELD WITH CRSF TOKEN
$logintoken = new \Inc\Clean();
//use Inc\MessageDisplay;
$messagedisplay = new \Inc\MessageDisplay();
$token_field = $logintoken->get_token_field('passreset');

//$SessionManager = new \Inc\SessionManager($_SESSION);


ob_start(); ?>

 <div class="container" id="passresetrequest">
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

			SessionManager::getInstance()->sessionvarUnset('actionmessage');
			SessionManager::getInstance()->sessionvarUnset('alert_flag');
			
		}
		
		?>

        <form action="index.php?action=passreset&controller=user" method="post">
		 	<div class="form-group">
				<label for="email">Adresse Email </label>
				<input type="email" placeholder="Entrer votre email" name="email" class="form-control" id="email" required>
				
            </div>

			<?= $token_field;?>
							
            <input type="submit" name="password-reset" class="btn btn-primary my-2">
          
        </form>
      </div>
    </div>
  </div>
  

<?php $content = ob_get_clean(); 
require('template.php');
?>
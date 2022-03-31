<?php 

// GET THE HIDEN FIELD WITH CRSF TOKEN
$token_field = get_token_field('passreset');

ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->


 <div class="container">
    <div class="card card-login mx-auto px-0">
		<div class="card-header text-left"><h5  class="text-bluedev">Réinitialisation Mot de Passe</h5>
              <!-- Merci de saisir votre email -->
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
				?>

			  </em>
		  <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>

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

        <form action="index.php?action=passreset" method="post">
		 <div class="form-group">
                  <label for="email">Adresse Email </label>
                  <input type="email" placeholder="Entrer votre email" name="email" class="form-control" id="email" required>
                  
                </div>
				<?php


										echo $token_field;
							?>
                <input type="submit" name="password-reset" class="btn btn-primary my-2">
          
        </form>
      </div>
    </div>
  </div>
  

<?php $content = ob_get_clean(); 
require('template.php');
?>



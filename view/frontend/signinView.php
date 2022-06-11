<?php
use Inc\SessionManager;

ob_start(); 
$title = "Inscription Utilisateur";
?>
<!-- Begin Page Content -->

<div class="container-fluid" id = "inscription">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h3 class="m-0 font-weight-bold text-info">Inscription Utilisateur</h3>
		</div>
		<?php

		// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

		$message = $this->messageDisplay->is_alertMessage();

		if (($message) && ($message != "")){

			echo $message;

			SessionManager::getInstance()->sessionvarUnset('actionmessage');
			SessionManager::getInstance()->sessionvarUnset('alert_flag');
		}
		?>
		<div class="container">
			<div class="card card-login mx-auto my-3 px-0">
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
									SessionManager::getInstance()->sessionvarUnset('errors');
							?>
							</em>
							<button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php	
					}
					?>
					
					<form enctype="multipart/form-data" action="index.php?action=usersignin&controller=user" method="post">
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
								<?= $tokenField;?>
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
require'template.php';
?>
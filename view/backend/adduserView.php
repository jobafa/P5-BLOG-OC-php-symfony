<?php 

use \Inc\SessionManager;
$messageDisplay = new \Inc\MessageDisplay();

ob_start(); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h3 class="m-0 font-weight-bold text-info">Ajout Utilisateur</h3>
		</div>
		<div class="container">
		<?php
		// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

		$message = $messageDisplay->is_alertMessage();

		if (($message) && ($message != "")){

			echo $message;
			
			SessionManager::getInstance()->sessionvarUnset('actionmessage');
			SessionManager::getInstance()->sessionvarUnset('alert_flag');
			
		}

		?>
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
								
					<form enctype="multipart/form-data" action="index.php?action=useradd&controller=user" method="post">
						<div class="form-group">
							<label for="pseudo" class="form-label">Pseudo</label><br />
							<input type="text"  class="form-control" id="pseudo" name="pseudo" value="" required />
						</div>
						<div class="form-group" class="form-label">
							<label for="email">Email</label><br />
							<input type="text" class="form-control" id="email" name="email" value="" required />
						</div>
						<div class="form-group" class="form-label">
							<label for="password">Mot de Passe</label><br />
							<input type="password" class="form-control" id="password" name="password" value="" required />
						</div >
						<div class="form-group">
							<label for="photo" class="form-label">Photo</label>
							<input type="file" class="form-control py-1" id="photo" name="photo" />
						</div>
						<div class="form-group" class="form-label">
							<label for="usertype_id">Rôle Utilisateur</label><br />
							<select class="form-select" name="usertype_id" aria-label="Default select example" required>
								<option value="">choisir le rôle Utilisateur</option>
								<option value="1">Administrateur</option>
								<!-- <option value="2">Editeur</option> -->
							<option value="3">Visiteur</option>
							</select>
						</div >
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
require'admintemplate.php';
?>

<?php 

/*// GET THE HIDEN FIELD WITH CRSF TOKEN
$token_field = get_token_field('login');*/
// GET THE HIDEN FIELD WITH CRSF TOKEN
$logintoken = new \Inc\Clean();
//var_dump($signuptoken);
$token_field = $logintoken->get_token_field('login');

$SessionManager = new \Inc\SessionManager($_SESSION);

ob_start(); ?>

<div class="container-fluid" id = "login">
<div class="row justify-content-center  my-3" id=login">
  <div class="col-lg-10 "> 
	<div class="card shadow">
		<div class="card-header py-2">
			<h5 class=" text-capitalize  m-0 font-weight-bold text-info">Authentification  Utilisateur</h5>
		</div>
 
      <div class="card-body">

		 <?php
			 
			
			// CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

			$message = is_alertMessage();

			if (($message) && ($message != "")){

				echo $message;

				$SessionManager->sessionvarUnset('actionmessage');
				$SessionManager->sessionvarUnset('alert_flag');
				//unset($_SESSION['actionmessage']);
				//unset($_SESSION['alert_flag']);
			}
		
			/*if(isset($_SESSION['errors'] )){
			
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
			</div>*/
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
			?>

        <form action="index.php?action=verifylogin&amp;controller=user" method="post">
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

		  <?= $token_field;?>
										
							
          <button type="submit" class="btn btn-primary my-3">Se connecter</button>
		  <div class="content-fluid alert-info  py-1 px-1 ">
		  <p><small><a class="text-secondary mx-2 " href="passresetrequest-user.html#passresetrequest">J'ai oubli&eacute; mon Mot de Passe</a>Vous n'avez pas encore de compte <!-- <a class=" text-secondary mx-2" href="index.php?action=signinview"> --><a class=" text-secondary mx-2" href="signinview-user.html#inscription">Inscrivez Vous</a></small></p>

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

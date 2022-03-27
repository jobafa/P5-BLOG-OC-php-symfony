<?php 
// GET THE HIDEN FIELD WITH CRSF TOKEN
$token_field = get_token_field('passreset');


/*echo $action.' '.$_SESSION['actionmessage'].' '.$_SESSION['alert_flag'];
exit;*/
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



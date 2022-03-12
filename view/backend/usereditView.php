<?php 
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

<!-- Begin Page Content -->
<div class="container-fluid">
 <div class="card shadow mb-4">
<div class="card-header py-3">
	<h3 class="m-0 font-weight-bold text-info">Mise à jour Profile Utilisateur</h3>
</div>
<div class="container">
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
if(isset($actionmessage) && ($alert_flag == 0 || $alert_flag == 1)) {
?>
<div class="alert <?= $classe ?> my-2" alert-dismissible fade show role="alert">
  <?= $actionmessage ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
unset($_SESSION['actionmessage']);
unset($_SESSION['alert_flag']);
}

if (! isset($profile)){
	$profile['id'] = '';
	$profile['lastname'] = '';
	$profile['firstname'] = '';
	$profile['phone_number'] = '';
	$profile['pseudo'] = '';
	$profile['email'] = '';
	}
?>

    <div class="card card-login mx-auto my-3 px-0">
      <div class="card-body">
			<form enctype="multipart/form-data" action="index.php?action=userupdate&id=<?= htmlspecialchars($profile['id']) ?>" method="post">
				<div class="form-group">
					<label for="lastname" class="form-label">Nom</label><br />
					<input type="text"  class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($profile['lastname']) ?>" required />
				</div>
				<div class="form-group">
					<label for="firstname" class="form-label">Prénom</label><br />
					<input type="text"  class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($profile['firstname']) ?>" required />
				</div>
				<div class="form-group">
					<label for="phone_number" class="form-label">Numéro Téléphone</label><br />
					<input type="text"  class="form-control" id="phone_number" name="phone_number" value="<?=  htmlspecialchars($profile['phone_number']) ?>" required />
				</div>
				<div class="form-group">
					<label for="pseudo" class="form-label">Pseudo</label><br />
					<input type="text"  class="form-control" id="pseudo" name="pseudo" value="<?= htmlspecialchars($profile['pseudo']) ?>" required />
				</div>
				<div class="form-group" class="form-label">
					<label for="email">Email</label><br />
					<input type="text" class="form-control" id="email" name="email" value="<?=  htmlspecialchars($profile['email']) ?>" required />
				</div>
				<!-- <div class="form-group" class="form-label">
					<label for="password">Mot de Passe</label><br />
					<input type="text" class="form-control" id="password" name="password" value="" />
				</div > -->
				<div class="form-group">
					<label for="photo" class="form-label">Photo</label>
					<input type="file" class="form-control py-1" id="photo" name="photo" />
				</div>
				<?php if((isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1))){ ?>
					<div class="form-group" class="form-label">
						<label for="usertype_id">Rôle Utilisateur</label><br />
						<select class="form-select" name="usertype_id" aria-label="Default select example" required>
							<option selected>choisir le rôle Utilisateur</option>
							<option value="1" <?php if($profile['usertype_id'] == 1){?> selected <?php } ?>>Administrateur</option>
							<!-- <option value="2">Editeur</option> -->
						  <option value="3" <?php if($profile['usertype_id'] == 3){?> selected <?php } ?>>Visiteur</option>
						</select>
					</div >
		
				<div class="form-group my-3">
					<input   type="submit" class="btn btn-info" />
				</div>
			</form>
</div>
    </div>

<?php
}
?>	
  </div>
</div>
</div>
<?php $content = ob_get_clean(); 
require('admintemplate.php');
?>

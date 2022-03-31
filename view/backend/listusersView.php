<?php 
$title = 'Gestion des Utilisateurs';
$USERTYPE = $_SESSION['USERTYPEID'];

if(isset($_SESSION['actionmessage'])) {
	//echo $_SESSION['message'];
	$actionmessage = $_SESSION['actionmessage'];

}
if(isset($_SESSION['alert_flag'])) {
	//echo $_SESSION['message'];
	$alert_flag = $_SESSION['alert_flag'];

}

?>
<?php ob_start(); ?>
<!-- <h1>Mon super blog !</h1> 
<p>Liste des Commentaires</p>
-->

<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    

                    <!-- List of Comments to Validate   -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
							
									<h3 class="m-0 font-weight-bold text-primary">Gestion des Utilisateurs</h3>
							
                        </div>
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
?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead align="center">
                                        <tr>
											<th align="center">Photo</th>
											<th align="center">Id User</th>
                                             <th align="center">Pseudo</th>
											<th align="center">Email</th>
                                            <th align="center">R&ocirc;le</th>
											<th align="center">Date Cr&eacute;ation</th>
                                            <th align="center">Actions</th>
                                            
                                        </tr>
                                    </thead>
                                   
                                    <tbody>


<?php

/************  GET THE LIST OF   COMMENTS  TO BE VALIDATED  *************/

//$commentsvalidate = listCommentsValidate();

/*
if( ! isset($commentsvalidate )){
	//var_dump($action);
	//$commentsvalidate = $_SESSION['COMMENTSVALIDATE'] ;
	$CommentManager = new \OC\PhpSymfony\Blog\Model\CommentManager(); // Création d'un objet
	$commentsvalidate = $CommentManager->getComments(null,'0'); // Appel d'une fonction de cet objet

}
*/
while ($users = $getusers->fetch())
{
	
?>
<tr>
<td width="8%" align="center">
	<a class="nav-link " >
                                
                                <img class="img-profile rounded-circle"  
								src="public/startbootstrap-sb-admin-2-gh-pages/img/undraw_profile.svg">
								<!-- src="uploads/images/<?= htmlspecialchars($users['photo']) ?>">-->
                                    
                            
</td>

<td width="10%" align="center"><?= htmlspecialchars($users['id']) ?></td>
           <td width="13%" align="center"><?= htmlspecialchars($users['pseudo']) ?></td>
		   <td width="10%" align="center"><?= htmlspecialchars($users['email']) ?></td>
           <td width="10%" align="center"><?= $users['usertype'] ?></td>
		   <td width="33%" align="center"><?= $users['creation_date_fr'] ?></td>
			<td width="16%" align="center">
				<?php
						
					//if(isset( $USERTYPE) && ($USERTYPE == 1)){
						if(isset( $users['is_activated']) && ($users['is_activated'] != NULL)){
							$isactivated = "off";
							$color = "danger";
						}else{
							$isactivated = "on";
							$color = "success";
						}
				?>
						<a href="index.php?action=useractivation&amp;id=<?= $users['id'] ?>&amp;email=<?= $users['email'] ?>&amp;isactivated=<?= $isactivated ?>" class="btn btn-outline-<?= $color ?> btn-sm" title="Activer"><i class="fas fa-toggle-<?= $isactivated ?>"></i> </a>
						<a href="index.php?action=myprofile&amp;id=<?= $users['id'] ?>" class="btn btn-outline-info btn-sm mx-1" title="Editer"><i class="fas fa-edit"></i> </a>
						<!-- <i class="fas fa-check"></i> </a> -->
				<?php
					//}		
					

				 ?>
					<a href="#" data-toggle="modal" data-target="#logoutModal<?= $users['id'] ?>" class="btn btn-outline-danger btn-sm " title="Supprimer">
					<i class="fas fa-trash"></i></a></td>
</tr>
   
 <!-- Comment Delete Modal-->
    <div class="modal fade" id="logoutModal<?= $users['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">&Ecirc;tes vous s&ucirc;r ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                       <!--  <span aria-hidden="true">Ã—</span> -->
                    </button>
                </div>
                <div class="modal-body">Cliquer sur  Supprimer pour Confirmer</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-primary" href="index.php?action=userdelete&amp;id=<?= $users['id'] ?>&amp;usertypeid=<?= $users['usertype_id'] ?>">Supprimer</a>
                </div>
            </div>
        </div>
    </div>


<?php
}
//$comment->closeCursor();
?>

                                       
                                  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<?php $content = ob_get_clean(); ?>

<?php require('admintemplate.php'); ?>

<?php 
$title = 'Gestion des Commentaires';
$USERTYPE = $_SESSION['USERTYPEID'];
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

							<?php
								if(isset( $USERTYPE) && ($USERTYPE == 1)){
							 ?>
									<h3 class="m-0 font-weight-bold text-success">Gestion des Commentaires</h3>
							<?php
							}elseif(isset( $USERTYPE) && ($USERTYPE == 3)){
							 ?>
									<h3 class="m-0 font-weight-bold text-success">Mes Commentaires</h3>
							 <?php
							 }
							 ?>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>

                                        <tr><th>Id User</th>
                                            <th>Auteur</th>
                                            <th>Commentaire</th>
											<th>Id Article</th>

                                            <th>Date</th>
                                            <th>Actions</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Auteur</th>
                                            <th>Commentaire</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                            
                                        </tr>
                                    </tfoot> -->
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

while ($comment = $commentsvalidate->fetch())
{
	
?>
<tr>

<td width="8%" align="left"><?= htmlspecialchars($comment['userid']) ?></td>

<td width="8%" align="left"><?= htmlspecialchars($comment['author']) ?></td>
           <td width="35%" align="left"><?= htmlspecialchars($comment['comment']) ?></td>
		   <td width="10%" align="left"><?= htmlspecialchars($comment['post_id']) ?></td>
           <td width="25%" align="center"><?= $comment['comment_date_fr'] ?></td>
			<td width="14%" align="center">
				<?php
						
					if(isset( $USERTYPE) && ($USERTYPE == 1)){
				?>
						<a href="index.php?action=commentvalidate&amp;id=<?= $comment['id'] ?>" class="btn btn-success btn-circle btn-sm mr-lg-1" title="Valider">
						<i class="fas fa-check"></i> </a>
				<?php
					}				
				 ?>
					<a href="#" data-toggle="modal" data-target="#logoutModal<?= $comment['id'] ?>"class="btn btn-outline-danger btn-sm" title="Supprimer">
					<i class="fas fa-trash"></i></a></td>

</tr>
   
 <!-- Comment Delete Modal-->
    <div class="modal fade" id="logoutModal<?= $comment['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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

                    <a class="btn btn-primary" href="index.php?action=commentdelete&amp;idcomment=<?= $comment['id'] ?>">Supprimer</a>

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


<?php ob_start(); ?>

<!-- <h1>Mon super blog !</h1> -->
<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h3 class=" font-weight-bold text-info">Mise à jour  Article</h3><!--< div class="row d-flex flex-row justify-content-center align-items-center"> -->
						<div class="col-lg-12  d-flex flex-row justify-content-center align-items-center "> 
							<?php
						
					//if(isset( $USERTYPE) && ($USERTYPE == 1)){
						if(isset( $post['is_published']) && ($post['is_published'] == '0')){
							$ispublished = "off";
							$color = "danger";
						}else{
							$ispublished = "on";
							$color = "success";
						}
				//var_dump($ispublished); 
//$posts = listPostsUpdate($userid);
//exit;
?>
				<a href="index.php?action=postactivation&amp;id=<?= $post['id'] ?>&amp;ispublished=<?= $ispublished ?>" class="btn btn-outline-<?= $color ?> btn-md" title="Publier"><i class="fas fa-toggle-<?= $ispublished ?>"></i> </a>
								<a href="index.php?action=modifypost&amp;id=<?= $post['id']  ?>"class="btn btn-outline-secondary btn-md mx-3" title="Modifier">
								  <i class="fas fa-edit"></i> 
								</a>
								<a href="#" data-toggle="modal" data-target="#logoutModal<?= $post['id'] ?>" class="btn btn-outline-danger btn-md mx-3" title="Supprimer">
								   <i class="fas fa-trash"></i>
								</a>
								<a href="index.php?action=adminposts">Retour à la liste des Articles</a>
					</div>
					<!-- 	</div> -->
		</div>
		<div class="container justify-content-center">
			<!-- <div class="card card-login mx-auto my-1 px-0 "> -->
				<div class="card-body ">

<!-- <h1>Mon super blog !</h1> -->
					
					
					<div class="row">
	
							<div class="col mb-5">
								<h4><?= htmlspecialchars($post['title']) ?>	</h4>
										<em>par <?= $post['author'] ?> le <?= $post['creation_date_fr'] ?></em>
								
								<div class="profile-card mb-lg-4 justify-content-left">
								<div class=".profile-content justify-content-left">
									<div class="profile-img mx-3 my-3 ">
										<IMG SRC="uploads/images/<?= htmlspecialchars($post['image']) ?>"   BORDER=0 ALT="">
									</div>
									<div class="news px-2 my-3">
										<div class="font-weight-bold">
											<?= htmlspecialchars($post['lede']) ?>	
										</div>
					
										<p>
											<?= nl2br(htmlspecialchars($post['content'])) ?>
										</p>
									</div>

							</div>
							</div>
					</div>
					</div>
					<div class="card-footer py-3">
					<!-- --><div class="row justify-content-center">
					<div class="col-lg-12  d-flex flex-row justify-content-center align-items-center "> 
					<?php	//if(isset( $USERTYPE) && ($USERTYPE == 1)){
						if(isset( $post['is_activated']) && ($post['is_activated'] != NULL)){
							$isactivated = "off";
							$color = "danger";
						}else{
							$isactivated = "on";
							$color = "success";
						}
				?>
						<a href="index.php?action=postactivation&amp;id=<?= $post['id'] ?>" class="btn btn-outline-<?= $color ?> btn-md mx-3" title="Activer"><i class="fas fa-toggle-<?= $isactivated ?>"></i> </a>
						<a href="index.php?action=modifypost&amp;id=<?= $post['id']  ?>"class="btn btn-outline-secondary btn-md mx-3" title="Modifier">
						  <i class="fas fa-edit"></i> 
						</a>
						<a href="#" data-toggle="modal" data-target="#logoutModal<?= $post['id'] ?>" class="btn btn-outline-danger btn-md mx-3" title="Supprimer">
						   <i class="fas fa-trash"></i>
						</a>
						<a href="index.php?action=adminposts">Retour à la liste des billets</a>
					</div>
				<!-- </div> -->
			</div>
		<!----> </div> 
	</div>
	
<!--  --></div>

<?php $content = ob_get_clean(); 
require('admintemplate.php');
?>

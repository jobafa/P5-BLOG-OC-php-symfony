<?php 
$cleanobject = new \Inc\Clean();

while ($data = $posts->fetch())
{

$texte=nl2br($cleanobject->escapeoutput($data['content']));
$texte = substr($texte, 0, 150);
?>
<div class="container">
<div class="row">
		<div class="profile-card shadow-bluedev mb-lg-3 justify-content-left">
				<div class="profile-card-header mt-2">
					<h5 class="text-capitalize text-bluedev">
							<?= $cleanobject->escapeoutput($data['title']) ?>
							
					</h5>
				</div>
<div class="row">
				<div class="col-md-6 col-lg-4 mx-2 mb-3">
						<div class="profile-img my-3">
							<IMG SRC="uploads/images/<?= $cleanobject->escapeoutput($data['image']) ?>"  width="320" height="250" BORDER=0 ALT="">
						</div>
				</div>
				<div class="col-md-6 col-lg-4 mx-5 my-3 justify-content-center">
				<h5 class="text-capitalize text-bluedev">
							<?= $cleanobject->escapeoutput($data['title']) ?>
							
					</h5>
												<em class="text-md-start text-muted mb-3">Par <?= $cleanobject->escapeoutput($data['author']) ?><BR>le <?= $data['update_date_fr'] ?> </em>

				</div>
</div>

				<div class="news col-md-6 col-lg-8 mb-3">
					
					<h6>
						<?= $cleanobject->escapeoutput($data['lede']) ?>
						
					</h6>
					
					<p class=" mb-1">
						<?= $texte ?>....
						
						
					
					</p><em class=" mb-1"><a  class="btn btn-bluedev btn-md" href="frontpost-<?= escapeoutput($data['id']) ?>-post-<?= $page ?>.html?#posts">Lire la suite</a></em>
				</div>
		</div>
	<!-- Pagination -->
	
</div>
</div>
<?php
}

$posts->closeCursor();

?>
<div class="row mt-5">
        <nav aria-label="Page navigation example mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link"
                        href="<?php if($page <= 1){ echo '#'; } else { echo "listposts-front-" . $prev; } ?>-post.html#posts">Précédent</a>
                </li>

                <?php for($i = 1; $i <= $totoalPages; $i++ ): ?>
                <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                    <a class="page-link" href="listposts-front-<?= $i; ?>-post.html#posts"> <?= $i; ?> </a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
				 <a class="page-link"
                        href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "listposts-front-". $next; } ?>-post.html#posts">Suivant</a>
                   
                </li>
            </ul>
        </nav>
	</div>


<?php
$title = "Gestion Erreurs";
ob_start(); ?>
<div class="container">
    <div class="card card-login mx-auto my-3 px-0">
      <div class="card-body">
		<div class="alert  text-danger my-2 alert-dismissible fade show" role="alert">
			
		<?php
		    echo $content =  'Erreur : ' . $e->getMessage();
		?>
        </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean(); 
require'view/frontend/template.php';
 ?>
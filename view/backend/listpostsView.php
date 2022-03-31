<?php $title = 'Mon blog';
//echo $action.' '.$_SESSION['actionmessage'].' '.$_SESSION['alert_flag'];
//exit;
if(isset($_SESSION['actionmessage'])) {
	//echo $_SESSION['message'];
	$actionmessage = $_SESSION['actionmessage'];

}
if(isset($_SESSION['alert_flag'])) {
	//echo $_SESSION['message'];
	$alert_flag = $_SESSION['alert_flag'];

}else {
	$alert_flag = '';
}
/*if(isset($_SESSION['updatemessage'])) {
	//echo $_SESSION['message'];
	$actionmessage = $_SESSION['updatemessage'];
}
*/
//echo $alert_flag.' '.$actionmessage;
//exit;

?>
<?php ob_start(); ?>
<!-- <h1>Mon super blog !</h1> -->
<p>Liste des Articles</p>

<?php
	//if(isset($_GET['message']) && !empty($_GET['message'])){
		if ($alert_flag == 0){
			//$affichage = "Deleting post issue !";
			$classe = "alert-danger";
		}else if ($alert_flag == 1){
			//$affichage = "Success ! Post was Deleted !";
			$classe = "alert-success";
		}
    //}
//echo  $actionmessage ;
if(isset($actionmessage) && (($alert_flag == 0 || $alert_flag == 1))) {
?>
<div class="alert <?= $classe ?>" role="alert">
  <?= $actionmessage ?>
</div>
<?php

}
if(isset($_SESSION['actionmessage'])) unset($_SESSION['actionmessage']);
if(isset($_SESSION['alert_flag'])) unset($_SESSION['alert_flag']);
?>
<table border="1">
  <!--  <caption>Passagers du vol 377</caption> -->

   <thead align="center"> <!-- En-tête du tableau -->
       <tr>
           <th align="center">Titre Article</th>
           <th align="center">Auteur</th>
           <th align="center">Date</th>
		    <th align="center">Actions</th>
       </tr>
   </thead>
   
   <tbody > <!-- Corps du tableau -->
      

<?php
while ($data = $posts->fetch())
{
?>
 <tr>
 <td><?= htmlspecialchars($data['title']) ?></td>
           <td align="center"><?= htmlspecialchars($data['author']) ?></td>
           <td align="center"><?= $data['creation_date_fr'] ?></td>
			<td align="center"><em><a href="index.php?action=modifypost&amp;id=<?= $data['id'] ?>">Modifier</a></em>&nbsp;&nbsp;&nbsp;<em><!-- <a href="index.php?action=deletepost&amp;id=<?= $data['id'] ?>"> --><a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Supprimer</a></em></td>
</tr>
   
<?php
}
$posts->closeCursor();
?>
  


       
    
   </tbody>
</table>


<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>

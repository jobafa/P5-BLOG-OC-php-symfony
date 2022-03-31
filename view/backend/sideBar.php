  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <!-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a> -->

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de Bord</span></a>
            </li>

            
  

            <!-- Divider -->
            <hr class="sidebar-divider">


<li class="nav-item">
              <!--   <a class="nav-link collapsed" href="index.php?action=myprofile" data-toggle="collapse" data-target="#collapseProfile" aria-expanded="true" aria-controls="collapseComments"> -->
				<!-- --><a class="nav-link collapsed" href="index.php?action=myprofile" > 
          <i class="fas fa-fw fa-folder"></i>
          <span>Mon Profile</span>
        </a>
         <!-- <div id="collapseComments" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
           <h6 class="collapse-header">Custom Utilities:</h6> 
             <a class="collapse-item" href="index.php?action=mycomments">Mes Commentaires</a>-->
          <!--  <a class="collapse-item" href="utilities-border.html">Borders</a>
            <a class="collapse-item" href="utilities-animation.html">Animations</a>
            <a class="collapse-item" href="utilities-other.html">Other</a>
          </div>
        </div> -->
          </li>
<?php
if(isset($USERTYPEID) && ($USERTYPEID == 1)){

?>
 <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                  <i class="fas fa-fw fa-folder"></i>
          <span>Utilisateurs</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
            <a class="collapse-item text-info" href="index.php?action=adduserview">Ajout Utilisateur</a>
                    
            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
            <a class="collapse-item text-info" href="index.php?action=usersadmin">Gestion Utilisateurs</a>
                    </div>
                </div>
            </li>
            <!-- Heading -->
            <!-- <div class="sidebar-heading">
                Addons
            </div> -->

            <!-- Nav Item - Pages Collapse Menu -->
            
			 <li class="nav-item">
                <a class="nav-link collapsed" href="index.php?action=adminposts" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapseUtilities">
                  <i class="fas fa-fw fa-folder"></i>
          <span>Articles</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
		<div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
            <a class="collapse-item text-primary" href="index.php?action=addpostview">Ajout Article</a>
                    
            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
            <a class="collapse-item text-primary" href="index.php?action=adminposts">Gestion Articles</a>
			<a class="collapse-item text-primary" href="index.php?action=adminmyposts">Mes Articles</a>
                    </div>
          
                </div>
            </li>
			<li class="nav-item">
                <a class="nav-link collapsed" href="index.php?action=commentsadmin" data-toggle="collapse" data-target="#collapseComments" aria-expanded="true" aria-controls="collapseComments"> <!-- -->
				<!-- <a class="nav-link collapsed" href="index.php?action=commentsadmin" > -->
          <i class="fas fa-fw fa-folder"></i>
          <span>Commentaires</span>
        </a>
         <div id="collapseComments" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!--<h6 class="collapse-header">Custom Utilities:</h6> -->
             <a class="collapse-item" href="index.php?action=commentsadmin">Gestion Commentaires</a>
          <!--  <a class="collapse-item" href="utilities-border.html">Borders</a>
            <a class="collapse-item" href="utilities-animation.html">Animations</a>
            <a class="collapse-item" href="utilities-other.html">Other</a>-->
          </div>
        </div> 
          </li>
			
<?php

}elseif (isset($USERTYPEID) && ($USERTYPEID ==3)){
?>			
		<li class="nav-item">
                <a class="nav-link collapsed" href="index.php?action=commentsadmin" data-toggle="collapse" data-target="#collapseComments" aria-expanded="true" aria-controls="collapseComments"> <!-- -->
				<!-- <a class="nav-link collapsed" href="index.php?action=commentsadmin" > -->
          <i class="fas fa-fw fa-folder"></i>
          <span>Commentaires</span>
        </a>
         <div id="collapseComments" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!--<h6 class="collapse-header">Custom Utilities:</h6> -->
             <a class="collapse-item" href="index.php?action=mycomments">Mes Commentaires</a>
          <!--  <a class="collapse-item" href="utilities-border.html">Borders</a>
            <a class="collapse-item" href="utilities-animation.html">Animations</a>
            <a class="collapse-item" href="utilities-other.html">Other</a>-->
          </div>
        </div> 
          </li>
	<?php

}
?>				

         
        </ul>
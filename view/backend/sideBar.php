<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
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
    <a class="nav-link collapsed" href="index.php?action=myprofile&controller=useradmin" > 
        <i class="fas fa-fw fa-folder"></i>
        <span>Mon Profile</span>
    </a>
      
  </li>       
  <?php
  if( $this->is_Logged() && $this->is_Admin()){
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
        <a class="collapse-item text-info" href="index.php?action=adduserview&controller=user">Ajout Utilisateur</a>
        <a class="collapse-item text-info" href="index.php?action=usersadmin&controller=useradmin">Gestion Utilisateurs</a>
      </div>
    </div>
  </li>
  <!-- Nav Item - Pages Collapse Menu -->
  
  <li class="nav-item">
    <a class="nav-link collapsed" href="index.php?action=adminposts" data-toggle="collapse" data-target="#collapsePages"
      aria-expanded="true" aria-controls="collapseUtilities">
      <i class="fas fa-fw fa-folder"></i>
      <span>Articles</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item text-primary" href="index.php?action=addpostview&controller=postadmin">Ajout Article</a>
        <a class="collapse-item text-primary" href="index.php?action=adminposts&controller=postadmin">Gestion Articles</a>
        <a class="collapse-item text-primary" href="index.php?action=adminmyposts&controller=postadmin">Mes Articles</a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="index.php?action=commentsadmin" data-toggle="collapse" data-target="#collapseComments" aria-expanded="true" aria-controls="collapseComments"> <!-- -->
      <i class="fas fa-fw fa-folder"></i>
      <span>Commentaires</span>
    </a>
    <div id="collapseComments" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="index.php?action=commentsadmin&controller=commentadmin">Gestion Commentaires</a>
      </div>
    </div> 
  </li>
  <?php
  }elseif( $this->is_Guest()){
  ?>			
  <li class="nav-item">
    <a class="nav-link collapsed" href="index.php?action=commentsadmin" data-toggle="collapse" data-target="#collapseComments" aria-expanded="true" aria-controls="collapseComments"> <!-- -->
      <i class="fas fa-fw fa-folder"></i>
      <span>Commentaires</span>
    </a>
    <div id="collapseComments" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="index.php?action=mycomments&controller=commentadmin">Mes Commentaires</a>
      </div>
    </div> 
  </li>
  <?php
  }
  ?>				
</ul>
         
        

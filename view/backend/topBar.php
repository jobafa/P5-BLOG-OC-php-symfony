  <?php

  // IF NO USER PICTURE USE DEFAULT PICTURE

	  if( isset($_SESSION['PHOTO']) && ($_SESSION['PHOTO']  != NULL ) ){

		  $photo = $_SESSION['PHOTO'];

  }else{
		 $photo = "undraw_profile.svg";
  }

  
  // IF USER IS LOGGED USE THE WRITE ACTION ACCORDING TO THE USER TYPE ID

if(isset($_SESSION['USERTYPEID']) ){
	if($_SESSION['USERTYPEID'] == 1){
		$useraction = "adminposts";
	}elseif($_SESSION['USERTYPEID'] == 3){
		$useraction = "mycomments";
	}
}
  ?>
	<!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
				
				<nav class="navbar navbar-expand navbar-light bg-primary topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                   
                    <ul class="navbar-nav ml-auto">

                       

                     

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link " href="listposts-front.html?#posts" id="userDropdown">
                                <span class="mr-2 d-none d-lg-inline text-white-600 meduim">Aller sur le Site</span>
                              
                            </a>
                           
                        </li>

                    </ul>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                       

                     

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-white-600 meduim"><?= htmlspecialchars($_SESSION['PSEUDO']) ?></span>
                                <img class="img-profile rounded-circle mx-1" 
                                    src="uploads/images/<?= htmlspecialchars($photo) ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="index.php?action=myprofile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="index.php?action=<?= $useraction ?>&from=dropdown">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Tableau de Bord
                                </a>
                              
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Se Deconnecter
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
           
<?php


if((isset($USERTYPEID) && ($USERTYPEID == 1))){
	?>        
                    <!-- Content Row -->
                    <div class="row justify-content-center">

                        <!-- ARTICLES -->
                        <div class="col-xl-4 col-lg-6 mb-2">
						<a style="text-decoration:none" href="index.php?action=adminposts" >
                            <div class="card border-left-primary shadow h-70 py-2">
                                
								<div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                 Gestion des Articles
											</div>
                                            <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div> -->
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-edit fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>

                        <!-- COMMENTAIRES -->
                        <div class="col-xl-4 col-lg-6 mb-2">
						<a style="text-decoration:none"  href="index.php?action=commentsadmin" >
                            <div class="card border-left-success shadow h-70 py-2">
                                
								<div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Gestion des Commentaires
											</div>
                                            <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div> -->
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>

						<!-- UTILISATEURS -->
                        <div class="col-xl-4 col-lg-6 mb-2">
						<a style="text-decoration:none" href="index.php?action=usersadmin" >
                            <div class="card border-left-primary shadow h-70 py-2">
                                
								<div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                 Gestion des Utilisateurs
											</div>
                                            <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div> -->
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-edit fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>

                       
                    <!-- Content Row -->
<?php
		}
		?>
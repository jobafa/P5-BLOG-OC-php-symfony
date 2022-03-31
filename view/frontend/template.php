<?php require('header.php'); 
 // IF NO USER PICTURE USE DEFAULT PICTURE

	  if( isset($_SESSION['PHOTO']) && (null !== $_SESSION['PHOTO']) ){
		  $photo = $_SESSION['PHOTO'];

  }else{
		 $photo = "undraw_profile.svg";
  }



?>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light  bg-white fixed-top " id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="accueil.html"><IMG SRC="public/images/LOGO-BLOG.png" width="160" height="80" ALT=""></a>
                 <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto  ">
						<!-- <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="home.php">Accueil</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="index.php?action=listposts&from=front">Blog</a></li> -->
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link  px-0 px-lg-3 rounded" href="accueil.html">Accueil</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link  px-0 px-lg-3 rounded" href="listposts-front.html#posts">Blog</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link  px-0 px-lg-3 rounded" href="accueil.html#contact">Contact</a></li>
                    </ul>
                </div>



				<!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                       

                     

                        <div class="topbar-divider d-none d-sm-block"></div>
<?php
  // IF USER IS LOGGED USE THE WRITE ACTION ACCORDING TO THE USER TYPE ID

if(isset($_SESSION['USERTYPEID']) ){
	if($_SESSION['USERTYPEID'] == 1){
		$useraction = "adminposts";
	}elseif($_SESSION['USERTYPEID'] == 3){
		$useraction = "mycomments";
	}
	?>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= htmlspecialchars($_SESSION['PSEUDO']) ?></span>
                                <img class="user_snippet_small_profile_image_rounded_geo mx-1"
								src="uploads/images/<?= htmlspecialchars($photo) ?>">
                                   <!-- src="public/startbootstrap-sb-admin-2-gh-pages/img/undraw_profile.svg"> -->
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="index.php?action=myprofile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
								 <?php
	//if((isset($_SESSION['USERTYPEID']) && ($_SESSION['USERTYPEID'] == 1))){?>
								 <a class="dropdown-item" href="index.php?action=<?= $useraction ?>&from=dropdown">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Tableau de Bord
                                </a>
								<?php
								//}?>
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Se Deconnecter
                                </a>
                            </div>
                        </li>

                    
<?php
		}else{
		?>
		<!-- <li class="nav-item dropdown no-arrow"><a class="nav-link py-3 px-0 px-lg-3 " href="index.php?action=loginview"><i class="fas fa-user-alt  mx-2 text-gray-400"></i>Se Connecter</a></li> -->
		<li class="nav-item dropdown no-arrow"><a class="nav-link py-3 px-0 px-lg-1 " href="loginview.html#login"><i class="fas fa-user-alt  mx-2  text-gray-400"></i><small>Se Connecter</small></a></li>
		<li class="nav-item dropdown no-arrow"><a class="nav-link py-3 px-0 px-lg-1 " href="signinview.html#inscription"><i class="fa fa-sign-in-alt mx-2 text-gray-400"></i></i><small>S'inscrire</small></a></li>

		<?php
		}
		?>
               </div>  
			   </ul>
                <!-- End of Topbar -->
				
           
        </nav>

		</div>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-1 h-100">
                <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end">
                        <h1 class="text-white font-weight-bold">Fathi abderrahim</h1>
                        <hr class="divider" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 mb-5">Conception & Développement de Sites Web</p>
                        <!-- <a class="btn btn-bluedev btn-xl" href="#about">En Savoir Plus</a> -->
                    </div>
                </div>
            </div>
        </header>
        <!-- Portfolio Section-->

        <section class="page-section portfolio" id="portfolio">
<div class="container"  id="posts">
                <!-- Portfolio Section Heading-->
                <h2 class="page-section-heading text-center text-capitalize text-bluedev mb-0">Mon Blog Formation</h2>
                <!-- Icon Divider-->
                <!-- <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div> -->
                <!-- Portfolio Grid Items-->
                <div class="row justify-content-center">
                    <!-- Portfolio Item 1-->
                    <div class="col-md-6 col-lg-10 mb-5">
  <?= $content ?>
</div>
			</div>
  </section>

  <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">&Ecirc;tes vous s&ucirc;r ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <!-- <span aria-hidden="true">Ã—</span> -->
                    </button>
                </div>
                <div class="modal-body">Cliquer sur Se Déconnecter pour vous déconnecter</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-primary" href="index.php?action=userlogout">Se Déconnecter</a>
                </div>
            </div>
        </div>
    </div>

<?php require('footer.php'); ?>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS
        <script src="public/js/scripts.js"></script>-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

		<!-- TEMPLATE ADMIN -->
		 <!-- Bootstrap core JavaScript-->
    <script src="public/startbootstrap-sb-admin-2-gh-pages/vendor/jquery/jquery.min.js"></script>
    <script src="public/startbootstrap-sb-admin-2-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="public/startbootstrap-sb-admin-2-gh-pages/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="public/startbootstrap-sb-admin-2-gh-pages/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="public/startbootstrap-sb-admin-2-gh-pages/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="public/startbootstrap-sb-admin-2-gh-pages/js/demo/chart-area-demo.js"></script>
    <script src="public/startbootstrap-sb-admin-2-gh-pages/js/demo/chart-pie-demo.js"></script>
    </body>
</html>

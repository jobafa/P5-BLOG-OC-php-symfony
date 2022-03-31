<?php
if( ! isset($_SESSION) ) session_start();
$USERTYPEID = $_SESSION['USERTYPEID'] ?? NULL ;
//ECHO $USERTYPEID;
if($USERTYPEID == NULL){
	header('Location: loginview.html');
}
$title = '';
//echo $_SESSION['ACTION'] ;
//require('config/config.php');
//require('controller/frontend.php');
// Chargement des classes


//require_once('model/PostManager.php');
//echo 'ENTRE '.$_SESSION['PSEUDO'].'  '.$_SESSION['RESULT']['email'];
//$USERTYPEID = $_SESSION['USERTYPEID'];
//echo $USERTYPE_ID.' id';
//exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

     <title><?= $title ?></title>

    <!-- Custom fonts for this template-->
    <link href="public/startbootstrap-sb-admin-2-gh-pages/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
  <link href="public/startbootstrap-sb-admin-2-gh-pages/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="public/startbootstrap-sb-admin-2-gh-pages/css/sb-admin-2.min.css" rel="stylesheet">
  <!-- <link href="public/css/styles.css" rel="stylesheet" /> -->
  <style>
 
.full-width-image {
   width: 100vw;
   position: relative;
   left: 50%;  
   margin-left: -50vw;
}



.profile-card{
	
	
            position:relative;
            overflow: hidden;
            margin-bottom:10px;
			top:25px;
            box-shadow:0px 2px 3px #333;
            
			/*border-radius: 1px;*/
        }
        .profile-card:hover .profile-img img
        {
            /*transform: scale(1.1);*/
			border-radius: 1px;
        }

		
        .profile-img img{
            max-width:700px;
            
			object-fit: cover;
            transition: transform 1s;
			/*border:1px solid #333;
			border-radius: 1px;*/
        }

/*@media (min-width: 1400px) {
 .profile-card{
	
	min-width:325px;
            min-height: 315px;
            
        }
  .profile-card .profile-img img{
            min-width:325px;
            min-height: 315px;
			
        }
}*/

    
        
/*  FIN AJOUT DU 22 NOVEMBRE PROFILE HOVER EFFECTS*/
	/* AJOUT POERSO */

/*.fa-toggle-on {
	color: #64a19d;
}

.fa-toggle-off {
	color: red;
}

.fa-trash {
	color: red;
}*/

  </style>

	 <!-- <link href="public/css/style.css" rel="stylesheet">
 -->
	 <!-- jquery -->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
	
	<!-- Sidebar -->
<?php


//if((isset($USERTYPEID) && ($USERTYPEID == 1))){
	
        
         require('sideBar.php');
		
		//}
		?>
        <!-- End of Sidebar -->

     
	   <?php
			require('topBar.php');
	   ?>

          <?= $content ?>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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
<!DOCTYPE html>
<html lang="FR">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title><?= $title?></title>

<!-- Custom fonts for this template-->
<link href="public/startbootstrap-sb-admin-2-gh-pages/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
<link href="public/startbootstrap-sb-admin-2-gh-pages/fontawesome-free/css/all.min.css" rel="stylesheet">

<!-- Custom styles for this template-->
<link href="public/startbootstrap-sb-admin-2-gh-pages/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- jquery -->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
	
        <!-- Sidebar -->
        <?php
        require('sideBar.php');
        ?>
        <!-- End of Sidebar -->

        <?php
        require('topBar.php');
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
        <?php
        
        if( $this->is_Logged() && $this->is_Admin()){	
        
        ?> 
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-info">Gestion Des Articles</h3>
                    <?php
                    // CALL TO FUNCTION is_alertMessage() TO CHECK IF WE HAVE AN ALERT MESSAGE

                    $message = $messageDisplay->is_alertMessage();

                    if (($message) && ($message != "")){

                        echo $message;

                        SessionManager::getInstance()->sessionvarUnset('actionmessage');
                        SessionManager::getInstance()->sessionvarUnset('alert_flag');
                    }
                    ?>
                </div>
						
                <div class="card-body">
    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead align="center">
                                <tr>
                                <!-- <th align="center">ID</th> -->
                                    <th align="center">Photo</th>
                                    <th align="center">ch&acirc;po</th>
                                    <th align="center">Date</th>
                                    <th align="center">Actions</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($posts as $data) {
                                    if($data['image'] === Null){
                                        $photo = "undraw_profile.svg";
                                    }else{
                                        $photo = $data['image'];
                                    }
                                ?>
                                <tr >
                                    <td width="5%" align="center">
                                        <img class="img-profile rounded-circle"  src="uploads/images/<?= $cleanobject->escapeoutput($photo) ?>" width="40" height= 40 >
                                    </td>
                                    <td width="42%" align="left"><?= $cleanobject->escapeoutput($data['lede']) ?></td>
                                    <td width="28%" align="center"><?= $data['update_date_fr'] ?></td>
                                    <td width="25%" align="center">
                                    <?php
                                    if(isset( $data['is_published']) && ($data['is_published'] == '0')){
                                        $ispublished = "off";
                                        $color = "danger";
                                    }else{
                                        $ispublished = "on";
                                        $color = "success";
                                    }
                                    ?>
                                        <a href="index.php?action=postactivation&amp;controller=postadmin&amp;id=<?= $cleanobject->escapeoutput($data['id']) ?>&amp;ispublished=<?= $ispublished ?>" class="btn btn-outline-<?= $color ?> btn-sm" title="Publier">
                                            <i class="fas fa-toggle-<?= $ispublished ?>"></i> 
                                        </a>
                                        <a href="index.php?action=post&amp;controller=post&amp;id=<?= $cleanobject->escapeoutput($data['id'])?>" class="btn btn-outline-info btn-sm mx-1" title="Voir">
                                            <i class="fas fa-eye"></i> 
                                        </a>
                                        <a href="index.php?action=modifypost&amp;controller=postadmin&amp;id=<?= $cleanobject->escapeoutput($data['id'])  ?>"class="btn btn-outline-secondary btn-sm mx-1" title="Editier">
                                            <i class="fas fa-edit"></i> 
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#deletemodal<?= $cleanobject->escapeoutput($data['id']) ?>" class="btn btn-outline-danger btn-sm mx-1" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- post Delete Modal-->
                                <div class="modal fade" id="deletemodal<?= $cleanobject->escapeoutput($data['id']) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">&Ecirc;tes vous s&ucirc;r ?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <!-- <span aria-hidden="true">Ã—</span> -->
                                                </button>
                                            </div>
                                            <div class="modal-body">Cliquer sur  Supprimer pour Confirmer </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                                <a class="btn btn-primary" href="index.php?action=deletepost&amp;controller=postadmin&amp;id=<?= $cleanobject->escapeoutput($data['id']) ?>">Supprimer</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>

                                       
                                  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <?php
        }else{
        ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-success">Mes Commentaires</h3>
                    
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; CapWeb 2022</span>
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
                    <!--  <span aria-hidden="true">Ã—</span> -->
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
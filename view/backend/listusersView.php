<?php 

 ob_start(); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    
    <!-- List of Comments to Validate   -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            
                    <h3 class="m-0 font-weight-bold text-primary">Gestion des Utilisateurs</h3>
            
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead align="center">
                        <tr>
                            <th align="center">Photo</th>
                            <th align="center">Id User</th>
                                <th align="center">Pseudo</th>
                            <th align="center">Email</th>
                            <th align="center">R&ocirc;le</th>
                            <th align="center">Date Cr&eacute;ation</th>
                            <th align="center">Actions</th>
                            
                        </tr>
                    </thead>
                    
                    <tbody>


                        <?php

                        /************  GET THE LIST OF   COMMENTS  TO BE VALIDATED  *************/

                        if(($this->is_Logged()) && ($this->is_Admin())){

                        while ($users = $getusers->fetch())
                        {
                            if($users['photo'] == Null){
                                $photo = "undraw_profile.svg";
                            }else{
                                $photo = $users['photo'];
                            }
                            
                        ?>
                        <tr>
                            <td width="8%" align="center">
                                                                                           
                                    <img class="img-profile rounded-circle"  src="uploads/images/<?= $cleanobject->escapeoutput($photo) ?>" width="40" height= 40 >
                                    <!--src="public/startbootstrap-sb-admin-2-gh-pages/img/undraw_profile.svg">-->
                                                     
                            </td>

                            <td width="10%" align="center"><?= $cleanobject->escapeoutput($users['id']) ?></td>
                            <td width="13%" align="center"><?= $cleanobject->escapeoutput($users['pseudo']) ?></td>
                            <td width="10%" align="center"><?= $cleanobject->escapeoutput($users['email']) ?></td>
                            <td width="10%" align="center"><?= $cleanobject->escapeoutput($users['usertype']) ?></td>
                            <td width="33%" align="center"><?= $users['creation_date_fr'] ?></td>
                            <td width="16%" align="center">
                                <?php
                                        
                                //if(isset( $USERTYPE) && ($USERTYPE == 1)){
                                if(isset( $users['is_activated']) && ($users['is_activated'] != NULL)){
                                    $isactivated = "off";
                                    $color = "danger";
                                }else{
                                    $isactivated = "on";
                                    $color = "success";
                                }
                                ?>
                                <a href="index.php?action=useractivation&amp;controller=useradmin&amp;id=<?= $users['id'] ?>&amp;email=<?= $cleanobject->escapeoutput($users['email']) ?>&amp;isactivated=<?= $isactivated ?>" class="btn btn-outline-<?= $color ?> btn-sm" title="Activer">
                                    <i class="fas fa-toggle-<?= $isactivated ?>"></i>
                                </a>
                                <a href="index.php?action=myprofile&amp;id=<?= $cleanobject->escapeoutput($users['id']) ?>&amp;controller=useradmin" class="btn btn-outline-info btn-sm mx-1" title="Editer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- <i class="fas fa-check"></i> </a> -->
                                <?php
                                    //}		
                                    

                                ?>
                                <a href="#" data-toggle="modal" data-target="#logoutModal<?= $cleanobject->escapeoutput($users['id']) ?>" class="btn btn-outline-danger btn-sm " title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        
                        <!-- Comment Delete Modal-->
                        <div class="modal fade" id="logoutModal<?= $cleanobject->escapeoutput($users['id']) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">&Ecirc;tes vous s&ucirc;r ?</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <!--  <span aria-hidden="true">Ã—</span> -->
                                        </button>
                                    </div>
                                    <div class="modal-body">Cliquer sur  Supprimer pour Confirmer</div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <a class="btn btn-primary" href="index.php?action=userdelete&amp;id=<?= $cleanobject->escapeoutput($users['id']) ?>&amp;usertypeid=<?= $users['usertype_id'] ?>&amp;controller=useradmin">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php
                        }
                        }
                        
                        ?>

                                       
                                  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
                <!-- /.container-fluid -->
<?php $content = ob_get_clean(); ?>

<?php require'admintemplate.php'; ?>

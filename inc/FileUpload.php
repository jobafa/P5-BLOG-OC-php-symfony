<?php

namespace Inc;

use Inc\SessionManager;
use Inc\MessageDisplay;

/**
 * Class FileUpload
 * checks and validate uploaded file 
 */
class FileUpload
{
        /*****************************************
    #CHECK UPLOAD : IF OK MOVE TO UPLOADS FOLDER
    @PARAM : $image
    *****************************************/

    public function addImage($image, $id = 0){

        if (isset($image) && ($image['error'] == 0))
            {

                $allowedextensions = array(	"png","jpg","jpeg");
                
                // Get image file extension
                $file_extension = pathinfo($image["name"], PATHINFO_EXTENSION);
                            
                // Validate file input to check if is with valid extension
                if (! in_array($file_extension, $allowedextensions)) {

                    SessionManager::getInstance()->set('actionmessage',  'Echec de l\'upload : Extension non authoris&eacute; !');
                    SessionManager::getInstance()->set('alert_flag',  0);
                    
                }elseif ($image["size"] > 2000000) {// Validate  file size
                    
                    SessionManager::getInstance()->set('actionmessage',  'Taille du fichier superieur &agrave; 2 Mo'); // ERROR MESSAGE
                    SessionManager::getInstance()->set('alert_flag',  0);
                    
                }else {

                    $dossier = "uploads/images/" ;
                    $file_name = basename($image["name"]);
                    $target = $dossier.$file_name;

                    if (file_exists($target)) {// RENAME FILE IF EXISTS IN TARGET
                        
                        $timestamp=time();
                        $file_name = $timestamp.'-'.$file_name;
                        $target = $dossier.$file_name;

                    }
                   
                    // MOVE UPLOADED FILE TO UPLOADS DIRECTORY

                    if (move_uploaded_file($image["tmp_name"], $target)) {
                        return $file_name;
                    } else {

                        return false;
                        
                    }
                }

                if (null !== SessionManager::getInstance()->get('alert_flag')){
                    $action = SessionManager::getInstance()->get('ACTION');

                        switch ($action) {

                                    case 'updatepost':

                                       
                                        \Http::redirect(' index.php?action=modifypost&controller=postadmin&id=' . $id);

                                        break;
                                    case 'myprofile':
                                        
                                        
                                        \Http::redirect(' index.php?action=myprofile&controller=useradmin&id=' . $id);
                                        
                                        break;

                                    case 'adduserview':
                                    
                                       
                                        \Http::redirect(' index.php?action=myprofile&controller=useradmin&id=' . $id);
                                    
                                    break;

                                    case 'usersignin':
                                    
                                        \Http::redirect('signinview-user.html#inscription');
                                    
                                    break;

                                    default:
                                                    
                                    break;    
                                    
                        }


                    
                    
                }else{
                    
                }
            
            }
    }

    //**************************************************************************//
    //*Cheks if upload is ok returns the uploaded file if no error*/
    /*if not initiates the alert message var*/
    //*@param $status,$image,$id
    /* @return  string**/

    public function checkUploadStatus($status, $image, $id = 0){

        
            switch ($status) {

                case UPLOAD_ERR_OK:

                    $photo = $this->addImage($image, $id);
                    return $photo;

                    break;

                case UPLOAD_ERR_NO_FILE:

                    if((SessionManager::getInstance()->get('ACTION') !== 'userupdate') 
                    && (SessionManager::getInstance()->get('ACTION') !== 'updatepost')){

                        $photo = 'undraw_profile.svg';                        
                        return $photo;
                    }else{
                        $photo = SessionManager::getInstance()->get('photo');
                        return $photo;
                    }
                    
                    break;

                case UPLOAD_ERR_INI_SIZE:

                    SessionManager::getInstance()->set('actionmessage',  "Erreur : la taille du Fichier uploadé dépasse la taille spécifié dans le  php.ini !");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Erreur : la taille du Fichier uploadé dépasse la taille spécifié par le formulaire! ");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    
                    break;
                case UPLOAD_ERR_PARTIAL:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Erreur : Fichier partiellement uploadé ! ");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    break;
                case UPLOAD_ERR_NO_FILE:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Erreur : Aucun fichier uploadé ! ");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Missing a temporary folder");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Erreur d\'écriture sur lr disque !");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    break;
                case UPLOAD_ERR_EXTENSION:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Echec de l\'upload :Extension non authoris&eacute; !");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    break;

                default:
                    
                    SessionManager::getInstance()->set('actionmessage',  "Erreur Upload !");
                    SessionManager::getInstance()->set('alert_flag',  0);
                    break;


            }// END OF SWITCH

            return false;	
                    
                        
        //} // END OF ULOADS ERRORS CHECKING
    }
}


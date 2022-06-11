<?php

namespace Inc;
use Inc\SessionManager;

class MessageDisplay 
{
        
    
    
    # **************
    # Initialize Display Action Message
    # @Param $action : user's action 
    # @Param $result : user's action result
    # **************



    public function initmessage($action,$result) {
        
        switch ($action) {
        
        case 'addcomment':
            if (! $result) {
                SessionManager::getInstance()->set('actionmessage', 'Echec d\'ajout Commentaire !');
                SessionManager::getInstance()->set('alert_flag', 0);
                
            }
            else {
                SessionManager::getInstance()->set('actionmessage','Votre Commentaire a été enregisté et sera publié après Validation !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            
            break;

        case 'addpost':
            if (! $result) {
                SessionManager::getInstance()->set('actionmessage','Echec ajout Article!');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            else {
                SessionManager::getInstance()->set('actionmessage','Article ajouté avec succès !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            
            break;
        
        case 'updatepost':
            if (! $result) {
                SessionManager::getInstance()->set('actionmessage','Echec mise à jour  Article  !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            else {
                SessionManager::getInstance()->set('actionmessage','Article mis à jour avec  succès!');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            
            break;
        //}
        case 'deletepost':
            if (! $result) {
                SessionManager::getInstance()->set('actionmessage','Echec suppression Article !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            else {
                SessionManager::getInstance()->set('actionmessage','Article supprimé avec succès !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            
            break;

            case 'useradd':
            if ($result == 1) {
                SessionManager::getInstance()->set('actionmessage','Merci pour votre insription. Pour activer votre compte. Merci de cliquer sur le lien d\'activation qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .  !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
                
            elseif($result == 'email') {
                SessionManager::getInstance()->set('actionmessage', 'l\adresse email existe d&egrave;j&agrave; !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            elseif ( ($result != 1) && ($result != 'email')) {
                SessionManager::getInstance()->set('actionmessage','Echec d\'ajout utilisateur !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'usersignin':
            if ($result == 1) {
                SessionManager::getInstance()->set('actionmessage','Merci pour votre insription. Pour activer votre compte. Merci de cliquer sur le lien d\'activation qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .  !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
                
            elseif($result == 'email') {

                SessionManager::getInstance()->set('actionmessage','l\adresse email existe d&egrave;j&agrave; !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            elseif ( ($result != 1) && ($result != 'email')) {

                SessionManager::getInstance()->set('actionmessage','Echec d\'ajout utilisateur !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'verifylogin':

                if (($result) && ($result == 'account_not_activated')) {

                SessionManager::getInstance()->set('actionmessage','Votre compte n\'est pas activ&eacute;. Merci de verifier votre messagerie pour l\'activer !');
                SessionManager::getInstance()->set('alert_flag',  0);

            }elseif(!$result ) {

                SessionManager::getInstance()->set('actionmessage','Mauvais  Login ou mot de Passe !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            else {

                SessionManager::getInstance()->set('actionmessage','Bienvenue !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            
            break;

            case 'backblogmanage':
            if ( $result == 1) {
                SessionManager::getInstance()->set('actionmessage','Bienvenue dans le menu d\'administration  !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            elseif ( $result == 0) {
                SessionManager::getInstance()->set('actionmessage','Connexion avec succès !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            
            break;

            case 'userupdate':
            if ( $result ) {
                SessionManager::getInstance()->set('actionmessage','Profile Utilistaeur mis &aacute; jour !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            else{
                SessionManager::getInstance()->set('actionmessage','probl&eacute;me lors de la mise à jour !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'userdelete':
            if ( $result ) {
                SessionManager::getInstance()->set('actionmessage','Utilistaeur supprim&eacute; !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            else{
                SessionManager::getInstance()->set('actionmessage','probl&egrave;me lors de la Suppression !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'passreset':
            if ( $result ) {
                SessionManager::getInstance()->set('actionmessage',' Pour r&eacute;initialiser votre Mot de Passe. Merci de cliquer sur le lien  qui vous a &eacute;t&eacute; envoy&eacute; sur votre adresse email .   !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            else{
                SessionManager::getInstance()->set('actionmessage','l\'adresse email n\'existe pas !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'passreinitialisation':
            if ( $result === "emailissue" ) {
                SessionManager::getInstance()->set('actionmessage',' l\'adresse email n\'existe pas  !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            elseif( !$result || ($result === "tokenissue") ){
                SessionManager::getInstance()->set('actionmessage','le lien Pour r&eacute;initialiser votre Mot de Passe est expir&eacute;  !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'newpass':
            if ( $result ) {
                SessionManager::getInstance()->set('actionmessage',' Votre Mot de Passe a &eacute;t&eacute; r&eacute;initialis&eacute; .   !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            else{
                SessionManager::getInstance()->set('actionmessage','Probl&egrave;me de confirmation de mot de passe  !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'contactform':
            if ( $result ) {
                SessionManager::getInstance()->set('actionmessage',' Votre message a &eacute;t&eacute; envoy&eacute; .   !');
                SessionManager::getInstance()->set('alert_flag',  1);
            }
            else{
                SessionManager::getInstance()->set('actionmessage','Probl&egrave;me d\'envoi du mail  !');
                SessionManager::getInstance()->set('alert_flag',  0);
            }
            
            break;

            case 'tokenlife':
            if ( $result ) {
                if(($result == 'token') || ($result == 'referer') ){
                    SessionManager::getInstance()->set('actionmessage','Vous ne pouvez pas faire cela !');
                    SessionManager::getInstance()->set('alert_flag',  0);
                    
                }elseif($result == 'expiredtoken' ){
                    
                    SessionManager::getInstance()->set('actionmessage',' Votre session est expir&eacute;e .  Merci recharger la page et de recommencer !');
                    SessionManager::getInstance()->set('alert_flag',  0);
                }
                
            }else{
                    SessionManager::getInstance()->set('actionmessage',' Probl&egrave;me de token .  Merci de recommencer !');
                    SessionManager::getInstance()->set('alert_flag',  0);
            }
           
            
            break;
        
        }
        
    }


    # CHECK IF THERE IS AN ALERT MESSAGE TO DISPLAY
    # AND RETURN THE MESSAGE
    # returns $message

    public function is_alertMessage(){

        $message = "";
        
        // CHECKS IF THERE IS A MESSAGE : ( ALERT ) TO BE DISPLAYED 

        if ((null !== SessionManager::getInstance()->get('alert_flag')) &&  (SessionManager::getInstance()->get('alert_flag') == 0)){
            
            $classe = "alert-danger";

        }elseif((null !== SessionManager::getInstance()->get('alert_flag')) &&  (SessionManager::getInstance()->get('alert_flag') == 1)){
            
            $classe = "alert-success";
        }

        if((null !== SessionManager::getInstance()->get('actionmessage')) && (null !== SessionManager::getInstance()->get('alert_flag'))) {

            $actionmessage = SessionManager::getInstance()->get('actionmessage');

            ob_start();

        ?>
        
            <div class="alert <?= $classe ?> my-2 mx-2  alert-dismissible fade show" role="alert">
            <em><?= $actionmessage ?></em>
            <button type="button" class="btn-close justify-content-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php

            $message = ob_get_clean();

        }
        
        return $message;
    }
}

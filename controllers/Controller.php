<?php
namespace Controllers;

use Inc\SessionManager;
use Inc\MessageDisplay;

abstract class Controller
{
	 
	 protected $model;
	 protected $modelName;
    protected $messageDisplay;
    
     public function __construct(){

        $this->model = new $this->modelName();
        $this->messageDisplay = new \Inc\MessageDisplay();
      
     }

   /*****************************************
	#CHECK IS LOGGED 

	*****************************************/

	public function is_Logged(){

		if(null !== SessionManager::getInstance()->get('USERTYPEID')){

			return true;

		}else{

			return false;

		}
	}


	/*****************************************
	#CHECK IS LOGGED ADMIN

	*****************************************/

	public function is_Admin(){

		if((null !== SessionManager::getInstance()->get('USERTYPEID')) && (SessionManager::getInstance()->get('USERTYPEID') == 1)){

			return true;

		}else{

			return false;

		}
	}

	/*****************************************
	#CHECK IS LOGGED ADMIN

	*****************************************/

	public function is_Guest(){

		if((null !== SessionManager::getInstance()->get('USERTYPEID')) && (SessionManager::getInstance()->get('USERTYPEID') == 3)){

			return true;

		}else{

			return false;

		}
	}

   /*****************************************
	#redirect not LOGGED 

	*****************************************/

	public function redirectLogin(){

      SessionManager::getInstance()->Set('actionmessage', 'Accès non authorisé, Veuillez vous  connecter !');
      SessionManager::getInstance()->Set('alert_flag', 0);
      
      \Http::redirect('loginview-user.html#login');
   }

}
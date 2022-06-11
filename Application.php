<?php
use Inc\Request;
use Inc\SessionManager;

class Application {

    public static function process(){

        $controllerName = "Post";
        $action = "listposts";

        $request =  new \Inc\Request;
        

        if(!empty($request->getGet()->get('controller'))){
            sessionmanager::getinstance()->set('controllername', $request->getGet()->get('controller'));
            $controllerName = ucfirst($request->getGet()->get('controller'));
            
        }

        if(!empty($request->getGet()->get('action'))){
            $action = $request->getGet()->get('action');
        }

        $controllerName = "\Controllers\\" . $controllerName;
        $controller = new $controllerName();
       
        return $controller;
           
        //$controller->$action();
    }
}
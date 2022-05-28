<?php
use Inc\Request;
class Application {

    public static function process(){

        $controllerName = "Post";
        $action = "listposts";

        //$get = new \Inc\Method($_GET);
        $request =  new \Inc\Request;
        

        if(!empty($request->getGet()->get('controller'))){
            $controllerName = ucfirst($request->getGet()->get('controller'));
            
        }

        if(!empty($request->getGet()->get('action'))){
            $action = $request->getGet()->get('action');
        }

        $controllerName = "\Controllers\\" . $controllerName;
        $controller = new $controllerName();
        /*var_dump($controller);
        exit;*/
        return $controller;
        
            
        //$controller->$action();
    }
}

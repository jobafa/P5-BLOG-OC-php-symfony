<?php

class Application {

    public static function process(){

        $controllerName = "Post";
        $action = "listposts";

        if(!empty($_GET['controller'])){
            $controllerName = ucfirst($_GET['controller']);
            
        }

        if(!empty($_GET['action'])){
            $action = $_GET['action'];
        }

        $controllerName = "\Controllers\\" . $controllerName;
        $controller = new $controllerName();
        /*var_dump($controller);
        exit;*/
        return $controller;
        
            
        //$controller->$action();
    }
}
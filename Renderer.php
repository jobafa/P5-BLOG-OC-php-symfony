<?php

class Renderer{

    public static function render(string $path,  array $variables = [] ){

        extract($variables);
        
        ob_start(); 
                
        require('view/'.$path.'.php');
        
        $content = ob_get_clean(); 
        
        require('view/frontend/template.php'); 
        
        
        }
}
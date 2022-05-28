<?php

spl_autoload_register ( function ($className) {
    
    $className = str_replace("\\","/", $className);
    require_once"$className.php";});
    //require_once("controllers/$classname.php")
   /* $sources = array("Controllers/$className.php", "models/$className.php ",  "inc/$className.php " );
    
        foreach ($sources as $source) {
            if (file_exists($source)) {
                var_dump($source);
                require_once $source;
            } 
        } */
    


/*spl_autoload_register(function ($className){
    //className = Controllers\Article
    // require = libraries/Controllers/Article.php
    $className = str_replace("\\","/", $className);

    require_once("$className.php");
    //var_dump($className);
} );
*/

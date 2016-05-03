<?php

/**
 * Front controller
 */

$page = '';

//Loading classes
require_once 'vendor/autoload.php'; //Twig
spl_autoload_register(function ($class){
    require_once 'src/models/' . $class . '.php';
});

//Checking page
if(isset($_GET['page'])){
    if(is_file('src/controllers/'.$_GET['page'].'.php'))
        $page = 'src/controllers/'.$_GET['page'].'.php';
    else
        $page = 'src/controllers/404.php';
}
else
    $page = 'src/controllers/index.php';

//Including
try{
    require_once $page;
} catch (Exception $e){
    require_once 'src/controllers/exception.php';
}

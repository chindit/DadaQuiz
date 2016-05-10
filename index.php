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
//Twig
$loader = new Twig_Loader_Filesystem('src/views');
$twig = new Twig_Environment($loader, array(
    //'cache' => 'cache',
));

try{
    require_once $page;
} catch (Exception $e){
    require_once 'src/controllers/exception.php';
}

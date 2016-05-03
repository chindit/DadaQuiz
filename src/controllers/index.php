<?php

/**
 * Controller for index page
 */

//First, get a list of Quiz

$loader = new Twig_Loader_Filesystem('src/views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
));

echo $twig->render('index.twig');

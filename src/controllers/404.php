<?php

$loader = new Twig_Loader_Filesystem('src/views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
));

echo $twig->render('404.twig');

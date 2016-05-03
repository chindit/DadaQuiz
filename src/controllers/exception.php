<?php

$chaine = '';
//$e est notre exception
if($e instanceof InvalidArgumentException){
    $chaine = 'Un argument invalide a été détecté.  Cela peut se produire si l\'URL a été manipulée ou si l\'article demandé n\'existe pas.';
}
else if($e instanceof UnexpectedValueException){
    $chaine = 'L\'objet reçu en paramètre ne correspond pas au modèle donné!';
}
else if($e instanceof BadMethodCallException){
    $chaine = 'Le contrôleur demandé n\'existe pas!';
}
else if($e instanceof PDOException){
    $chaine = 'Une erreur de base de données est survenue!';
}
else{
    //Exception par défaut
    $chaine = 'Une exception générique s\'est produite';
}

$loader = new Twig_Loader_Filesystem('src/views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
));

echo $twig->render('exception.twig');
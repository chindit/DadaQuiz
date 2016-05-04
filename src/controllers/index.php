<?php

/**
 * Controller for index page
 */

//First, get a list of Quiz
$quizManager = new QuizManager();
if(isset($_GET['nbPage']) && is_numeric($_GET['nbPage'])){
    if(!$quizManager->isPageInRange($_GET['nbPage']))
        header('Location:index.php?page=404');
}

$loader = new Twig_Loader_Filesystem('src/views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
));

echo $twig->render('index.twig');

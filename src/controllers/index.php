<?php

/**
 * Controller for index page
 */

//First, get a list of Quiz
$quizManager = new QuizManager();
$page = 1;
if(isset($_GET['nbPage']) && is_numeric($_GET['nbPage'])){
    if(!$quizManager->isPageInRange($_GET['nbPage']))
        header('Location:index.php?page=404');
    $page = $_GET['nbPage'];
}

$quizList = $quizManager->getPage($page);

echo $twig->render('index.twig', array('quizList' => $quizList, 'pagination' => array('current' => $page, 'total' => $quizManager->getNbPages())));

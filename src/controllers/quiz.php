<?php
/**
 * Affiche un Quiz
 */

if(!isset($_GET['quiz'])){
    header('Location:index.php');
}

if(!is_numeric($_GET['quiz'])){
    header('Location:index.php?page=404');
}

$valided = false;
$data = array();

$quizManager = new QuizManager();
$quiz = $quizManager->getQuiz($_GET['quiz']);

if(isset($_POST['submit'])){
    foreach($_POST as $key => $val){
        if(is_numeric($key) && is_numeric($val)){
            $data[$key] = $val;
        }
    }
    $valided = true; //Quiz submitted
}

//Count nb of questions for results
$data['nbQuestions'] = count($quiz->getQuestions());

//Rendering
echo $twig->render('quiz.twig', array('quiz' => $quiz, 'valided' => $valided, 'data' => $data));

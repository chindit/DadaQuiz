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

if(isset($_POST['submit']) && !$isAlreadySent){
    foreach($_POST as $key => $val){
        if(is_array($val)){
            //Checkbox!
            $subAnswers = array();
            foreach($val as $subKey => $subVal){
                if(is_numeric($subVal)){
                    $subAnswers[] = $subVal;
                }
            }
            $data[$key] = $subAnswers;
        }
        if(is_numeric($key) && is_numeric($val)){
            //Radio
            $data[$key] = $val;
        }
    }
    $valided = true; //Quiz submitted
    
    //STATS
    $data['points'] = $quizManager->getPoints($quiz, $data);
    
    //Saving scores
    $quizManager->saveHistory($data, $_GET['quiz']); //$_GET is trusted.  See L18.
}

//Rendering
echo $twig->render('quiz.twig', array('quiz' => $quiz, 'valided' => $valided, 'data' => $data));

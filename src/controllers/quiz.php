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
$twigData = array();

$quizManager = new QuizManager();
$quiz = $quizManager->getQuiz($_GET['quiz']);

//Checking history
$isHistory = false;
if(isset($_GET['history']) && is_numeric($_GET['history'])){
    $historyData = $quizManager->getHistory($_GET['history'], $_GET['quiz']);
    if($historyData === FALSE){
        throw new Exception('L\'historique demandé n\'existe pas!');
    }
    //Check for IP
    if($historyData['ip'] != $_SERVER['REMOTE_ADDR']){
        throw new ErrorException('Cet historique ne vous appartient pas!  Galopin va!');
    }
    //Everything is OK
    $valided = true;
    $isHistory = true;
    
    //Merging data
    $twigData = array_merge($twigData, $historyData);
}

if(isset($_POST['submit']) && !$valided){var_dump($_POST);
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
    $quizManager->saveHistory($data, $quiz); //$_GET is trusted.  See L19.
    
    $twigData['data'] = $data;
}

//Counting nb of records in history
$nbHistory = $quizManager->countHistory($_GET['quiz']);

$twigData['quiz'] = $quiz;
$twigData['valided'] = $valided;
$twigData['isHistory'] = $isHistory;
$twigData['nbHistory'] = $nbHistory;

//Rendering
echo $twig->render('quiz.twig', $twigData);

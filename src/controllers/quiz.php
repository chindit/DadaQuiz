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

$data = array();
$twigData = array();
$twigData['valided'] = false;

$quizManager = new QuizManager();
$quiz = $quizManager->getQuiz($_GET['quiz']);

//Checking history
$twigData['isHistory'] = false;
if(isset($_GET['history']) && is_numeric($_GET['history'])){
    //History is asked -> retreiving data
    $historyData = $quizManager->getHistory($_GET['history'], $_GET['quiz']);
    
    //No data -> Exception
    if($historyData === FALSE){
        throw new Exception('L\'historique demandé n\'existe pas!');
    }
    //Check for IP
    if($historyData['ip'] != $_SERVER['REMOTE_ADDR']){
        throw new ErrorException('Cet historique ne vous appartient pas!  Galopin va!');
    }
    //Everything is OK
    $twigData['valided'] = true; //Showing results
    $twigData['isHistory'] = true;
    
    //Merging data
    $twigData = array_merge($twigData, $historyData);
}

//Quiz submitted
if(isset($_POST['submit']) && !$twigData['valided']){
    //Checking validity for every answer
    foreach($_POST as $key => $val){
        //If array() -> checkbox
        if(is_array($val)){
            $subAnswers = array();
            
            //Need numerical value
            foreach($val as $subKey => $subVal){
                if(is_numeric($subVal)){
                    $subAnswers[] = $subVal;
                }
            }
            $data[$key] = $subAnswers;
        }
        //If $key and $val are numeric -> radio
        if(is_numeric($key) && is_numeric($val)){
            //Radio
            $data[$key] = $val;
        }
        //If only one is numeric -> Order
        if(is_numeric($key) && !is_numeric($val)){
            //We check if it's an «Order» question
            $questions = $quiz->getQuestions();
            //We need to parse all questions to find the selected one :(
            foreach($questions as $quest){
                if($quest->getId() == $key && $quest->getType() == 'order'){
                    //It's an order-> checking answer
                    $answer = $quizManager->getOrderedAnswers($quest->getId());
                    
                    //Putting correction into var
                    $data[$key]['user'] = $val;
                    $data[$key]['answer'] = $answer;
                    $data[$key]['array'] = explode(',', $val);
                    
                    break; //End of loop
                }
            }
        }
    }
    $twigData['valided'] = true; //Quiz submitted
    
    //STATS
    $data['points'] = $quizManager->getPoints($quiz, $data);

    //Saving scores
    $quizManager->saveHistory($data, $quiz); //$_GET is trusted.  See L19.
    
    $twigData['data'] = $data;
}

//Counting nb of records in history
$nbHistory = $quizManager->countHistory($_GET['quiz']);

//Setting last data
$twigData['quiz'] = $quiz;
$twigData['nbHistory'] = $nbHistory;

//Rendering
echo $twig->render('quiz.twig', $twigData);

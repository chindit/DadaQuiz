<?php
/**
 * Affiche l'historique des réponses à un Quiz
 */

//We need a valid ID
if(!isset($_GET['quiz']) || !is_numeric($_GET['quiz'])){
    header('Location: index.php?page=404');
    die();
}

$quizManager = new QuizManager();
//Verifying ID
$quiz = $quizManager->getQuiz($_GET['quiz']);

//Now, ID is OK -> getting history
$history = $quizManager->getHistoryList($quiz->getId());
$score = $quizManager->getQuizScore($quiz->getId());

//Rendering
echo $twig->render('historique.twig', array('score' => $score, 'history' => $history, 'quiz' => $quiz));

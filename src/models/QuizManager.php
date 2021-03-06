<?php
/**
 * Nothing to say :)
 *
 * @author david
 */
class QuizManager{
    
    /**
     * Constructor : get the DB
     */
    public function __construct(){
        $this->bdd = Sql::getInstance()->getPDO();
        $this->nbPages = -1;
    }
    
    /**
     * Vérifie si le nombre donné est valide et correspond bien à une page 
     * @param int $page
     * @return bool
     */
    public function isPageInRange($page){
        //1)Nombre de pages?
        $nbPages = $this->getNbPages();
        //2)Renvoi de la valeur
        return ($page > 0 && $page <= $nbPages) ? true : false;
    }
    
    /**
     * Calcule le nombre de pages
     * @return int : nombre de pages
     */
    public function getNbPages(){
        //Si on a déjà compté, on ne recompte pas.
        if($this->nbPages > 0){
            return $this->nbPages;
        }
        //Comptage des articles
        $config = Config::getInstance();
        $nbArticles = $this->bdd->query('SELECT COUNT(id) AS nbItems FROM quiz')->fetch(PDO::FETCH_NUM)[0];
        $this->nbPages = ceil($nbArticles/$config->getConfig('itemsPerPage'));
        //Renvoi du nombre de pages 
        return $this->nbPages;
    }
    
    /**
     * Renvoie les quiz de la page demandée
     * @param int $page : nombre de pages 
     * @return array : liste des articles
     */
    public function getPage($page){
        //Sélection des données
        $query = $this->bdd->prepare('SELECT * FROM quiz ORDER BY created DESC  LIMIT :start, :limit');
        $config = Config::getInstance();
        //Page -1 étant donnée que Page 1 = Articles 0
        $nbItemsPerPage = (int)$config->getConfig('itemsPerPage');
        $start = ($page-1)*$nbItemsPerPage;
        $query->bindParam('start', $start, PDO::PARAM_INT);
        $query->bindParam('limit', $nbItemsPerPage, PDO::PARAM_INT);
        $query->execute();
        //Même si le texte a été tronqué, ce sont quand même des «articles»
        $quizListData = $query->fetchAll(PDO::FETCH_ASSOC);
        $quizList = array();
        //On crée les objets et on les renvoie
        foreach($quizListData as $quizData){
            $currentQuiz = new Quiz();
            $currentQuiz->hydrate($quizData);
            $quizList[] = $currentQuiz;
        }
        //On renvoie la liste des articles
        return $quizList;
    }
    
    /**
     * Verify if ID is valid or not
     * @param int $id ID
     * @return bool
     */
    public function getQuiz($id){
        $query = $this->bdd->prepare('SELECT * FROM quiz WHERE id=:id');
        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->execute();
        $quizSql = $query->fetch();
        if($quizSql === FALSE){
            throw new OutOfRangeException('L\'id du Quiz n\'est pas valide!');
        }
        $quiz = new Quiz($quizSql);
        
        //Quiz valid -> getting questions
        $questionsList = $this->bdd->prepare('SELECT * FROM questions WHERE questions.quiz=:id ORDER BY RAND()');
        $questionsList->bindParam('id', $id, PDO::PARAM_INT);
        $questionsList->execute();
        
        //Prepare for Answers
        $answersSql = $this->bdd->prepare('SELECT * FROM reponses WHERE reponses.question IN (SELECT id FROM questions WHERE questions.quiz = :id) ORDER BY RAND()');
        $answersSql->bindParam('id', $id, PDO::PARAM_INT);
        $answersSql->execute();
        $answersList = $answersSql->fetchAll();
        
        while($curr = $questionsList->fetch()){
            $question = new Question($curr);
            //Get all answers for this question
            foreach($answersList as $key => $answer){
                if($answer['question'] == $question->getId()){
                    $answerObject = new Answer($answer);
                    $question->addAnswer($answerObject);
                }
            }
            $quiz->addQuestion($question);
        }
        
        return $quiz;
    }
    
    /**
     * Calculate how many points we need to complete the Quiz
     * @param Quiz $quiz
     * @return int
     * @throws UnexpectedValueException
     */
    public function getPoints(Quiz $quiz, array $data){
        //Check type
        if(!$quiz instanceof Quiz){
            throw new UnexpectedValueException('A «Quiz» is needed here');
        }
        
        //Initializing vars
        $points = 0;
        $score = 0;
        $questionScore = 0;
        
        //Getting questions
        $questions = $quiz->getQuestions();
        
        //Parsing questions
        foreach($questions as $question){
            $answers = $question->getAnswers();
            
            $isQuestionOk = true; //In case of Checkbox, this var is used to check if every sub-choice is correct
            
            //Check solution
            foreach($answers as $answer){
                if($answer->getCorrect()){
                    switch($question->getType()){
                        case 'radio':
                            //If answered AND answer is correct, score is incremented
                            if(array_key_exists($question->getId(), $data) && ($answer->getId() == $data[$question->getId()])){
                                $score++;
                                $questionScore++;
                            }
                            break;
                        case 'checkbox':
                            //If answered AND answer is correct, score is incremented BUT question in not necessarily correct
                            if(array_key_exists($question->getId(), $data) && in_array($answer->getId(), $data[$question->getId()])){
                                $score++;
                            }
                            else{
                                //Not answered OR correct answer was not checked -> question failed
                                $isQuestionOk = false;
                            }
                            break;
                        case 'number':
                            if(array_key_exists($question->getId(), $data) && ($data[$question->getId()] == $answer->getAnswer())){
                                $score++;
                                $questionScore++;
                            }
                            break;
                        case 'order':
                            //Do nothing.  This case is treated outside the switch
                            break;
                        default:
                            throw new UnexpectedValueException('Type for question '.$question->getId().' is not valid!');
                            break;
                    }//End of Switch
                }//End of Question->getCorrect()
                else{
                    //We just need to remove a point if a wrong checkbox was checked
                    if($question->getType() == 'checkbox'){
                        if(array_key_exists($question->getId(), $data) && in_array($answer->getId(), $data[$question->getId()])){
                            //Wrong answer!
                            $score--; //Loosing 1pt
                            $isQuestionOk = false; //Question is false
                        }
                    }
                }
                
            }//End of answer loop
            
            //Adding question score in case of checkbox
            if($question->getType() == 'checkbox' && $isQuestionOk){
                $questionScore++;
            }
            
            //Treating «order» questions
            if($question->getType() == 'order' && array_key_exists($question->getId(), $data) && isset($data[$question->getId()]['user']) && isset($data[$question->getId()]['answer']) && $data[$question->getId()]['user'] == $data[$question->getId()]['answer']){
                $score++;
                $questionScore++;  
            }
        }//End of Question loop
        
        //Return score
        return array('points' => $this->getQuizScore($quiz->getId()), 'score' => $score, 'questions' => count($questions), 'questionsScore' => $questionScore);
    }
    
    /**
     * Save answers into database
     * 
     * @param array $data
     * @param int $quiz
     * @return null
     * @throws UnexpectedValueException
     */
    public function saveHistory($data, $quiz){
        if(!is_array($data)){
            throw new UnexpectedValueException('Les résultats du quiz ne sont pas valides');
        }
        $storedData = serialize($data);
        $score = $this->getPoints($quiz, $data)['score'];
        $quizId = $quiz->getId();
        $sql = $this->bdd->prepare('INSERT INTO history(quiz,data,score,ip) VALUES(:quiz, :data, :score, :ip)');
        $sql->bindParam('quiz', $quizId, PDO::PARAM_INT);
        $sql->bindParam('data', $storedData, PDO::PARAM_STR);
        $sql->bindParam('score', $score, PDO::PARAM_INT);
        $sql->bindParam('ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $sql->execute();
        
        return;
    }
    
    /**
     * Count number of records in history
     * @param type $quiz
     * @return type
     */
    public function countHistory($quiz){
        $query = $this->bdd->prepare('SELECT COUNT(id) FROM history WHERE ip=:ip AND quiz=:quiz');
        $query->bindParam('ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $query->bindParam('quiz', $quiz, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_NUM)[0];
    }
    
    /**
     * Get history for Quiz $quiz
     * @param type $quiz
     * @return type
     */
    public function getHistory($id, $quiz){
        $query = $this->bdd->prepare('SELECT * FROM history WHERE id=:id AND quiz=:quiz');
        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->bindParam('quiz', $quiz, PDO::PARAM_INT);
        $query->execute();
        
        $result = $query->fetch();
        $result['data'] = unserialize($result['data']);
        return $result;
    }
    
    /**
     * Return a list of history entry
     * @param int $quiz
     * @return mixed
     */
    public function getHistoryList($quiz){
        $query = $this->bdd->prepare('SELECT id,date,score,quiz FROM history WHERE ip=:ip AND quiz=:quiz ORDER BY date DESC');
        $query->bindParam('ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $query->bindParam('quiz', $quiz, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }
    
    /**
     * Count how many points we can get for a quiz
     * @param int $quiz
     * @return int
     */
    public function getQuizScore($quiz){
        $query = $this->bdd->prepare('CALL count_points(:id)');
        $query->bindParam('id', $quiz, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_NUM)[0];
    }
    
    /**
     * Return correct answers for Order type questions.
     * @param type $question
     * @return type
     */
    public function getOrderedAnswers($question){
        $query = $this->bdd->prepare('SELECT GROUP_CONCAT(reponses.answer) FROM reponses WHERE reponses.question = :question ORDER BY reponses.poids ASC ');
        $query->bindParam('question', $question, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_NUM)[0];
    }
    
    private $nbPages;
    private $bdd;
}

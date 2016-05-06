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
        $nbArticles = $this->bdd->query('SELECT COUNT(id) AS nbItems FROM quiz')->fetch()[0];
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
        $nbItemsPerPage = $config->getConfig('itemsPerPage');
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
        $quizSql = $query->fetch(PDO::FETCH_ASSOC);
        if($quizSql === FALSE){
            throw new OutOfRangeException('L\'id du Quiz n\'est pas valide!');
        }
        $quiz = new Quiz($quizSql);
        
        //Quiz valid -> getting questions
        $questionsList = $this->bdd->prepare('SELECT * FROM questions WHERE questions.quiz=:id');
        $questionsList->bindParam('id', $id, PDO::PARAM_INT);
        $questionsList->execute();
        
        //Prepare for Answers
        $answersSql = $this->bdd->prepare('SELECT * FROM reponses WHERE reponses.question=:id');
        $idQuestion;
        $answersSql->bindParam('id', $idQuestion, PDO::PARAM_INT);
        
        while($curr = $questionsList->fetch(PDO::FETCH_ASSOC)){
            $question = new Question($curr);
            //Get all answers for this question
            $idQuestion = $question->getId();
            $answersSql->execute();
            while($ans = $answersSql->fetch(PDO::FETCH_ASSOC)){
                $answer = new Answer($ans);
                $question->addAnswer($answer);
            }
            $quiz->addQuestion($question);
        }
        
        return $quiz;
    }
    
    private $nbPages;
    private $bdd;
}

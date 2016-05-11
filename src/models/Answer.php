<?php
/**
 * Description of Answer
 *
 * @author david
 */
class Answer extends Entity{
    public function __construct($data = array()){
        if(!empty($data)){
            $this->hydrate($data);
        }
    }
    
    protected function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setAnswer($answer){
        $this->answer = $answer;
        return $this;
    }
    
    public function getAnswer(){
        return $this->answer;
    }
    
    public function setCorrect($correct){
        $this->correct = (bool)$correct;
        return $this;
    }
    
    public function getCorrect(){
        return (bool)$this->correct;
    }
    
    protected function setQuestion($question){
        $this->question = $question;
        return $this;
    }
    
    public function getQuestion(){
        return $this->question;
    }
    
    public function setPoids($poids){
        $this->poids = $poids;
        return $this;
    }
    
    public function getPoids(){
        return $this->poids;
    }
    
    private $id;
    private $answer;
    private $question;
    private $correct;
    private $poids;
}

<?php
/**
 * Description of Question
 *
 * @author david
 */
class Question extends Entity{
    public function __construct($data = array()){
        if(!empty($data)){
            $this->hydrate($data);
        }
        $this->answers = array();
    }
    
    protected function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setQuestion($question){
        $this->question = $question;
        return $this;
    }
    
    public function getQuestion(){
        return $this->question;
    }
    
    public function setType($type){
        $this->type = $type;
        return $this;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function addAnswer($answer){
        if(!$answer instanceof Answer){
            throw new InvalidArgumentException('Une Answer était attendue');
        }
        $this->answers[] = $answer;
        return $this;
    }
    
    public function removeAnswer($answer){
        if(!$answer instanceof Answer){
            throw new InvalidArgumentException('Une Answer était attendue');
        }
        foreach($this->answers as $index => $currAnswer){
            if($currAnswer->getId() == $answer->getId()){
                unset($this->answers[$index]);
                break;
            }
        }
        return $this;
    }
    
    public function getAnswers(){
        return $this->answers;
    }
    
    protected function setQuiz($quiz){
        $this->quiz = $quiz;
        return $quiz;
    }
    
    public function getQuiz(){
        return $this->quiz;
    }
    
    public function setExplanation($explanation){
        $this->explanation = $explanation;
        return $this;
    }
    
    public function getExplanation(){
        return $this->explanation;
    }
    
    private $id;
    private $quiz;
    private $question;
    private $type;
    private $answers;
    private $explanation;
}

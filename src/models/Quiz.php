<?php
/**
 * Description of Quiz
 *
 * @author david
 */
class Quiz extends Entity{
    public function __construct($data = array()){
        if(!empty($data)){
            $this->hydrate($data);
        }
        $this->questions = array();
    }
    
    protected function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }
    
    public function getDescription(){
        return $this->description;
    }
    
    protected function setCreated($date){
        $this->created = $date;
        return $this;
    }
    
    public function getCreated(){
        return $this->created;
    }
    
    public function addQuestion($question){
        if(!$question instanceof Question){
            throw new InvalidArgumentException('Une Question était attendue');
        }
        $this->questions[] = $question;
        return $this;
    }
    
    public function removeQuestion($question){
        if(!$question instanceof Question){
            throw new InvalidArgumentException('Une Question était attendue');
        }
        foreach($this->questions as $index => $currQuest){
            if($currQuest->getId() == $question->getId()){
                unset($this->questions[$index]);
                break;
            }
        }
        return $this;
    }
    
    public function getQuestions(){
        return $this->questions;
    }

    private $id;
    private $name;
    private $description;
    private $created;
    private $questions;
}

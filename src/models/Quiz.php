<?php
/**
 * Description of Quiz
 *
 * @author david
 */
class Quiz extends Entity{
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

    private $id;
    private $name;
    private $description;
    private $created;
}

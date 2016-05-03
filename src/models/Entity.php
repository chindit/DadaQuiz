<?php

/**
 * Classe abstraite «Entity»
 * En fait, elle me sert juste à ne pas répéter mon hydrateur
 */
 
abstract class Entity{
    protected function hydrate($data){
        foreach($data as $key => $value){
            $methodName = 'set'.ucfirst($key);
            if(!method_exists($this, $methodName)){ //is_callable ne fonctionnera pas dans ce cas à cause du «abstract».
                //La propriété n'est pas valide!
                throw new UnexpectedValueException('La propriété «'.$methodName.'» n\'existe pas!');
            }
            //Sinon, on applique la propriété
            $this->$methodName($value);
        }
    }
}

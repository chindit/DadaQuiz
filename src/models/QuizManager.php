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
    
    private $nbPages;
    private $bdd;
}

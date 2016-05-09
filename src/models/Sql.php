<?php
/**
 * Plutôt qu'une classe, c'est une simple instance de PDO
 * Déclarée sous forme de Singleton pour éviter d'ouvrir plusieurs connexions.
 * C'est squelettique, mais le projet n'a pas besoin de plus.
 */
class Sql{
  
   /**
    * «Constructeur» du Singleton
    */
    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self; 
        }
        return self::$instance;
    }
    
    /**
     * Renvoie la connexion à la BDD
     */
     public function getPDO(){
         return $this->bdd;
     }
  
   /**
    * Constructeur -> simple connexion à la BDD
    */
    protected function __construct() {
        $config = Config::getInstance();
        $this->bdd = new PDO('mysql:host='.$config->getConfig('hostname').';dbname='.$config->getConfig('dbname').';charset=utf8', $config->getConfig('dbuser'), $config->getConfig('dbpass'), array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
    }
    
    /**
     * RàS
     */
    protected function __clone() { }
  
    protected static $instance; 
    private $bdd;
}

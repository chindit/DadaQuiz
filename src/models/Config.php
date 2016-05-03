<?php
/**
 * Classe qui gère la config
 */
class Config{
  
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
    * Constructeur -> chargement des données
    */
    protected function __construct() {
        $this->config = (is_file($this->path)) ? (json_decode(file_get_contents($this->path), true)) : array();
    }
    
    /**
     * Renvoie une valeur de configuration
     * @param string $config Clé de configuration à retourner
     * @return mixed La valeur de la config ou false si elle n'existe pas
     */
     public function getConfig($key){
         return (array_key_exists($key, $this->config)) ? $this->config[$key] : false;
     }
     
     /**
      * Enregistre un clé
      * @param string $key Clé de configuration
      * @param mixed $value Valeur de la clé
      * @return bool
      */
      public function setConfig($key, $value){
          if(array_key_exists($key, $this->config))
              if($this->config[$key] == $value)
                return true;
          //Sinon, on change la valeur et on enregistre
          $this->config[$key] = $value;
          return $this->save();
      }
      
      /**
       * Enregistre la config
       */
       private function save(){
           $json = json_encode($this->config);
           return (file_put_contents($this->path, $json));
       }
    
    /**
     * RàS
     */
    protected function __clone() { }
  
    protected static $instance; 
    private $config;
    private $path = 'config/config.json';
}

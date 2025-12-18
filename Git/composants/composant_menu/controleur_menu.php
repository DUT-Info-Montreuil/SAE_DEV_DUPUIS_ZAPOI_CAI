<?php
include_once 'vue_menu.php';
Class ContMenu{
    private $vue;

    public function __construct(){
        $this->vue = new VueMenu();
    }
    public function getAffichage(){
        return $this->vue->getAffichage();
    }

}

?>
<?php
include_once 'controleur_menu.php';
Class ModMenu{
    private $controleur;

    public function __construct(){
        $this->controleur = new ContMenu;
    }
    public function affiche(){
        return $this->controleur->getAffichage();
    }
}

?>
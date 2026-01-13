<?php
include_once "controleur_accueil.php";
Class Mod_accueil{
    private $cont;
    private $vue;
    public function __construct(){

        $this->vue = new Vue_accueil();
        $this->cont = new Cont_accueil($this->vue);
        $this->affiche_stock();

        }

    public function affiche_stock() {
        $this->cont->acceuil();
    }
    public function affiche(){
        return $this->cont->affiche();

    }

}

?>




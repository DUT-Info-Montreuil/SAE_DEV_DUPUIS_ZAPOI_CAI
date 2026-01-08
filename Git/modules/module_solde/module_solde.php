<?php
include_once "controleur_solde.php";
class Mod_solde{
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "inscription";

        $this->vue = new Vue_solde();
        $this->cont = new Cont_solde($this->vue);


        switch($action) {

            case "ajout_solde":
                $this->cont->envoyer_formulaire_solde();
                break;
            case "solde":
                $this->cont->afficher_formulaire_solde();
                break;
        }

    }

    public function affiche(){
        return $this->cont->affiche();
    }
}









?>

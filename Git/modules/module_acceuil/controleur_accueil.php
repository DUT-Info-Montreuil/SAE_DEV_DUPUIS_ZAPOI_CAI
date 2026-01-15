<?php
include_once 'vue_accueil.php';

class Cont_accueil{
    private $vue;

    public function __construct(Vue_accueil $vue){
        $this->vue = $vue;
    }

    public function affiche(){
        return$this->vue->affiche();
    }

    public function acceuil() {
        $this->vue->afficher_accueil();
    }






}
?>
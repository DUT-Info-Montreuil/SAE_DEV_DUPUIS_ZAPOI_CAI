<?php
Class VueMenu{
    protected $affichage;
    public function __construct(){
        if(isset($_SESSION['login']) && isset($_SESSION['mdp'])) {
        $this->affichage = '
        <nav>
        <p>
        <a href="index.php?module=joueurs&action=bienvenue">Module Joueurs</a>
        <a href="index.php?module=equipes&action=bienvenue">Module Équipes</a>
        </p>
        </nav> ';
        }




    }
    public function getAffichage(){
        return $this->affichage;
    }




}


?>
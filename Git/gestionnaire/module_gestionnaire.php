<?php
include_once "controleur_connexion.php";
Class Mod_connexion{
    private $cont;

    public function __construct(){

        $action = $_GET['action'] ?? "inscription";
        $this->cont = new Cont_connexion();
        $this->cont->exec();
        switch($action) {
            case "recap":

                $asso_array = $this->cont->getAssos();
                $this->cont->afficher_formulaire_inscription($asso_array);
                break;
            
    }

    }
public function affiche(){
    return $this->cont->affiche();

}


}






?>

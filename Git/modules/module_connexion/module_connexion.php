<?php
include_once "controleur_connexion.php";
class Mod_connexion {
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "inscription";

        $this->vue = new Vue_connexion();
        $this->cont = new Cont_connexion($this->vue);

  
        switch($action) {

            case "ajout_inscription":
                $this->cont->envoyer_formulaire_inscription();
                break;

            case "ajout_connexion":
                $this->cont->envoyer_formulaire_connexion();
                break;

            case "deconnexion":
                $this->cont->déconnexion();
                break;
        }


        $this->cont->exec();

 
        switch($action) {
            case "inscription":
                $asso_array = $this->cont->getAssos();
                $this->cont->afficher_formulaire_inscription($asso_array);
                break;

            case "connexion":
                $this->cont->afficher_formulaire_connexion();
                break;

            default:
                if (!$_SESSION['connecté']) {
                    $this->cont->afficher_formulaire_connexion();
                }
        }
    }

    public function affiche(){
        return $this->cont->affiche();
    }
}









?>

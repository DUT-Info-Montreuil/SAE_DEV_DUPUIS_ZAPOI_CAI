<?php
include_once "controleur_connexion.php";
Class Mod_connexion{
    private $cont;

    public function __construct(){

        $action = $_GET['action'] ?? "inscription";
        $this->cont = new Cont_connexion();
        $this->cont->exec();
        switch($action) {
            case "inscription":

                $asso_array = $this->cont->getAssos();
                $this->cont->afficher_formulaire_inscription($asso_array);
                break;
            case "ajout_inscription":

                $this->cont->envoyer_formulaire_inscription();
                $this->cont->afficher_formulaire_connexion();

                break;
            case "connexion":

                $this->cont->afficher_formulaire_connexion();
                break;
            case "deconnexion":
                $this->cont->déconnexion();
                break;

            case "ajout_connexion":
                $this->cont->envoyer_formulaire_connexion();
                 echo "
                <html>
                <body>
                </body>
                </html>
                ";
                break;
    }

    }
public function affiche(){
    return $this->cont->affiche();

}


}






?>

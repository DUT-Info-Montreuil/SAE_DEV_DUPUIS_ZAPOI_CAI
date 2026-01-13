<?php
include_once "controleur_connexion.php";
class Mod_connexion {
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "";

        $this->vue = new Vue_connexion();
        $this->cont = new Cont_connexion($this->vue);

  
        switch($action) {
            case "ajout_association":
                $this->cont->envoyer_formulaire_asso();
                break;
            case "ajout_inscription":
                $this->cont->envoyer_formulaire_inscription();
                break;

            case "ajout_connexion":
                $this->cont->envoyer_formulaire_connexion();
                header("Location: index.php?module=connexion&action=redirection");
            break;
            case "redirection":
                

                if(isset($_POST['association']) && $_POST['association']!="none" && $this->cont->getRole()!=4){
                    if($this->cont->getRole()==3){
                        header("Location: index.php?module=solde&association=".$_POST['association']."&action=page_solde");
                        exit;
                    }
                    else if($this->cont->getRole()==2){
                        header("Location: index.php?module=commande&association=".$_POST['association']."&action=commande");
                        exit;
                    }
                    else if ($this->cont->getRole()==1){
                        header("Location: index.php?module=stock&association=".$_POST['association']."&action=affiche_stock");
                        exit;
                    }
                }
                else{
                    header("Location: index.php?module=connexion&action=choisirAsso");
                }
                break;
            case "deconnexion":
                $this->cont->déconnexion();
                break;
        }


        switch($action) {
            case "inscription":
                $asso_array = $this->cont->getAssos();
                $this->cont->afficher_formulaire_inscription($asso_array);
                break;
            case "connexion":
                $this->cont->afficher_formulaire_connexion();
                break;
            case "nouvelleAsso":
                $this->cont->afficher_formulaire_asso();
                break;
            case "choisirAsso":
                $asso = $this->cont->getAssos();
                $this->cont->choixAsso($asso);
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
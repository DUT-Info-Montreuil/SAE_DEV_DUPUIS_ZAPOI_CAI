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
                break;
            case "validationAsso":
                $this->cont->valideAsso();
                break;
            case "redirection":


                if(isset($_POST['association']) && $_POST['association']!="none"){
                    $_SESSION['idAsso'] = $_POST['association'];
                    if($this->cont->existe($_SESSION['idCompte'], $_SESSION['idAsso'])){//Existance de l'utilisateur dans l'asso
                        if($this->cont->getRole()==3){
                        header("Location: index.php?module=solde&association=".$_SESSION['idAsso']."&action=page_solde");
                        exit;
                        }
                        else if($this->cont->getRole()==2){
                            header("Location: index.php?module=commande&association=".$_SESSION['idAsso']."&action=commande");
                            exit;
                        }
                        else if ($this->cont->getRole()==1 || $this->cont->getRole()==4){
                            header("Location: index.php?module=stock&association=".$_SESSION['idAsso']."&action=affiche_stock");
                            exit;
                        }
                    }
                    else{
                        $this->cont->newUtilisateurClient();
                        header("Location: index.php?module=connexion&action=choisirAsso");
                    }
                    
                }
                else{
                    header("Location: index.php?module=connexion&action=redirection");
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
                if($_SESSION["login"]== "admin"){
                    $_SESSION["role"] = 4;
                }
                $asso = $this->cont->getAssos();
                $this->cont->choixAsso($asso);
                break;
            case "newAsso":
                $this->cont->affiche_asso_Temp();
                break;
            default:
                if (!$_SESSION['connecté']) {
                    $this->cont->afficher_formulaire_connexion();
                }
                break;
        }
    }

    public function affiche(){
        return $this->cont->affiche();
    }
}









?>
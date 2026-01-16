<?php
include_once "controleur_commande.php";
class Mod_commande {
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "inscription";

        $this->vue = new Vue_commande();
        $this->cont = new Cont_commande($this->vue);

        switch($action) {
            case 'ajout_debut_commande':
                $this->cont->afficher_formulaire_débutCommande();
                break;

            case 'traiter_debut_commande':
                $this->cont->envoyer_formulaire_débutCommande();
                $this->cont->afficher_formulaire_Commande();
                break;

            case 'ajout_produit':
                    if($this->cont->commande_valide()){

                        $this->cont->envoyer_formulaire_Commande();
                        $this->vue->message("Commande enregistrée !");

                    }
                    else{
                        $this->cont->updatecommande();
                        $this->vue->message("Echec de la commande");
                    }
                break;
                
            case 'prix_total':
                $this->cont->prix_total();
                break;
            case 'finCommande':
                $this->cont->finaliserCommande();
        }
	}


    public function affiche(){
        return $this->cont->affiche();
    }
}

?>

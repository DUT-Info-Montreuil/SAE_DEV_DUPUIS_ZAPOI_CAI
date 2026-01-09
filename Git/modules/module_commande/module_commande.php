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
            case 'début_commande':
                $this->cont->envoyer_formulaire_débutCommande();
                break;
            case 'commande':
                 $this->cont->envoyer_formulaire_Commande();
                 break;
            case 'ajout_debut_commande':
                $this->cont->afficher_formulaire_débutCommande();
               	 break;
            case 'ajout_produit':
                $this->cont->afficher_formulaire_Commande();
                break;
        }
	}

    public function affiche(){
        return $this->cont->affiche();
    }
}









?>

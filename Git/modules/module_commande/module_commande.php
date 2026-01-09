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
        // Étape 1 : Affiche juste le gros bouton "Commande"
        $this->cont->afficher_formulaire_débutCommande();
        break;

    case 'traiter_debut_commande':
        // Étape 2 : L'utilisateur a cliqué, on insère en BDD
        $this->cont->envoyer_formulaire_débutCommande();
        // Puis on affiche la sélection des produits
        $this->cont->afficher_formulaire_Commande();
        break;

    case 'ajout_produit':
        // Étape 3 : L'utilisateur a choisi ses produits, on enregistre les lignes
        $this->cont->envoyer_formulaire_Commande();
        $this->vue->message("Commande enregistrée !");
        break;
}
	}

    public function affiche(){
        return $this->cont->affiche();
    }
}









?>

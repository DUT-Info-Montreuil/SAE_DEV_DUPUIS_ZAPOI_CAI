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
    var_dump( $_SESSION['solde']);
    var_dump($this->cont->prix_total());
            if($this->cont->commande_valide()){

                $this->cont->envoyer_formulaire_Commande();
                $this->vue->message("Commande enregistrée !");
                $this->cont->prix_total(); // pas idéal d'afficher le prix total après avoir passé commande,  à refaire une fois que nous aurons des connaissances en AJAX

            }
            else{
                $this->cont->updatecommande();
                $this->vue->message("Echec de la commande");
            }
        break;


}
	}

    public function affiche(){
        return $this->cont->affiche();
    }
}









?>

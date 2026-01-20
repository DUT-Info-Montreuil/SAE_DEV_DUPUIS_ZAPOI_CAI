<?php
    include_once 'vue_commande.php';
    include_once 'modele_commande.php';

    class Cont_commande{
        private $vue;
        private $modele;
        public function __construct(Vue_commande $vue){
            $this->vue = $vue;
            $this->modele = new Modele_commande();

        }
        //Fonctions de la vue
        public function afficher_formulaire_débutCommande(){
            if ($_SESSION['role']==3) {
                $this->vue->formulaire_début_commande();
            }
            else{
                die("Droit requis non perçu.");
            }
        }
        public function afficher_formulaire_Commande(){
            if ($_SESSION['role']==3) {
                $produits = $this->getProduits();
                $this->vue->formulaire_commande($produits);
            }
            else{
                die("Droit requis non perçu.");
            }
            
        }

        public function envoyer_formulaire_débutCommande(){
            if ($_SESSION['role']==3) {
                $this->modele->ajout_début_commande();
            }
            else{
                die("Droit requis non perçu.");
            }
        }
        public function envoyer_formulaire_Commande(){
            if ($_SESSION['role']==3) {
                $this->modele->ajout_commande();
                $prix = $this->modele->calculerPrixTotalCommande();
                $this->modele->déduireSolde($prix);
            }
            else{
                die("Droit requis non perçu.");
            }
            
        }
        public function prix_total(){
            if ($_SESSION['role']==3) {
                $total=$this->modele->calculerPrixTotalCommande();
                header('Content-Type: application/json');
                echo json_encode(['total' => number_format($total, 2, '.', '')]);
                exit;
            }
            else{
                die("Droit requis non perçu.");
            }

        }
        public function commande_valide() : bool {
            if ($_SESSION['role']==3) {
                $prixAchat = $this->modele->calculerPrixTotalCommande();
            return $this->modele->commandeEstValide($_SESSION['solde'], $prixAchat);
            }
            else{
                die("Droit requis non perçu.");
            }
            
        }
       	public function affiche(){
		return $this->vue->affiche();
       	}
        public function updatecommande(){
            if($_SESSION['role']==3){
                return $this->modele->updatecommande();
            }
            else{
                die("Droit requis non perçu.");
            }
            return vide[null];
        }



        public function getProduits() : array {
            if($_SESSION['role']==3){
                return $this->modele->getProduits();
            }
            else{
                die("Droit requis non perçu.");
            }
            return vide[null];
        }


}
?>

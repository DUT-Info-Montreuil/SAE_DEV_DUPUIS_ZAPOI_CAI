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
                $this->vue->formulaire_début_commande();

        }
        public function afficher_formulaire_Commande(){
                $produits = $this->getProduits();
                $this->vue->formulaire_commande($produits);
        }

        public function envoyer_formulaire_débutCommande(){
            $this->modele->ajout_début_commande();
        }
        public function envoyer_formulaire_Commande(){
            $this->modele->ajout_commande();
            $prix = $this->modele->calculerPrixTotalCommande($_SESSION['idCommandeActuelle']);
            $this->modele->déduireSolde($prix);
        }
        public function prix_total(){
            $total=$this->modele->calculerPrixTotalCommande();
            header('Content-Type: application/json');
            echo json_encode(['total' => number_format($total, 2, '.', '')]);
            exit;

        }
        public function commande_valide() : bool {
            $prixAchat = $this->modele->calculerPrixTotalCommande();

            return $this->modele->commandeEstValide($_SESSION['solde'], $prixAchat);
        }

       	public function affiche(){
		return $this->vue->affiche();
       	}

        public function updatecommande(){
        return $this->modele->updatecommande();
        }

        public function getProduits() : array {
            return $this->modele->getProduits();
        }

        public function annulationCommande($id){
            return $this->modele->annulerCommande($id);
        }
        public function messageAnnulation(){
            return $this->vue->message("Commande annulée");
        }


}
?>

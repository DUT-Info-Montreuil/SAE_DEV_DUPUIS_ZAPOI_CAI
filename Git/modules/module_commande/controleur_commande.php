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
        }
       	public function affiche(){
		return $this->vue->affiche();
       	}



        public function getProduits() : array {
            return $this->modele->getProduits();
        }


}
?>

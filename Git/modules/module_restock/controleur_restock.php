<?php
    include_once 'vue_restock.php';
    include_once 'modele_restock.php';

    class Cont_restock{
        private $vue;
        private $modele;
        public function __construct(Vue_restock $vue){
            $this->vue = $vue;
            $this->modele = new Modele_restock();
        }

        public function recupProduitsAchat(){
            return $this->modele->getProduitsAchat();
        }
        public function afficherProduitsAchat($p){
            return $this->vue->produitsAchat($p);
        }

        public function recupFournisseurs(){
            return $this->modele->getFournisseurs();
        }
        public function afficherFournisseurs($f){
            return $this->vue->fournisseurs($f);
        }

        public function recupProduitsF($id){
            return $this->modele->getProduitsFournisseur($id);
        }
        public function afficherProduitsFournisseur($pf){
            return $this->vue->fournisseurs($pf);
        }

       	public function affiche(){
		return $this->vue->affiche();
       	}

}
?>

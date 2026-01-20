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
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->modele->getProduitsAchat();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }
        public function afficherProduitsAchat($p){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->vue->produitsAchat($p);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function recupFournisseurs(){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->modele->getFournisseurs();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }
        public function afficherFournisseurs($f){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->vue->fournisseurs($f);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function recupProduitsF($id){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->modele->getProduitsFournisseur($id);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }
        public function afficherProduitsFournisseur($pf){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->vue->fournisseurs($pf);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }
        public function ajoutStock($id, $quantite){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->modele->ajoutStock($id, $quantite);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

       	public function affiche(){
		return $this->vue->affiche();
       	}

}
?>

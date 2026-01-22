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

        public function fondsSuffisants($idProd, $quantite, $prix): bool{

            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                $montant_total = 0;
                foreach($idProd as $key => $p){
                    $qte = (int) $quantite[$key];
                    $montant_total += $qte * $prix[$key];
                }
                var_dump($montant_total);
                $fonds = $this->modele->fonds();
                $reste = $fonds - $montant_total;
                if($reste > 0){
                    return true;
                }else{
                    return false;
                }

            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }

        public function ajoutStock($idProd, $quantite){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                foreach($idProd as $key => $p){
                    $qte = (int) $quantite[$key];
                    if($qte > 0){
                        $this->modele->ajoutStock($p, $qte);
                    }
                }
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function ajoutAchat($idProd, $quantite){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){

                $this->modele->ajoutAchat($idProd, $quantite);

            }else{
                 $this->vue->message('Droit requis non perçu.');
             }

        }

       	public function affiche(){
		return $this->vue->affiche();
       	}

}
?>

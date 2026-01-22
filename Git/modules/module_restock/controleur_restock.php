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

        public function fondsSuffisants(): bool{

            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                $montant_total = 0;
                foreach($_POST['produit'] as $p){
                    $montant_total += (int)$p['qte'] * (float)$p['prix'];
                }//TODO vérifier avec le solde de l'asso

                $reste = 2;
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

                        $this->modele->ajoutStock($idProd, $quantite);
                    }


            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function ajoutAchat(){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                $idAchat = $this->modele->ajoutAchat();
                foreach($_POST['produit'] as $p){
                    if($p['qte']>0){
                        $this->modele->ajoutLigneAchat($idAchat,$p);
                    }
                }
                $this->modele->updateAchat();
            }else{
                 $this->vue->message('Droit requis non perçu.');
             }

        }

        public function parcourirLignes($lignes){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                foreach($lignes as $l){
                    $id = $l['id'];
                    $q = $l['quantite'];
                    var_dump($id);
                    if($id && $q){
                        $this->ajoutStock($id, $q);

                    }
                }
            }else{
               $this->vue->message('Droit requis non perçu.');
            }
        }

        public function recupAchats(){
            return $this->modele->getAchats();
        }
        public function afficherAchats($achats){
            return $this->vue->finaliserAchats($achats);
        }

        public function recupDetailsAchat($id){
            return $this->modele->getDetailsAchat($id);
        }
        public function afficherDetailsAchat($achats){
            return $this->vue->detailsAchat($achats);
        }

    public function finaliserAchat($idAchat){
        return $this->modele->finaliserAchat($idAchat);
    }

       	public function affiche(){
		return $this->vue->affiche();
       	}

}
?>

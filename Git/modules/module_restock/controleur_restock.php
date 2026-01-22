<?php
include_once 'vue_restock.php';
include_once 'modele_restock.php';
include_once 'utils/token_csrf.php';

class Cont_restock {
    private $vue;
    private $modele;
    private $checker_csrf;

    public function __construct(Vue_restock $vue) {
        $this->vue = $vue;
        $this->modele = new Modele_restock();
        $this->checker_csrf = new Token_CSRF();
    }

    public function recupProduitsAchat() {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            return $this->modele->getProduitsAchat();
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function afficherProduitsAchat($p) {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            return $this->vue->produitsAchat($p);
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function recupFournisseurs() {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            return $this->modele->getFournisseurs();
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function afficherFournisseurs($f) {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            return $this->vue->fournisseurs($f);
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function recupProduitsF($id) {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            return $this->modele->getProduitsFournisseur($id);
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function afficherProduitsFournisseur($pf) {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            return $this->vue->fournisseurs($pf);
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }


    public function ajoutStock($id, $quantite) {
        if ($this->checker_csrf->check_csrf()) {
            if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
                return $this->modele->ajoutStock($id, $quantite);
            } else {
                $this->vue->message('Droit requis non perçu.');
            }
        } else {
            $this->vue->message("Token invalide");

        }
    }

        public function fondsSuffisants($idProd, $quantite, $prix): bool{

            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                $montant_total = 0;
                foreach($idProd as $key => $p){
                    $qte = (int) $quantite[$key];
                    $montant_total += $qte * $prix[$key];
                }

                $fonds = $this->modele->getTresorerie();
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





        public function ajoutAchat($idProd, $quantite, $prix,  $idF){
            if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                foreach($idF as $indF => $f){
                    $idAchat = $this->modele->ajoutAchat($idF[$indF]);
                    foreach($idProd as $indP => $p){
                        $qte = (int) $quantite[$indP];
                        $prix_la = (int) $prix[$indP];
                        $this->modele->ajoutLigneAchat($idAchat, $p, $qte, $prix_la);
                    }
                }
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
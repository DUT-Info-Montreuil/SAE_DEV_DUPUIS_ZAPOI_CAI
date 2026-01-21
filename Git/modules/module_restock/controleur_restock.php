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

    public function affiche() {
        return $this->vue->affiche();
    }
}
?>
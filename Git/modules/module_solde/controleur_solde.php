<?php
include_once 'vue_solde.php';
include_once 'modele_solde.php';
include_once 'utils/token_csrf.php';

class Cont_solde {
    private $vue;
    private $modele;
    private $checker_csrf;

    public function __construct(Vue_solde $vue) {
        $this->vue = $vue;
        $this->modele = new Modele_solde();
        $this->checker_csrf = new Token_CSRF();
    }

    public function afficher_formulaire_solde() {
        if ($_SESSION['role'] == 3) {
            $this->vue->formulaire_solde();
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function afficher_solde($solde) {
        if ($_SESSION['role'] == 3) {
            $this->vue->afficheSolde($solde);
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }


    public function afficher_page_solde() {
        if ($_SESSION['role'] == 3) {
            $solde = $this->modele->getSolde();
            $this->vue->afficheSolde($solde);
            $this->vue->formulaire_solde();
        }
        else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

        public function afficher_soldeAsso(){
            if($_SESSION['role']==1 || $_SESSION['role']==4 ){
                $this->vue->afficheSolde($this->modele->getSoldeAsso());
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }


    public function envoyer_formulaire_solde() {
        if ($this->checker_csrf->check_csrf()) {
            if ($_SESSION['role'] == 3) {
                $message = $this->modele->ajout_formulaire_solde();
                $this->vue->message($message);
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
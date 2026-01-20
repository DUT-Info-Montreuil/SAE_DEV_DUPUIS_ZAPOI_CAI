<?php
    include_once 'vue_solde.php';
    include_once 'modele_solde.php';

    class Cont_solde{
        private $vue;
        private $modele;
        public function __construct(Vue_solde $vue){
            $this->vue = $vue;
            $this->modele = new Modele_solde();

        }

        //Fonctions de la vue
        public function afficher_formulaire_solde(){
            if($_SESSION['role']==3){
                $this->vue->formulaire_solde();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function afficher_solde($solde){
            if($_SESSION['role']==3){
                $this->vue->afficheSolde($solde);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function afficher_page_solde(){
            if($_SESSION['role']==3){
                $solde = $this->modele->getSolde();
                $this->vue->afficheSolde($solde);
                $this->vue->formulaire_solde();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }

        //Fonctions du modèle
        public function envoyer_formulaire_solde(){
            if($_SESSION['role']==3){
                $message = $this->modele->ajout_formulaire_solde();
                $this->vue->message($message);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }

        public function affiche() {
                return $this->vue->affiche();
        }



}
?>
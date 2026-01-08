<?php
    include_once 'vue_recapJournee.php';
    include_once 'modele_recapJournee.php';

    class controleur_recapJournee{
        private $vue;
        private $modele;
        public function __construct(){
            $this->vue = new vue_recapJournee);
            $this->modele = new modele_recapJournee();

        }
    }
        //Fonctions de la vue

    public function affiche() {
            return $this->vue->affiche();
    }

    public function getRecap() {
            return $this->modele->getRecetteJournee();
    }

    public function afficheRecap() {
            return $this->vue->recapitulatif();
    }





}
?>
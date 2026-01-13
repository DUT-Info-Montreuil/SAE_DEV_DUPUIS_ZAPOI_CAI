<?php
    include_once 'vue_recapJournee.php';
    include_once 'modele_recapJournee.php';

    class controleur_recapJournee{
        private $vue;
        private $modele;
        public function __construct(){
            $this->vue = new vue_recapJournee();
            $this->modele = new modele_recapJournee();

        }

        //Fonctions de la vue

    public function affiche() {
            return $this->vue->affiche();
    }

    public function getRecap(int $recette) {
            return $this->modele->getRecetteJournee($recette);
    }
        public function afficheRecap(array $recette) {
            return $this->vue->benefDuJour($recette);
    }

    public function getRecapSemaine() {
            return $this->modele->getRecapSemaine();
    }
    public function afficheRecapSemaine(array $semaine) {
            return $this->vue->recap_semaine($semaine);
    }

    public function getTransactions(int $jour) {
            return $this->modele->getTransactions($jour);
    }
    public function afficheTransactions(array $transactions) {
            return $this->vue->transactionsDuJour($transactions);
    }

    public function getProduitsVendus(int $jour) {
            return $this->modele->getProdVendus($jour);
    }
    public function afficheProduitsVendus(array $transactions) {
            return $this->vue->ProduitsVendus($transactions);
    }

    public function getMoyenneRecetteJour(int $jour) {
            return $this->modele->getMoyenneRecetteJour($jour);
    }
    public function afficheMoyenneRecetteJour(float $moy) {
            return $this->vue->moyenneRecetteJour($moy);
    }



}
?>
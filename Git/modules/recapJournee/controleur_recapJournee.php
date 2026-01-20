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
        if($_SESSION['role']==1){

        }
        else{
                die("Droit requis non perçu.");
        }
            return $this->modele->getRecetteJournee($recette);
    }
        public function afficheRecap(array $recette) {
                if($_SESSION['role']==1){

        }
        else{
                die("Droit requis non perçu.");
        }
            return $this->vue->benefDuJour($recette);
    }

    public function getRecapSemaine() {
        if($_SESSION['role']==1){

        }
        else{
                die("Droit requis non perçu.");
        }
            return $this->modele->getRecapSemaine();
    }
    public function afficheRecapSemaine(array $semaine) {
        if($_SESSION['role']==1){

        }
        else{
                die("Droit requis non perçu.");
        }
            return $this->vue->recap_semaine($semaine);
    }

    public function getTransactions(int $jour) {
        if($_SESSION['role']==1 && $_SESSION['role'] == 4){
                return $this->modele->getTransactions($jour);
        }
        else{
                die("Droit requis non perçu.");
        }
            
    }
    public function afficheTransactions(array $transactions) {
        if($_SESSION['role']==1 && $_SESSION['role'] == 4){
                 return $this->vue->transactionsDuJour($transactions);
        }
        else{
                die("Droit requis non perçu.");
        }
           
    }

    public function getProduitsVendus(int $jour) {
        if($_SESSION['role']==1 && $_SESSION['role'] == 4){
                return $this->modele->getProdVendus($jour);
        }
        else{
                die("Droit requis non perçu.");
        }
            
    }
    public function afficheProduitsVendus(array $transactions) {
        if($_SESSION['role']==1 && $_SESSION['role'] == 4){
                return $this->vue->ProduitsVendus($transactions);
        }
        else{
                die("Droit requis non perçu.");
        }
            
    }

    public function getMoyenneRecetteJour(int $jour) {
        if($_SESSION['role']==1 && $_SESSION['role'] == 4){
                return $this->modele->getMoyenneRecetteJour($jour);
        }
        else{
                die("Droit requis non perçu.");
        }
            
    }
    public function afficheMoyenneRecetteJour(float $moy) {
        if($_SESSION['role']==1 && $_SESSION['role'] == 4){
                return $this->vue->moyenneRecetteJour($moy);
        }
        else{
                die("Droit requis non perçu.");
        }
            
    }



}
?>
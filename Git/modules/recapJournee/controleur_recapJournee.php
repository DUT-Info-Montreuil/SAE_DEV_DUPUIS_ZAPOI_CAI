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

    public function getRecap($recette) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->modele->getRecetteJournee($recette);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }

    public function afficheRecap(array $recette) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->vue->benefDuJour($recette);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }

    public function getRecapSemaine() {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->modele->getRecapSemaine();
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }
    public function afficheRecapSemaine(array $semaine) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->vue->recap_semaine($semaine);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }

    public function getTransactions(int $jour) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->modele->getTransactions($jour);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
            
    }


    public function getProduitsVendus(int $jour) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
                return $this->modele->getProdVendus($jour);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
            
    }
    public function afficheProduitsVendus(array $transactions) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->vue->ProduitsVendus($transactions);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
            
    }

    public function getMoyenneRecetteJour(int $jour) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->modele->getMoyenneRecetteJour($jour);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
            
    }
    public function afficheMoyenneRecetteJour(float $moy, array $transactions) {
        if($_SESSION['role']==1 || $_SESSION['role'] == 4){
            return $this->vue->moyenneRecetteJour($moy,$transactions);
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
            
    }



}
?>
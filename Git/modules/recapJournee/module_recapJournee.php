<?php
include_once "controleur_recapJournee.php";
Class module_recapJournee{
    private $cont;

    public function __construct(){

        $action = $_GET['action'] ?? "recapDuJour";
        $this->cont = new controleur_recapJournee();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "recapDuJour" ;

        switch($action) {
            case "recapDuJour":
                $jour = 0;
                $recette = $this->cont->getRecap($jour);
                $this->cont->afficheRecap($recette);

                $recapSemaine = $this->cont->getRecapSemaine();
                $this->cont->afficheRecapSemaine($recapSemaine);

                $moyenne = $this->cont->getMoyenneRecetteJour($jour);
                $this->cont->afficheMoyenneRecetteJour($moyenne);
                $transactions = $this->cont->getTransactions($jour);
                $this->cont->afficheTransactions($transactions);

                $produits = $this->cont->getProduitsVendus($jour);
                $this->cont->afficheProduitsVendus($produits);

                break;

            case "recapCertainJour":

                $jour = $_GET['jour'] ?? "0";

                $moyenne = $this->cont->getMoyenneRecetteJour($jour);
                $this->cont->afficheMoyenneRecetteJour($moyenne);
                $transactions = $this->cont->getTransactions($jour);
                $this->cont->afficheTransactions($transactions);

                $produits = $this->cont->getProduitsVendus($jour);
                $this->cont->afficheProduitsVendus($produits);

                break;
        }

    }

    public function affiche(){
        return $this->cont->affiche();

    }


}






?>

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
                $transactions = $this->cont->getTransactions($jour);
                $this->cont->afficheMoyenneRecetteJour($moyenne,$transactions);
                
                $produits = $this->cont->getProduitsVendus($jour);
                $this->cont->afficheProduitsVendus($produits);

                $this->cont->afficheEcart();

                $this->cont->afficheEcartJours();
                break;

            case "recapCertainJour":

                $jour = $_GET['jour'] ?? "0";

                $moyenne = $this->cont->getMoyenneRecetteJour($jour);
                $transactions = $this->cont->getTransactions($jour);
                $this->cont->afficheMoyenneRecetteJour($moyenne,$transactions);
                
                

                $produits = $this->cont->getProduitsVendus($jour);
                $this->cont->afficheProduitsVendus($produits);

                break;
            case "ecartStockEntre2J":
                $liste = $this->cont->getEcartJ1J2();
                $this->cont->afficheEcartJ1J2($liste);
                break;



        }

    }

    public function affiche(){
        return $this->cont->affiche();

    }


}






?>
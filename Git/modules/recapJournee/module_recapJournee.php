<?php
include_once "controleur_recapJournee.php";
Class module_recapJournee{
    private $cont;

    public function __construct(){

        $action = $_GET['action'] ?? "recap";
        $this->cont = new controleur_recapJournee();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "recap" ;

        switch($action) {
            case "recap":
                $jour = 0;
                $recette = $this->cont->getRecap($jour);
                $this->cont->afficheRecap($recette);
                break;
            case "recapSemaine":
                $recapSemaine = $this->cont->getRecapSemaine();
                $this->cont->afficheRecapSemaine($recapSemaine);
                break;
            case "transactions":
                $transactions = $this->cont->getTransactions();
                $this->cont->afficheTransactions($transactions);
                break;
            case "produitsVendus":
                $produits = $this->cont->getProduitsVendus();
                $this->cont->afficheProduitsVendus($produits);
                break;
        }

    }

    public function affiche(){
        return $this->cont->affiche();

    }


}






?>

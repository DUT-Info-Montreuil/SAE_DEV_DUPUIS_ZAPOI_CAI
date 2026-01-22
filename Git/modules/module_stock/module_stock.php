<?php
include_once 'controleur_stock.php';
include_once 'vue_stock.php';
Class Mod_stock{
    private $cont;
    private $vue;
    public function __construct(){

        $action = $_GET['action'] ?? "";
        $this->vue = new Vue_stock();
        $this->cont = new Cont_stock($this->vue);

        switch($action){
            case "affiche_stock":
                $this->cont->affiche_stock();

                $this->cont->affiche_stockFaible();
                break;
            case "menu":
                $this->cont->affiche_menu();
                break;
            case "changeInfoMenu":
                $this->cont->changement();
                break;
            case "afficheProdAjouter":
                $this->cont->afficheProduit();
                break;
            case "ajouteProduit":
                $this->cont->ajouterNewProduit();
                break;
            case "creeInventaire":
                $this->cont->creeInventaire();
                break;
            case"recherche":
                $this->cont->getRecherche();
                break;
            case "deduireStock": // TODO vérifier si on a assez dans le stock
                $this->cont->deduireStock();
                break;
        }

    }

    public function affiche(){
        return $this->cont->affiche();

    }

}

?>

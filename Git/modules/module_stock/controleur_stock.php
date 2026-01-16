<?php
include_once 'vue_stock.php';
include_once 'modele_stock.php';

class Cont_stock{
    private $vue;
    private $modele;
    public function __construct(Vue_stock $vue){
        $this->vue = $vue;
        $this->modele = new Modele_stock();
    }
    //Fonctions de la vue
    public function affiche(){
            return$this->vue->affiche();
    }

    public function affiche_stock() {
        $this->vue->afficheStock($this-> getStock());
    }

    //Fonctions du modèle
    public function getStock(): array{
        return $this->modele->getStock();
    }
    public function getRecherche(){
        $recherche=$_POST['rechercher'] ?? "";
        $res=$this->modele->getRecherche($recherche);
        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }

    


}
?>
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
        if($_SESSION['role']==1){
            $this->vue->afficheStock($this-> getStock());
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }

    //Fonctions du modèle
    public function getStock(): array{
        if($_SESSION['role']==1){
            return $this->modele->getStock();
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
        
    }
    public function getRecherche(){
        if($_SESSION['role']==1){
            $recherche=$_POST['rechercher'] ?? "";
            $res=$this->modele->getRecherche($recherche);
            header('Content-Type: application/json');
            echo json_encode($res);
            exit;
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }
    public function deduireStock(){
        if($_SESSION['role']==3){
            $this->modele->deduireStock();
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }

}
?>
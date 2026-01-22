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
        if($_SESSION['role']==1 || $_SESSION['role']==4){
            if($this->modele->stockExistePas()){
                $this->vue->afficheInitStockJour($this ->modele-> getStockArrivage());
            }
            else{
                $this->vue->afficheStock($this-> getStock());
            }

        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }
    public function affiche_stockFaible() {
        if($_SESSION['role']==1 || $_SESSION['role']==4){
            $this->vue->afficheNBFaible($this-> modele -> getNBProduitFaible());
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }

    }

    public function afficheProduit(){
        if($_SESSION['role']==1 || $_SESSION['role']==4){
            $this->vue->afficheProduit($this->modele->getHorsMenu());
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function creeInventaire(){
        if($_SESSION['role']==1 || $_SESSION['role']==4){
            $this->modele->creeInventaire();

        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
        header("Location: index.php?module=stock&action=affiche_stock");
    }

    //Fonctions du modèle
    public function getStock(): array{
        if($_SESSION['role']==1 || $_SESSION['role']==4){
            return $this->modele->getStock();
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
        
    }
    public function getRecherche(){
        if($_SESSION['role']==1 || $_SESSION['role']==4){
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
        if($_SESSION['role']==2){
            $this->modele->deduireStock();
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function affiche_menu(){
        if($_SESSION['role']==1){
            $this->vue->affiche_menu($this->modele->getMenu());
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function changement(){
        if($_SESSION['role']==1 && isset($_POST['produit'])){
            $prod=$_POST['produit'];
            foreach($prod as $item){
                $this->modele->changeInfo($item['idProd'],$item['prix'],$item['seuil']);
            }
            header("Location: index.php?module=stock&action=affiche_stock");
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }
    public function ajouterNewProduit(){
        if($_SESSION['role']==1){
            if(isset($_POST['produit'])){
                $prod = $_POST['produit'];
                foreach($prod as $item){
                    $this->modele->ajouterProduitMenu($item['idProd'],$item['prix'],$item['seuil']);
                }
            }
            header("Location: index.php?module=stock&action=affiche_stock"); 
        }
        else{
            $this->vue->message('Droit requis non perçu.');
        }
    }

}
?>
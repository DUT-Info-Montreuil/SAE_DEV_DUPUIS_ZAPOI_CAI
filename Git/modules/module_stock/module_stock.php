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
                $this->affiche_stock();
                break;
        }
    }
    public function affiche_stock() {
        $this->cont->affiche_stock();
    }
    public function affiche(){
        return $this->cont->affiche();

    }

}

?>

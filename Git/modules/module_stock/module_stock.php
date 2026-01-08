<?php
include_once 'controleur_stock.php';
Class Mod_stock{
    private $cont;

    public function __construct(){

        $action = $_GET['action'] ?? "";
        $this->cont = new Cont_stock();
        $this->cont->affiche_stock();
    }
    public function affiche(){
        return $this->cont->affiche();

    }

}

?>

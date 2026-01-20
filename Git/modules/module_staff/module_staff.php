<?php
include_once("controleur_staff.php");
include_once("vue_staff.php");
class Module_staff{
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "inscription";

        $this->vue = new Vue_staff();
        $this->cont = new Cont_staff($this->vue);
        $this->action = $action;

        switch($action) {
            case "gestionMembre":
                $this->cont->gestionMembre();
                break;
            case "promoteMembre":
                $this->cont->promoteMembre();
                break;
        }
    }

    public function affiche(){
        return $this->cont->affiche();
    }
}
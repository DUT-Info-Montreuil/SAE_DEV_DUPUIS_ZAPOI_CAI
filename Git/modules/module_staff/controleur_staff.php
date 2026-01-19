<?php
include_once("modele_staff.php");
include_once("vue_staff.php");
class Cont_staff{
    private $vue;
    private $modele;
    public function __construct($vue){
        $this->vue = $vue;
        $this->modele = new Mod_staff();
    }

    public function gestionMembre(){
        $membres = $this->modele->getMembres();
        $this->vue->listeMembre($membres);
    }

    public function promoteMembre(){
        if(isset($_POST['role']) && isset($_POST['choix'])){
            $role = $_POST['role'];
            foreach($_POST['choix'] as $idCompte){
                $this->modele->promoteMembre($idCompte, $role);
            }
        }
        $this->gestionMembre();
    }
    public function affiche(){
        return $this->vue->affiche();
    }
}
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
        if($_SESSION['role']==1 || $_SESSION['role']==4){
            $membres = $this->modele->getMembres();
            $this->vue->listeMembre($membres);
        }
        else{
            die("Droit requis non perçu.");
        }
        
    }

    public function promoteMembre(){
        if($_SESSION['role']==1){
            if(isset($_POST['role']) && isset($_POST['choix'])){
                $role = $_POST['role'];
                foreach($_POST['choix'] as $idCompte){
                    $this->modele->promoteMembre($idCompte, $role);
                }
            }
            $this->gestionMembre();
        }
        else{
            die("Droit requis non perçu.");
        }
        
    }
    public function affiche(){
        return $this->vue->affiche();
    }
}
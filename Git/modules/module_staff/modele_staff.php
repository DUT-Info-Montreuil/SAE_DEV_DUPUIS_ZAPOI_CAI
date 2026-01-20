<?php
include_once("utils/connexion.php");

Connexion::initConnexion();

class Mod_staff extends Connexion{
    private $vue;
    private $cont;

    public function __construct(){
        
    }

    public function getMembres(){
        $sql=self::$bdd->prepare("SELECT * FROM utilisateur NATURAL JOIN compte NATURAL JOIN role WHERE idRole!=4 AND idAsso = ? AND idCompte != ? ORDER BY idRole ASC");
        $sql->execute([$_SESSION["idAsso"], $_SESSION["idCompte"]]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function promoteMembre($idCompte, $role){
        $sql=self::$bdd->prepare("UPDATE utilisateur SET idRole = ? WHERE idCompte = ?");
        $sql->execute([$role, $idCompte]);
    }
}
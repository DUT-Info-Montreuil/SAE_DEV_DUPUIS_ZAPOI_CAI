<?php
include_once("utils/connexion.php");


Connexion::initConnexion();
class Modele_stock extends Connexion{

    public function __construct(){

    }
    
    public function getRecherche(): array{//TODO
        $sql = self::$bdd->prepare("SELECT idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits WHERE");
        $sql->execute();
        $resultatRecherche = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultatRecherche;
    }

    public function getStock(): array{
        $selectStock = self::$bdd->prepare("SELECT stock.idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits");
        $selectStock->execute();
        $resultStock = $selectStock->fetchAll(PDO::FETCH_ASSOC);
        return $resultStock;
    }

    public function getNBProduit(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as nb FROM stock NATURAL JOIN produits ");
        $selectStock->execute();
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['nb'];
    }
    public function getNBProduitDisponible(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as dispo FROM stock NATURAL JOIN produits WHERE quantite >= seuil");
        $selectStock->execute();
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['dispo'];
    }
    public function getNBProduitFaible(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as faible FROM stock NATURAL JOIN produits WHERE quantite < seuil");
        $selectStock->execute();
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['faible'];
    }

}
?>

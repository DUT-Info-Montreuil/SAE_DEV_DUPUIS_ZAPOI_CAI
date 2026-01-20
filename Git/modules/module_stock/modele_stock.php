<?php
include_once("utils/connexion.php");


Connexion::initConnexion();
class Modele_stock extends Connexion{

    public function __construct(){

    }
    
    public function getRecherche($nom_produit): array{
        $sql = self::$bdd->prepare("SELECT idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits NATURAL JOIN inventaire NATURAL JOIN menu WHERE nom LIKE ? AND idAsso = ?");
        $sql->execute(["%$nom_produit%",$_SESSION['idAsso']]);
        $resultatRecherche = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultatRecherche;
    }

    public function getStock(): array{
        $selectStock = self::$bdd->prepare("SELECT stock.idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits NATURAL JOIN inventaire NATURAL JOIN menu WHERE idAsso = ? ORDER BY (quantite < seuil) DESC");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetchAll(PDO::FETCH_ASSOC);
        return $resultStock;
    }

    public function getNBProduit(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as nb FROM stock NATURAL JOIN produits NATURAL JOIN inventaire ");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['nb'];
    }


public function deduireStock() {
    $idCom = $_POST['idCommande'] ?? null;
    if ($idCom) {
        $sql = self::$bdd->prepare("SELECT idProd, quantite FROM lignecommande WHERE idCommande = ?");
        $sql->execute([$idCom]);
        $produits = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sqlUpdate = self::$bdd->prepare("UPDATE stock SET quantite = quantite - ? WHERE idProd = ?");
        foreach ($produits as $p) {
            $sqlUpdate->execute([$p['quantite'], $p['idProd']]);
        }
    }
}
}
?>

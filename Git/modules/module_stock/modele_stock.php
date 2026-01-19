<?php
include_once("utils/connexion.php");


Connexion::initConnexion();
class Modele_stock extends Connexion{

    public function __construct(){

    }
    
    public function getRecherche($nom_produit): array{
        $sql = self::$bdd->prepare("SELECT idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits WHERE nom LIKE ?");
        $sql->execute(["%$nom_produit%"]);
        $resultatRecherche = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultatRecherche;
    }

    public function getStock(): array{
        $selectStock = self::$bdd->prepare("SELECT stock.idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits NATURAL JOIN inventaire WHERE idAsso = ? ORDER BY (quantite < seuil) DESC");
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
    public function getNBProduitDisponible(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as dispo FROM stock NATURAL JOIN produits NATURAL JOIN inventaire WHERE idAsso = ? AND quantite >= seuil");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['dispo'];
    }
    public function getNBProduitFaible(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as faible FROM stock NATURAL JOIN produits NATURAL JOIN inventaire WHERE idAsso = ? AND quantite < seuil");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['faible'];
    }

public function deduireStock() {
    // 1. On récupère l'ID envoyé par le JS (on utilise 'idCommande')
    $idCom = $_POST['idCommande'] ?? null;
    if ($idCom) {
        // 2. On récupère les produits et quantités liés à cette commande
        // Note : Vérifiez bien si votre colonne s'appelle 'qte_produit' ou 'quantite' dans lignecommande
        $sql = self::$bdd->prepare("SELECT idProd, quantite FROM lignecommande WHERE idCommande = ?");
        $sql->execute([$idCom]);
        $produits = $sql->fetchAll(PDO::FETCH_ASSOC);

        // 3. On prépare la mise à jour du stock
        $sqlUpdate = self::$bdd->prepare("UPDATE stock SET quantite = quantite - ? WHERE idProd = ?");

        foreach ($produits as $p) {
            // 4. On déduit directement la quantité achetée du stock actuel
            $sqlUpdate->execute([$p['quantite'], $p['idProd']]);
        }
    }
    exit();
}
}
?>

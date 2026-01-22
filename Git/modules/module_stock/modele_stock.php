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

    public function getNBProduitFaible(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as nb FROM stock NATURAL JOIN produits NATURAL JOIN inventaire NATURAL JOIN menu WHERE idAsso = ? AND quantite<seuil");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['nb'];
    }

    public function getMenu(){
        $selectMenu = self::$bdd->prepare("SELECT nom,prix,seuil,idProd FROM menu NATURAL JOIN produits WHERE idAsso = ?");
        $selectMenu->execute([$_SESSION['idAsso']]);
        $resultatMenu = $selectMenu->fetchAll(PDO::FETCH_ASSOC);
        return $resultatMenu;
    }

    public function getHorsMenu(){
        $selectMenu = self::$bdd->prepare("SELECT DISTINCT idProd, nom FROM stock NATURAL JOIN produits NATURAL JOIN inventaire WHERE idAsso = ? AND idProd NOT IN (SELECT idProd FROM menu WHERE idAsso = ?)");
        $selectMenu->execute([$_SESSION['idAsso'],$_SESSION['idAsso']]);
        $resultatMenu = $selectMenu->fetchAll(PDO::FETCH_ASSOC);
        return $resultatMenu;
    }

    public function ajouterProduitMenu($id,$prix,$seuil){
        $sql = self::$bdd->prepare("INSERT INTO menu (idProd,idAsso,prix,seuil) VALUES (?,?,?,?)");
        $sql -> execute([$id,$_SESSION['idAsso'],$prix,$seuil]);
    }

    public function changeInfo($idProd,$prix,$seuil){
        $sql = self::$bdd->prepare("UPDATE menu SET prix = ?, seuil = ? WHERE idAsso = ? AND idProd = ?");
        $sql -> execute([$prix,$seuil,$_SESSION['idAsso'],$idProd]);
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

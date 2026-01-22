<?php
include_once("utils/connexion.php");


Connexion::initConnexion();
class Modele_stock extends Connexion{

    public function __construct(){

    }
    
    public function getRecherche($nom_produit): array{
        $sql = self::$bdd->prepare("SELECT idProd,nom,quantite,seuil FROM stock NATURAL JOIN produits NATURAL JOIN inventaire NATURAL JOIN menu WHERE nom LIKE ? AND idAsso = ? AND date = CURRENT_DATE");
        $sql->execute(["%$nom_produit%",$_SESSION['idAsso']]);
        $resultatRecherche = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultatRecherche;
    }

    public function getStockArrivage(){
        $selectStock = self::$bdd->prepare("SELECT idProd,nom,quantite FROM stock NATURAL JOIN produits NATURAL JOIN inventaire WHERE idAsso = ? AND date = CURRENT_DATE-1");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetchAll(PDO::FETCH_ASSOC);
        return $resultStock;
    }

    public function stockExistePas(){
        $sql = self::$bdd->prepare("SELECT * FROM inventaire WHERE idAsso = ? AND date = CURRENT_DATE");
        $sql->execute([$_SESSION['idAsso']]);
        $donne = $sql-> fetchAll(PDO::FETCH_ASSOC);
        return $donne==null;
    }

    public function creeInventaire(){
        $sqlInventaire = self::$bdd->prepare("INSERT INTO inventaire (date, idAsso) VALUES (CURRENT_DATE, ?)");
        $sqlInventaire->execute([$_SESSION['idAsso']]);

        $idInv = self::$bdd->lastInsertId();

        $prod = $_POST['produit'];

        foreach($prod as $p){
            $sql = self::$bdd->prepare("
                    INSERT INTO stock (quantite, qteInit, idProd, idinventaire) VALUES (?, ?, ?, ?)
            ");

            $sql->execute([$p['qteArrive'], $p['qteArrive'], $p['idProd'], $idInv]);
        }

    }

    public function getStock(): array{
        $selectStock = self::$bdd->prepare(
        "SELECT stock.idProd,nom,quantite,seuil
        FROM stock NATURAL JOIN produits p NATURAL JOIN inventaire LEFT OUTER JOIN (SELECT idProd,seuil FROM menu WHERE idAsso = ?) s ON p.idProd=s.idProd
        WHERE idAsso = ? AND date = CURRENT_DATE
        ORDER BY (quantite < seuil) DESC");
        $selectStock->execute([$_SESSION['idAsso'],$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetchAll(PDO::FETCH_ASSOC);
        return $resultStock;
    }

    public function getNBProduit(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as nb FROM stock NATURAL JOIN produits NATURAL JOIN inventaire  AND date = CURRENT_DATE");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['nb'];
    }

    public function getNBProduitFaible(): int{
        $selectStock = self::$bdd->prepare("SELECT count(*) as nb FROM stock NATURAL JOIN produits NATURAL JOIN inventaire NATURAL JOIN menu WHERE idAsso = ? AND date = CURRENT_DATE AND (quantite < seuil) ");
        $selectStock->execute([$_SESSION['idAsso']]);
        $resultStock = $selectStock->fetch(PDO::FETCH_ASSOC);
        return (int)$resultStock['nb'];
    }




    public function deduireStock() {
        $idCom = $_POST['idCommande'] ?? null;
        if ($idCom) {
            $sql = self::$bdd->prepare("SELECT idProd, quantite FROM lignecommande WHERE idCommande = ? AND date = CURRENT_DATE");
            $sql->execute([$idCom]);
            $produits = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sqlUpdate = self::$bdd->prepare("UPDATE stock SET quantite = quantite - ? WHERE idProd = ?");
            foreach ($produits as $p) {
                $sqlUpdate->execute([$p['quantite'], $p['idProd']]);
            }
        }
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

    public function retirerProduitMenu($idProd){
        $sql = self::$bdd->prepare("DELETE FROM menu WHERE idProd = ? AND idAsso = ? ");
        $sql -> execute([$idProd,$_SESSION['idAsso']]);
    }
}
?>

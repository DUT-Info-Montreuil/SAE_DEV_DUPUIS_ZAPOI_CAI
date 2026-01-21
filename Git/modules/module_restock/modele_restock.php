<?php
include_once("utils/connexion.php"); // fichier de connexion à la base

Connexion::initConnexion();

class Modele_restock extends Connexion {

    public function __construct() {
        // constructeur vide
    }


    public function getProduitsAchat() {
        $sql = "
            SELECT
                p.nom as nom ,
                p.idProd ,
                pf.prix / 100 as prix,
                p.image as image,
                f.nom as fournisseur,
                f.idFournisseur as idF,
                i.idInventaire 
            FROM
                produits p JOIN prod_fournisseur pf ON p.idProd = pf.idProd
                JOIN fournisseur f ON pf.idFournisseur = f.idFournisseur
                JOIN stock s ON s.idProd = p.idProd 
                JOIN inventaire i ON i.idInventaire = s.idInventaire
            WHERE
                i.date = CURRENT_DATE
            GROUP BY p.idProd, f.idFournisseur            
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->execute();

        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $produits;

    }

    public function getProduitsFournisseur($id) {
        $sql = "
            SELECT
                p.nom as nom ,
                p.idProd ,
                pf.prix / 100 as prix,
                p.image as image,
                f.nom as fournisseur,
                f.idFournisseur as idF,
                i.idInventaire 
            FROM
                produits p JOIN prod_fournisseur pf ON p.idProd = pf.idProd
                JOIN fournisseur f ON pf.idFournisseur = f.idFournisseur
                JOIN stock s ON s.idProd = p.idProd 
                JOIN inventaire i ON i.idInventaire = s.idInventaire
            WHERE
                pf.idFournisseur = :id
                AND i.date = CURRENT_DATE
            GROUP BY p.idProd, f.idFournisseur
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $produits;

    }

    public function getFournisseurs(){

        $sql = "
            SELECT
                idFournisseur as id,
                nom
            FROM
                fournisseur
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->execute();

        $f = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $f;

    }

    public function ajoutStock($idProd, $idInventaire, $quantite){

        $sql_q = "SELECT quantite FROM stock NATURAL JOIN inventaire WHERE idProd = :idProd AND idInventaire = :idInventaire";

        $stmt = self::$bdd->prepare($sql_q);
        $stmt->bindParam(':idProd', $idProd);
        $stmt->bindParam(':idInventaire', $idInventaire);
        $stmt->execute();
        $quantiteCourante = $stmt->fetchColumn();

        $quantiteFinale = $quantite + $quantiteCourante;

        $sql = "UPDATE stock SET quantite = :q WHERE idProd = :idProd AND idInventaire = :idInventaire";

        $stmt2 = self::$bdd->prepare($sql);
        $stmt2->bindParam(':q', $quantiteFinale);
        $stmt2->bindParam(':idProd', $idProd);
        $stmt2->bindParam(':idInventaire', $idInventaire);
        $stmt2->execute();

    }

}





?>

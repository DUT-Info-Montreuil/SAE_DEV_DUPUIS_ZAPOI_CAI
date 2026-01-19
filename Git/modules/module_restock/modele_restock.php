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
                p.prix / 100 as prix,
                p.image as image,
                f.nom as fournisseur
            FROM
                produits p JOIN fournisseur f ON p.idFournisseur = f.idFournisseur
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
                p.prix / 100 as prix,
                p.image as image,
                f.nom as fournisseur
            FROM
                produits p JOIN fournisseur f ON p.idFournisseur = f.idFournisseur
            WHERE
                p.idFournisseur = :id
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

    public function ajoutStock($id, $quantite){

        $sql_q = "SELECT quantite FROM stock WHERE idProd = :id";

        $stmt = self::$bdd->prepare($sql_q);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quantiteCourante = $stmt->fetchColumn();

        $quantiteFinale = $quantite + $quantiteCourante;

        $sql = "UPDATE stock SET quantite = :q WHERE idProd = :id ";

        $stmt2 = self::$bdd->prepare($sql);
        $stmt2->bindParam(':q', $quantiteFinale);
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();

    }

}





?>

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

}





?>

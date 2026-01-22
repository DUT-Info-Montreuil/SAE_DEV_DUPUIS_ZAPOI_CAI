<?php
include_once("utils/connexion.php"); // fichier de connexion à la base

Connexion::initConnexion();

class Modele_restock extends Connexion {

    public function __construct() {
        // constructeur vide
    }


    public function getProduitsAchat() {
        $sql = "
            SELECT DISTINCT
                p.idProd ,
                f.idFournisseur as idF,
                p.nom as nom ,
                pf.prix / 100 as prix,
                p.image as image,
                f.nom as fournisseur
            FROM
                produits p JOIN prod_fournisseur pf ON p.idProd = pf.idProd
                JOIN fournisseur f ON pf.idFournisseur = f.idFournisseur

        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->execute();

        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $produits;

    }

    public function getProduitsFournisseur($id) {
        $sql = "
            SELECT DISTINCT
                p.nom as nom ,
                p.idProd ,
                pf.prix / 100 as prix,
                p.image as image,
                f.nom as fournisseur,
                f.idFournisseur as idF
            FROM
                produits p JOIN prod_fournisseur pf ON p.idProd = pf.idProd
                JOIN fournisseur f ON pf.idFournisseur = f.idFournisseur
            WHERE
                pf.idFournisseur = :id

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


    public function ajoutAchat($idProd, $quantite){



    }








    public function dansInventaire($idProd, $idInventaire) : bool{

        $sql = "SELECT idProd FROM stock WHERE idInventaire = :idInventaire AND idProd = :idProd";
        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':idProd', $idProd);
        $stmt->bindParam(':idInventaire', $idInventaire);
        $stmt->execute();
        $res = $stmt->fetchColumn();
        if($res == NULL){
            return false;
        }else{
            return true;
        }

    }

    public function finalAjoutStock($idProd, $idInventaire, $quantiteFinale){

        if($this->dansInventaire($idProd, $idInventaire)){

            $sql_update = "UPDATE stock SET quantite = :q WHERE idProd = :idProd AND idInventaire = :idInventaire";

            $stmt3 = self::$bdd->prepare($sql_update);
            $stmt3->bindParam(':q', $quantiteFinale);
            $stmt3->bindParam(':idProd', $idProd);
            $stmt3->bindParam(':idInventaire', $idInventaire);
            $stmt3->execute();

        }else{

            $sql_insert = "INSERT INTO  stock (quantite, qteInit, idProd, idInventaire) VALUES (:q, :q, :idProd, :idInventaire);";

            $stmt4 = self::$bdd->prepare($sql_insert);
            $stmt4->bindParam(':q', $quantiteFinale);
            $stmt4->bindParam(':idProd', $idProd);
            $stmt4->bindParam(':idInventaire', $idInventaire);
            $stmt4->execute();

        }
    }


    public function ajoutStock($idProd, $quantite){

        $sql_q = "SELECT quantite FROM stock NATURAL JOIN inventaire WHERE idProd = :idProd AND date = CURRENT_DATE";

        $stmt = self::$bdd->prepare($sql_q);
        $stmt->bindParam(':idProd', $idProd);
        $stmt->execute();
        $quantiteCourante = $stmt->fetchColumn();

        $quantiteFinale = $quantite + $quantiteCourante;

        $sql_inv = "SELECT idInventaire FROM inventaire WHERE date = CURRENT_DATE";
        $stmt2 = self::$bdd->prepare($sql_inv);
        $stmt2->execute();
        $idInventaireCourant = $stmt2->fetchColumn();

        $idInventaire = $idInventaireCourant;

        $this->finalAjoutStock($idProd, $idInventaire, $quantiteFinale);


    }


}





?>

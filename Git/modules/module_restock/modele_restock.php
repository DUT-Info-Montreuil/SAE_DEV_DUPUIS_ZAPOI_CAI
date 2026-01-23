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
            ORDER BY
                p.idProd;

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


    public function ajoutAchat(){
        $sql = "INSERT INTO achat (date, idAsso) VALUES (CURRENT_DATE, :idAsso);";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':idAsso', $_SESSION['idAsso']);
        $stmt->execute();

        $idAchat = self::$bdd->lastInsertId();

        return $idAchat;

    }


    public function ajoutLigneAchat($idAchat,$p){
        $sql = "INSERT INTO ligneAchat (idAchat, idProd, idFournisseur, quantite) VALUES (:idAchat, :idProd, :idFournisseur, :quantite);";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':idAchat', $idAchat);
        $stmt->bindParam(':idProd', $p['idProd']);
        $stmt->bindParam(':quantite', $p['qte']);
        $stmt->bindParam(':idFournisseur', $p['idF']);
        $stmt->execute();

    }

    public function getAchats() {

        $sql = "
            SELECT
                a.idAchat as id,
                a.date,
                la.idFournisseur,
                a.état,
                SUM(la.quantite * pf.prix) / 100 as total_achat
            FROM
                achat a
                JOIN ligneAchat la ON a.idAchat = la.idAchat
                JOIN fournisseur f ON f.idFournisseur = la.idFournisseur
                JOIN prod_fournisseur pf ON (f.idFournisseur = pf.idFournisseur AND la.idProd = pf.idProd)
                JOIN produits p ON p.idProd = la.idProd
            WHERE
                a.idAsso = :idAsso
            GROUP BY
                a.idAchat, a.date, la.idFournisseur, a.état
            ORDER BY
                a.état,
                a.date DESC
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':idAsso', $_SESSION['idAsso']);
        $stmt->execute();
        $achatsFetch = $stmt->fetchAll();

        $achats = [];

        foreach ($achatsFetch as $achat) {
            $id = $achat['id'];

            $etat = "reçue";
            if($achat['état'] == 0){
               $etat = "en cours";
            }

            $achats[$id] = [
                'id' => $achat['id'],
                'date' => $achat['date'],
                'idFournisseur' => $achat['idFournisseur'],
                'état' => $etat,
                'total' => $achat['total_achat']
            ];
        }

        return $achats;


    }

    public function getDetailsAchat($id) {
         $sql = "
             SELECT
                 la.idProd AS id,
                 p.nom AS nom,
                 SUM(la.quantite) AS quantite_totale,
                 SUM(la.quantite * pf.prix) / 100 AS total_produit
             FROM achat a
             JOIN ligneAchat la ON la.idAchat = a.idAchat
             JOIN fournisseur f ON f.idFournisseur = la.idFournisseur
             JOIN prod_fournisseur pf ON (f.idFournisseur = pf.idFournisseur AND la.idProd = pf.idProd)
             JOIN produits p ON p.idProd = la.idProd
             WHERE a.idAchat = :idAchat
             GROUP BY la.idProd, p.nom
             ORDER BY quantite_totale DESC;
         ";


         $stmt = self::$bdd->prepare($sql);
         $stmt->bindParam(':idAchat', $id);
         $stmt->execute();

         $detailsFetch = $stmt->fetchAll(PDO::FETCH_ASSOC);


         $details_achat = [];


         foreach ($detailsFetch as $ligne) {
         $id = $ligne['id'];
         $details_achat[$id] = ['id' => $ligne['id'],'nom' => $ligne['nom'],'quantite' => $ligne['quantite_totale'],'total' => $ligne['total_produit']];
         }

         return $details_achat;
     }

    public function updateAchat(){
        $sql = "DELETE FROM achat
                    WHERE idAchat NOT IN (SELECT distinct(idAchat) FROM ligneAchat)";

        $s_sql = self::$bdd->prepare($sql);
        $s_sql->execute();
    }

    public function finaliserAchat($idAchat){
        if(!empty($idAchat)){
            $sql_fin = "UPDATE achat SET état = 1 WHERE idAchat = ?";
            $s_sql_fin = self::$bdd->prepare($sql_fin);
            $s_sql_fin->execute([$idAchat]);
        }
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

            $sql_update = "UPDATE stock SET quantite = quantite+:q WHERE idProd = :idProd AND idInventaire = :idInventaire";

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

    public function inventaireCheck(){

        $sql_q = "SELECT * FROM inventaire WHERE date = CURRENT_DATE";

        $stmt = self::$bdd->prepare($sql_q);
        $stmt->execute();
        $bool = $stmt->fetchColumn();

        if($bool == NULL){
            return false;
        }else{
            return true;
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

    public function getTresorerie() {
        $sql = self::$bdd->prepare("SELECT tresorerie FROM association WHERE idAsso = ?");
        $sql->execute([$_SESSION['idAsso']]);
        return $sql->fetchColumn();
    }


}





?>

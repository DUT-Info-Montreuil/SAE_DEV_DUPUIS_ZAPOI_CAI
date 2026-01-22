<?php
include_once("utils/connexion.php");

Connexion::initConnexion();

class Modele_historique extends Connexion{
    public function __construct() {

    }

    public function getHistoriqueClient() {
        $utilisateur = $_SESSION['idCompte'];
        $sql = "
            SELECT
                c.idCommande AS id,
                SUM(lc.quantite * m.prix) / 100 AS total_commande,
                c.date AS jour,
                c.état AS etat
            FROM commande c
            JOIN lignecommande lc ON lc.idCommande = c.idCommande
            JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
            WHERE c.idUtilisateur = :utilisateur
            GROUP BY
                c.idCommande,
                c.date,
                c.état
            ORDER BY
                c.état ASC,
                c.date DESC,
                total_commande DESC;

        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':utilisateur', $utilisateur);
        $stmt->execute();

        $histoFetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $histo_client = [];

        foreach ($histoFetch as $commande) {
            $id = $commande['id'];

            $etat = "validé";
            if($commande['etat'] == 0){
               $etat = "en cours de validation";
            }

            $histo_client[$id] = [
                'id' => $commande['id'],
                'total' => $commande['total_commande'],
                'etat' => $etat,
                'jour' => $commande['jour']
            ];
        }
        return $histo_client;

    }

     public function getDetailsCommande($id) {
         $sql = "
             SELECT
                 m.idProd AS id,
                 p.nom AS nom,
                 SUM(lc.quantite) AS quantite_totale,
                 SUM(lc.quantite * m.prix) / 100 AS total_produit
             FROM commande c
             JOIN lignecommande lc ON lc.idCommande = c.idCommande
             JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
             JOIN produits p on p.idProd = m.idProd
             WHERE c.idCommande = :idCommande
             GROUP BY m.idProd, p.nom
             ORDER BY quantite_totale DESC;
         ";


         $stmt = self::$bdd->prepare($sql);
         $stmt->bindParam(':idCommande', $id);
         $stmt->execute();

          $detailsFetch = $stmt->fetchAll(PDO::FETCH_ASSOC);


         $details_commande = [];


         foreach ($detailsFetch as $ligne) {
         $id = $ligne['id'];
         $details_commande[$id] = ['id' => $ligne['id'],'nom' => $ligne['nom'],'quantite' => $ligne['quantite_totale'],'total' => $ligne['total_produit']];
         }

         return $details_commande;
     }

    public function getHistoriqueAsso(){

        $idAsso = $_SESSION['idAsso'];

        $sql = "
            SELECT
                a.idAchat AS id,
                SUM(la.quantite * pf.prix) / 100 AS total_achat,
                a.date AS jour,
                a.état AS etat
            FROM achat a
            JOIN ligneAchat la ON la.idAchat = a.idAchat
            JOIN prod_fournisseur pf ON pf.idProd = la.idProd
            WHERE a.idAsso = :idAsso
            GROUP BY
                a.idAchat,
                a.date,
                a.état
            ORDER BY
                a.état ASC,
                a.date DESC,
                total_achat DESC;
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':idAsso', $idAsso);
        $stmt->execute();

        $histoFetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $histo_asso = [];

        foreach ($histoFetch as $achat) {
            $id = $achat['id'];

            $etat = "reçue";
            if($achat['etat'] == 0){
               $etat = "en cours";
            }

            $histo_asso[$id] = [
                'id' => $achat['id'],
                'total' => $achat['total_achat'],
                'etat' => $etat,
                'jour' => $achat['jour']
            ];
        }
        return $histo_asso;

    }

    public function getDetailsAchat($id) {
         $sql = "
             SELECT
                 pf.idProd AS id,
                 p.nom AS nom,
                 SUM(la.quantite) AS quantite_totale,
                 SUM(la.quantite * pf.prix) / 100 AS total_produit
             FROM achat a
             JOIN ligneAchat la ON la.idAchat = a.idAchat
             JOIN prod_fournisseur pf ON pf.idProd = la.idProd
             JOIN produits p on p.idProd = pf.idProd
             WHERE a.idAchat = :idAchat
             GROUP BY pf.idProd, p.nom
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

}

?>
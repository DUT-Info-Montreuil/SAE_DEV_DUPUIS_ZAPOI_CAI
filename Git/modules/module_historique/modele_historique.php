<?php
include_once("utils/connexion.php");

Connexion::initConnexion();

class Modele_historique extends Connexion{
    public function __construct() {

    }

    public function getHistoriqueClient() {
        $utilisateur = $_SESSION['idUtilisateur'];
        $sql = "
            SELECT
                c.idCommande as id,
                ( lc.quantite * p.prix ) / 100 AS total_ligne,
                c.date AS jour,
                c.état as etat
            FROM commande c
            JOIN lignecommande lc ON lc.idCommande = c.idCommande
            JOIN produits p ON p.idProd = lc.idProd
            WHERE c.idUtilisateur = :utilisateur
            ORDER BY c.état ASC,
                     c.date DESC,
                     total_ligne DESC;
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
               $etat = "en cours";
            }

            $histo_client[$id] = [
                'id' => $commande['id'],
                'total' => $commande['total_ligne'],
                'etat' => $etat,
                'jour' => $commande['jour']
            ];
        }
        return $histo_client;

    }

    public function getDetailsCommande($id) {

        $sql = "
            SELECT
                p.idProd AS id,
                p.nom AS nom,
                SUM(lc.quantite) AS quantite_totale,
                SUM(lc.quantite * p.prix) / 100 AS total_produit
            FROM commande c
            JOIN lignecommande lc ON lc.idCommande = c.idCommande
            JOIN produits p ON p.idProd = lc.idProd
            WHERE c.idCommande = :idCommande
            GROUP BY p.idProd
            ORDER BY quantite_totale DESC;
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindParam(':idCommande', $id);
        $stmt->execute();

        $detailsFetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $details_commande = [];

        foreach ($detailsFetch as $ligne) {
            $id = $ligne['id'];

            $details_commande[$id] = [
                'id' => $ligne['id'],
                'nom' => $ligne['nom'],
                'quantite' => $ligne['quantite_totale'],
                'total' => $ligne['total_produit']
            ];
        }
        return $details_commande;

    }
}

?>
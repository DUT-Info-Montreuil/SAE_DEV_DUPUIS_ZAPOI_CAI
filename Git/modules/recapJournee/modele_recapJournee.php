<?php
include_once("utils/connexion.php"); // creer le fichier base de données


Connexion::initConnexion();
class modele_recapJournee extends Connexion{


    public function __construct(){

    }

        public function getRecetteJournee(int $jour): array
        {
            $sql = "
                SELECT
                    COALESCE(SUM(lc.quantite * m.prix), 0) / 100 AS recette,
                    DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY) AS jour
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
                JOIN produits p ON lc.idProd = p.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                AND c.état = 1
            ";

            $stmt = self::$bdd->prepare($sql);
            $stmt->bindValue(':jour', $jour, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getRecapSemaine() : array {
            $tab = [];

            for ($i = 1; $i < 8; $i++) {
                $tab[$i] = $this->getRecetteJournee($i);
            }

            return $tab;

        }

        public function getTransactions(int $jour): array{
            $sql = "
                SELECT
                    c.idCommande as id,
                    p.nom as nom,
                    lc.quantite as quantite,
                    m.prix as prix,
                    ( lc.quantite * m.prix ) AS total_ligne
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
                JOIN produits p ON p.idProd = lc.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                AND c.état = 1;
            ";

            $stmt = self::$bdd->prepare($sql);
            $stmt->bindParam(':jour', $jour);
            $stmt->execute();

            $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $transactions = [];

            foreach ($lignes as $ligne) {
                $id = $ligne['id'];

                if (!isset($transactions[$id])) {
                    $transactions[$id] = [
                        'produits' => '',
                        'total' => 0
                    ];
                }

                $transactions[$id]['produits'] .=
                    ($transactions[$id]['produits'] ? ', ' : '') .
                    $ligne['quantite'] . 'x ' . $ligne['nom'];
+
                $transactions[$id]['total'] += $ligne['total_ligne'] / 100;

            }
            return $transactions;
        }

    public function getMoyenneRecetteJour(int $jour): float
    {
        $sql = "
            SELECT ROUND(AVG(total_commande / 100), 2)
            FROM (
                SELECT SUM(lc.quantite * m.prix) AS total_commande
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
                JOIN produits p ON p.idProd = lc.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                AND c.état = 1
                GROUP BY c.idCommande
            ) t
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindValue(':jour', $jour, PDO::PARAM_INT);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function getProdVendus(int $jour) {

            $sql = "
                SELECT
                    p.nom as nom,
                    SUM(lc.quantite) AS quantite_totale,
                    m.prix as prix,
                    SUM(lc.quantite * m.prix) AS total_produit
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
                JOIN produits p ON p.idProd = lc.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                AND c.état = 1
                GROUP BY p.nom, m.prix
                ORDER BY quantite_totale DESC;
            ";

            $stmt = self::$bdd->prepare($sql);
            $stmt->bindParam(':jour', $jour);
            $stmt->execute();

            $prodVendus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stock = $this->getStock();

            $produits = [];

            foreach ($prodVendus as $ligne) {
                $nom = $ligne['nom'];

                $produits[$nom] = [
                    'nom' => $nom,
                    'quantite' => (int) $ligne['quantite_totale'],
                    'prix' => (float) $ligne['prix'] / 100,
                    'total' => (float) $ligne['total_produit'] / 100,
                    'stock' => $stock[$nom]['stock'] ?? 0
                ];
            }

            return $produits;
    }

    public function getStock() : array {
        $sql = "
            SELECT nom, quantite
            FROM produits p JOIN stock s ON p.idProd = s.idProd
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->execute();

        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stock_prod = [];

        foreach ($stock as $ligne) {
            $nom = $ligne['nom'];

            $stock_prod[$nom] = [
                'nom' => $ligne['nom'],
                'stock' => (int) $ligne['quantite']
            ];
        }
        return $stock_prod;
    }




}
?>

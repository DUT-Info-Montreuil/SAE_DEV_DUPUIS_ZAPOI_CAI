<?php
include_once("utils/connexion.php"); // creer le fichier base de données


Connexion::initConnexion();
class modele_recapJournee extends Connexion{


    public function __construct(){

    }

        public function getRecetteJournee(int $jour) : array {

            $sql_lignecom = "SELECT SUM(lc.quantite * p.prix) AS recette_totale,
                                     DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY) as date_jour
                                     FROM commande c
                                     JOIN lignecommande lc ON lc.idCommande = c.idCommande
                                     JOIN produits p ON lc.idProd = p.idProd
                                     WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                            ";

            $requete = self::$bdd->prepare($sql_lignecom);
            $requete->bindParam(':jour', $jour);
            $requete->execute();

            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            $recetteTotale = ['recette' => $resultat['recette_totale'],
                              'jour' => $resultat['date_jour']]?? 0;

            return $recetteTotale;

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
                    p.prix as prix,
                    ( lc.quantite * p.prix ) AS total_ligne
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN produits p ON p.idProd = lc.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY);
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

                // Addition du total
                $transactions[$id]['total'] += $ligne['total_ligne'] / 100;
            }

            return $transactions;
        }

    public function getProdVendus(int $jour) {

            $sql = "
                SELECT
                    p.nom as nom,
                    SUM(lc.quantite) AS quantite_totale,
                    p.prix as prix,
                    SUM(lc.quantite * p.prix) AS total_produit
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN produits p ON p.idProd = lc.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                GROUP BY p.idProd;
            ";

            $stmt = self::$bdd->prepare($sql);
            $stmt->bindParam(':jour', $jour);
            $stmt->execute();

            $prodVendus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $produits = [];

            foreach ($prodVendus as $ligne) {
                $nom = $ligne['nom'];

                $produits[$nom] = [
                    'nom' => $ligne['nom'],
                    'quantite' => (int) $ligne['quantite_totale'],
                    'prix' => (float) $ligne['prix'],
                    'total' => (float) $ligne['total_produit']
                ];
            }
            return $produits;
    }


}
?>

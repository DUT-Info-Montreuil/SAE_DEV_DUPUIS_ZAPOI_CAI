<?php
include_once("utils/connexion.php"); // creer le fichier base de données


Connexion::initConnexion();
class modele_recapJournee extends Connexion{


    public function __construct(){

    }

        public function getRecetteJournee(int $jour) : int {

            $sql_lignecom = "SELECT
                                 SUM(lc.quantite * p.prix) AS recette_totale
                             FROM commande c
                             JOIN lignecommande lc ON lc.idCommande = c.idCommande
                             JOIN produits p ON lc.idProd = p.idProd
                             WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY);

                            ";

            $requete = self::$bdd->prepare($sql_lignecom);
            $requete->bindParam(':jour', $jour);
            $requete->execute();

            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            $recetteTotale = $resultat['recette_totale'] ?? 0;

            return $recetteTotale;

        }

        public function getRecapSemaine() : array {
            $tab = [];

            for ($i = 0; $i < 7; $i++) {
                $tab[$i] = $this->getRecetteJournee($i);
            }

            return $tab;

        }

        public function getTransactions(): array
        {
            $sql = "
                SELECT
                    c.idCommande as id,
                    p.nom as nom,
                    lc.quantite as quantite,
                    p.prix as prix,
                    ( lc.quantite * p.prix ) AS total_ligne
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN produits p ON p.idProd = lc.idProd;
            ";

            $stmt = self::$bdd->prepare($sql);
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

    public function getProdVendus() {

            $sql = "
                SELECT
                    p.nom as nom,
                    SUM(lc.quantite) AS quantite_totale,
                    p.prix as prix,
                    SUM(lc.quantite * p.prix) AS total_produit
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN produits p ON p.idProd = lc.idProd
                WHERE c.date = CURRENT_DATE()
                GROUP BY p.idProd;
            ";

            $stmt = self::$bdd->prepare($sql);
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

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
                AND c.état = 1 AND idAssociation = :idAsso
            ";

            $stmt = self::$bdd->prepare($sql);
            $stmt->bindValue(':jour', $jour, PDO::PARAM_INT);
            $stmt->bindValue(':idAsso', $_SESSION['idAsso'], PDO::PARAM_INT);
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

    public function getProdVendus( $jour) {

            $sql = "
                SELECT
                    p.nom as nom,
                    SUM(lc.quantite) AS quantite_totale,
                    m.prix as prix,
                    SUM(lc.quantite * m.prix) AS total_produit,
                    m.idProd
                FROM commande c
                JOIN lignecommande lc ON lc.idCommande = c.idCommande
                JOIN menu m ON (m.idProd = lc.idProd AND m.idAsso = c.idAssociation)
                JOIN produits p ON p.idProd = lc.idProd
                WHERE DATE(c.date) = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY)
                AND c.état = 1 
                GROUP BY p.nom, m.prix, m.idProd
                ORDER BY quantite_totale DESC;
            ";

            $stmt = self::$bdd->prepare($sql);

            $stmt->bindParam(':jour', $jour);
            $stmt->execute();

            $sql_stock = self::$bdd->prepare("SELECT quantite FROM inventaire NATURAL JOIN stock NATURAL JOIN menu WHERE idAsso = ? AND idProd = ? AND date = CURRENT_DATE");
            
            

            $prodVendus = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $produits = [];
            $id=0;
            foreach ($prodVendus as $ligne) {
                $nom = $ligne['nom'];
                $sql_stock -> execute([$_SESSION['idAsso'],$ligne['idProd']]);
                $produits[$id] = [
                    'nom' => $nom,
                    'quantite' => (int) $ligne['quantite_totale'],
                    'prix' => (float) $ligne['prix'] / 100,
                    'total' => (float) $ligne['total_produit'] / 100,
                    'stock' => $sql_stock->fetchColumn(),
                ];
                $id++;
            }

            return $produits;
    }

    public function getStock() : array {
        $sql = "
            SELECT nom, quantite
            FROM produits p JOIN stock s ON p.idProd = s.idProd
            JOIN inventaire i ON i.idInventaire = s.idInventaire
        ";

        $stmt = self::$bdd->prepare($sql);
        $stmt->execute();

        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stock_prod = [];

        foreach ($stock as $ligne) {
            $nom = $ligne['nom'];

            $stock_prod[] = [
                'nom' => $ligne['nom'],
                'stock' => (int) $ligne['quantite']
            ];
        }
        return $stock_prod;
    }

    public function ecartJourJ1J2(){
        $sql=self::$bdd->prepare("(
                        SELECT
                            p.idProd,
                            p.nom,
                            s1.quantite AS qteJ1,
                            COALESCE(s2.quantite, 0) AS qteJ2
                        FROM inventaire i1
                        JOIN stock s1 ON s1.idInventaire = i1.idInventaire
                        JOIN produits p ON p.idProd = s1.idProd
                        LEFT JOIN inventaire i2
                            ON i2.date = :J2 AND i2.idAsso = i1.idAsso
                        LEFT JOIN stock s2
                            ON s2.idInventaire = i2.idInventaire
                            AND s2.idProd = s1.idProd
                        WHERE i1.date = :J1
                            AND i1.idAsso = :idAsso
                    )

                    UNION

                    (
                        SELECT
                            p.idProd,
                            p.nom,
                            COALESCE(s1.quantite, 0) AS qteJ1,
                            s2.quantite AS qteJ2
                        FROM inventaire i2
                        JOIN stock s2 ON s2.idInventaire = i2.idInventaire
                        JOIN produits p ON p.idProd = s2.idProd
                        LEFT JOIN inventaire i1
                            ON i1.date = :J1 AND i1.idAsso = i2.idAsso
                        LEFT JOIN stock s1
                            ON s1.idInventaire = i1.idInventaire
                            AND s1.idProd = s2.idProd
                        WHERE i2.date = :J2
                            AND i2.idAsso = :idAsso
                    );
");

        $sql->bindParam(':J2', $_POST['J2']);
        $sql->bindParam(':J1', $_POST['J1']);
        $sql->bindParam(':idAsso', $_SESSION['idAsso']);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ecartJourJ(){

        $sql=self::$bdd->prepare("SELECT (quantite-qteInit) AS ecart, nom FROM stock NATURAL JOIN inventaire NATURAL JOIN produits WHERE idAsso = ? AND date = CURRENT_DATE");

        $sql->execute([$_SESSION['idAsso']]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }



}
?>

<?php
include_once("utils/connexion.php"); // creer le fichier base de données


Connexion::initConnexion();
class modele_recapJournee extends Connexion{


    public function __construct(){

    }

        public function getRecetteJournee(int $jour) : int {

            $sql_lignecom = "SELECT
                                SUM(lc.quantite * p.prix) AS recette_totale
                                FROM lignecommande lc
                                JOIN produits p ON lc.idProd = p.idProd
                                WHERE lc.date = DATE_SUB(CURRENT_DATE(), INTERVAL :jour DAY);
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
                $tab[$i] = $this->getRecetteJournee(-$i);
            }

            return $tab;

        }

        public function getTransactions() : array {

            $sql_transactions = "
                SELECT p.nom, lc.quantite
                FROM commande c JOIN lignecommande lc JOIN produits p
                GROUP BY idCommande;
            ";
            /** niké, à check à la maison, mais principe ok  */

            $requete = self::$bdd->prepare($sql_transactions);
            $requete->execute();

            $resultat = $requete->fetchAll();




        }

}
?>

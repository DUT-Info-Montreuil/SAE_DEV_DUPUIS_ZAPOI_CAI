<?php
include_once("utils/connexion.php"); // creer le fichier base de données


Connexion::initConnexion();
class Modele_connexion extends Connexion{
    private $id_solde=0;


    public function __construct(){

    }

public function getRecetteJournée() : int {
    
    $sql_lignecom = "SELECT 
                        SUM(lc.quantite * p.prix) AS recette_totale
                        FROM lignecommande lc
                        JOIN produits p ON lc.idProd = p.idProd
                        WHERE lc.date = CURRENT_DATE;
                    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    $recetteTotale = $resultat['recette_totale'] ?? 0;

    return $recetteTotale;

}


}
?>

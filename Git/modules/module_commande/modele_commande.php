<?php
include_once("utils/connexion.php"); // fichier de connexion à la base

Connexion::initConnexion();

class Modele_commande extends Connexion {

    public function __construct() {
        // constructeur vide
    }


    public function ajout_début_commande() {
        $idUtilisateur = $_SESSION['idCompte'];
        $sql = "INSERT INTO commande(idAssociation,idUtilisateur,date,état) values(:idAsso,:idUtilisateur,NOW(),:etat)";
        $ssql = self::$bdd->prepare($sql);
        $success = $ssql->execute([
        'idAsso'=>$_SESSION['idAsso'],
        'idUtilisateur'=>$idUtilisateur,
        'etat'=>0
        ]);
        $_SESSION['idCommandeActuelle'] = self::$bdd->lastInsertId();
    }
    public function ajout_commande() {

        if (!isset($_POST['produits']) || !is_array($_POST['produits'])) {
            return;
        }

        $sql = "INSERT INTO lignecommande (idCommande, idProd, quantite)
                VALUES (?, ?, ?)";
        $stmt = self::$bdd->prepare($sql);

        foreach ($_POST['produits'] as $prod) {
            $lastID = $_SESSION['idCommandeActuelle'];
            $id = $prod['id'];
            $qte = $prod['qte'];

            if ($id && $qte > 0) {
                $stmt->execute([
                    $lastID,
                    $id,
                    $qte
                ]);
            }
        }
    }

  public function getProduitsMenu():array{
        $idAsso= $_SESSION['idAsso'];
         $sql = "SELECT * FROM produits NATURAL JOIN menu INNER JOIN stock ON produits.idProd=stock.idProd WHERE menu.idAsso = ?";
                $stmt = self::$bdd->prepare($sql);
                $stmt->execute([$idAsso]);
                $produits = $stmt->fetchAll();

         return $produits;
  }
  public function déduireSolde($montant) : void {
    $solde = $_SESSION['solde'];
    $solde_final = $solde - $montant;
    $sql = "UPDATE compte
            NATURAL JOIN Utilisateur
            SET solde = ?
            WHERE idCompte = ? AND idAsso = ?";
    $s_sql= self::$bdd->prepare($sql);
    $s_sql->execute([$solde_final,$_SESSION['idCompte'],$_SESSION['idAsso']]);

    $_SESSION['solde'] = $solde_final;


  }
public function calculerPrixTotalCommande() {
    $total = 0;
    if (isset($_POST['produits'])) {
        foreach ($_POST['produits'] as $prod) {
            $id = $prod['id'];
            $idAsso = $_SESSION['idAsso'];
            $qte = (int)$prod['qte'];
            if ($qte > 0) {
                $stmt = self::$bdd->prepare("SELECT prix FROM menu WHERE idProd = ? AND idAsso = ?");
                $stmt->execute([$id,$idAsso]);
                $prixUnitaire = $stmt->fetchColumn()/100;
                $total += $prixUnitaire * $qte;
            }
        }
    }
    return $total;

}

public function finaliserCommande($idCommande){
    if(!empty($idCommande)){
        $sql_fin = "UPDATE commande SET état = 1 WHERE idCommande = ?";
        $s_sql_fin = self::$bdd->prepare($sql_fin);
        $s_sql_fin->execute([$idCommande]);
    }
}
public function commandesEnCours(){
    $sql = "
        SELECT
            c.état,
            c.idCommande AS id,
            SUM(lc.quantite * m.prix) / 100 AS total_commande
        FROM commande c
        JOIN lignecommande lc ON lc.idCommande = c.idCommande
        JOIN menu m ON (m.idProd = lc.idProd AND c.idAssociation = m.idAsso)
        WHERE c.état = 0
        GROUP BY
            c.état,
            c.idCommande
        ORDER BY
            c.date DESC
    ";

    $s_sql = self::$bdd->prepare($sql);
    $s_sql->execute();
    $res = $s_sql->fetchAll();

    return $res;
}




    public function commandeEstValide($solde_user, $prix_total) : bool {
        return ($prix_total > 0 && $solde_user >= $prix_total);
    }

    public function updatecommande(){
        $sql = "DELETE FROM commande
                    WHERE idCommande NOT IN (SELECT distinct(idCommande) FROM lignecommande)";

        $s_sql = self::$bdd->prepare($sql);
        $s_sql->execute();
    }

    public function annulerCommande($id) {

        $sql_lc = "
            DELETE FROM lignecommande WHERE idCommande = :id
        ";

        $s_sql_lc = self::$bdd->prepare($sql_lc);
        $s_sql_lc->bindParam(':id', $id);
        $s_sql_lc->execute();

        $sql_c = "
            DELETE FROM commande WHERE idCommande = :id
        ";

        $s_sql_c = self::$bdd->prepare($sql_c);
        $s_sql_c->bindParam(':id', $id);
        $s_sql_c->execute();

    }

    public function commandeProduit($id) {
        $idAsso=$_SESSION['idAsso'];
        $sql_lc = "
            SELECT 
                p.idProd as id,
                p.nom,
                pf.prix,
                s.quantite,
                p.image,
                f.nom as nomF,
                f.idFournisseur as idF
            FROM
                produits p
                NATURAL JOIN stock s
                JOIN prod_fournisseur pf ON p.idProd = pf.idProd
                JOIN fournisseur f ON f.idFournisseur = pf.idFournisseur
            WHERE
                p.idProd = :id;
        ";

        $s_sql_lc = self::$bdd->prepare($sql_lc);
        $s_sql_lc->bindParam(':id', $id);
        $s_sql_lc->execute();
        
        
        return $s_sql_lc->fetchAll();
    }




}





?>

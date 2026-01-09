<?php
include_once("utils/connexion.php"); // fichier de connexion à la base

Connexion::initConnexion();

class Modele_commande extends Connexion {

    public function __construct() {
        // constructeur vide
    }


    public function ajout_début_commande() {
        $idUtilisateur = $_SESSION['idUtilisateur'];
        $sql = "INSERT INTO commande(DEFAULT,idUtilisateur,date,état) values(:idUtilisateur,:date,:etat)";
        $ssql = self::$bdd->prepare($sql);
        $success = self::$bdd->execute([
        'idUtilisateur'=>$idUtilisateur,
        'date'=>CURRENT_DATE(),
        'etat'=>0
        ]);
    }
    public function ajout_commande() {

        if (!isset($_POST['produits']) || !is_array($_POST['produits'])) {
            return;
        }


        $sql = "INSERT INTO lignecommande (idCommande, idProd, quantite)
                VALUES (?, ?, ?)";
        $stmt = self::$bdd->prepare($sql);

        foreach ($_POST['produits'] as $prod) {
            $lastID = self::$bdd->lastInsert();
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

      public function getProduits():array{
             $sql = "SELECT * FROM produits";
                    $stmt = self::$bdd->prepare($sql);
                    $stmt->execute();
                    $produits = $stmt->fetchAll();

             return $produits;
        }
}





?>

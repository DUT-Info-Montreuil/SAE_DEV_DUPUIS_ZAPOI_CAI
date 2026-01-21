<?php
include_once("utils/connexion.php");

Connexion::initConnexion();

class Modele_solde extends Connexion{
    public function __construct() {

    }
   public function ajout_formulaire_solde() {

       if (!isset($_POST["solde"]) || $_POST["solde"] === "") {
           return "Champs manquants";
       }

       $input_solde = $_POST["solde"];
       $idCompte = $_SESSION['idCompte'];

       $sql_idCompte = "SELECT idCompte FROM compte WHERE idCompte = :idC";
       $ssql_idCompte = self::$bdd->prepare($sql_idCompte);
       $ssql_idCompte->execute(['idC' => $idCompte]);
       $sql_solde = "SELECT solde FROM compte where idCompte = :idC";
       $ssql_solde = self::$bdd->prepare($sql_solde);
       $ssql_solde->execute(['idC' => $idCompte]);

       $solde = $ssql_solde->fetchColumn();
       $idCompte = $ssql_idCompte->fetchColumn(); // utiliser cette méthode plutôt que fetch/fetchAll , ça donne directement le contenu du tuple au lieu d'un array
       $soldetotal= $solde+$input_solde;

       $sql = "UPDATE compte
               SET solde = :solde
               WHERE idCompte = :idC";

       $ssql = self::$bdd->prepare($sql);

       $success = $ssql->execute([
           'solde' => $soldetotal,
           'idC'   => $idCompte,
       ]);

       if (!$success) {
           return "Problème avec la mise à jour du solde";
       }
        $sql_historique = "INSERT into historique_solde(idCompte,montant,dateT) values(?,?,CURRENT_DATE())";
        $s_sql_historique = self::$bdd->prepare($sql_historique);
        $s_sql_historique->execute([$idCompte,$input_solde]);
        $_SESSION['solde'] = $this->getSolde();
       return "Solde mis à jour avec succès";
   }
public function getSolde(){

    $idCompte = $_SESSION['idCompte'];
    $sql_solde = "SELECT solde FROM compte WHERE idCompte = :idU";
    $ssql_solde = self::$bdd->prepare($sql_solde);
    $ssql_solde->execute(['idU' => $idCompte]);

    return $ssql_solde->fetchColumn();
}


}

?>
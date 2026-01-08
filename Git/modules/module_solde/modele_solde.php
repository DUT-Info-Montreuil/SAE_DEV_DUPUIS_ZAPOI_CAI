<?php
include_once("utils/connexion.php");

Connexion::initConnexion();

class Modele_solde extends Connexion{
    public function __construct() {

    }
    public function ajout_formulaire_solde(){
        if (!isset($_POST["solde"]) || $_POST["solde"] === "") {
            return "Champs manquants";
        }
        $input_solde = $_POST["solde"];
        $idUtilisateur = $_SESSION['idUtilisateur'];
        $sql_idCompte = "SELECT idCompte FROM compte WHERE idUtilisateur = :idU";
        $ssql_idCompte = self::$bdd->prepare($sql_idCompte);
        $ssql_idCompte->execute(['idU'=>$idUtilisateur]);
        $idCompte=$ssql_idCompte->fetch(PDO::FETCH_ASSOC);
        $sql = "INSERT into compte(idCompte,solde,idUtilisateur) values(:idC,:solde,:idUser)";
        $ssql = self::$bdd->prepare($sql);
        $success = $ssql -> execute([
            'idC'=>$idCompte,
            'solde'=>$input_solde,
            'idUser'=>$idUtilisateur

        ]);
        if(!$success){
            return "Problème avec l'ajout de solde";
        }
        return "Solde ajouté avec succès";


    }
}

?>
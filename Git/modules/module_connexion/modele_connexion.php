<?php
include_once("utils/connexion.php"); // fichier de connexion à la base

Connexion::initConnexion();

class Modele_connexion extends Connexion {

    public function __construct() {
        // constructeur vide
    }


    public function ajout_formulaire_inscription() {

        if (empty($_POST["login_inscription"]) || empty($_POST["mdp_inscription"]) || empty($_POST["asso_inscription"])) {
            return "Champs manquants";
        }

        $input_login = $_POST["login_inscription"];
        $input_mdp   = $_POST["mdp_inscription"];
        $input_asso  = $_POST["asso_inscription"];

        // Vérifier si le nom existe déjà
        $existedeja = self::$bdd->prepare("SELECT idUtilisateur FROM Utilisateur WHERE nom = :login");
        $existedeja->execute(['login' => $input_login]);

        if ($existedeja->fetch()) {
            return "Vous avez déjà un compte ou ce nom est pris.";
        }

        $role_defaut = 3;

        $hash_mdp = password_hash($input_mdp, PASSWORD_DEFAULT);


        $sql = "INSERT INTO Utilisateur (nom, mdp, idRole, idAsso) VALUES (:nom, :mdp, :idRole, :idAsso)";
        $stmt = self::$bdd->prepare($sql);
        $success = $stmt->execute([
            'nom'    => $input_login,
            'mdp'    => $hash_mdp,
            'idRole' => $role_defaut,
            'idAsso' => $input_asso
        ]);

        if (!$success) {
            return "Erreur lors de la création de l'utilisateur.";
        }

        $idUtilisateur = self::$bdd->lastInsertId();

        $sqlCompte = "INSERT INTO Compte (solde, idUtilisateur) VALUES (0, :idUtilisateur)";
        $stmtCompte = self::$bdd->prepare($sqlCompte);
        $successCompte = $stmtCompte->execute(['idUtilisateur' => $idUtilisateur]);

        if (!$successCompte) {
            return "Erreur lors de la création du compte.";
        }

        return "Inscription réussie !";
    }


    public function ajout_formulaire_connexion() {
        if (empty($_POST["login_connexion"]) || empty($_POST["mdp_connexion"])) {
            return "Champs manquants";
        }

        $input_login = $_POST["login_connexion"];
        $input_mdp   = $_POST["mdp_connexion"];

        $sql = "SELECT idUtilisateur, nom, mdp FROM Utilisateur WHERE nom = :login";
        $stmt = self::$bdd->prepare($sql);
        $stmt->execute(['login' => $input_login]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$utilisateur || !password_verify($input_mdp, $utilisateur['mdp'])) {
            return "Login ou mot de passe incorrect.";
        }


        $_SESSION['login'] = $utilisateur['nom'];
        $_SESSION['idUtilisateur'] = $utilisateur['idUtilisateur'];
        $_SESSION['connecté'] = true; 

        return "Connexion réussie !";
        
    }
public function déconnexion() {
    session_unset();
    session_destroy();

    // recrée une session vide pour l’affichage du menu
    session_start();
    $_SESSION['connecté'] = false;

}





    public function getAssos(): array {
        $stmt = self::$bdd->prepare("SELECT idAsso,nomAsso FROM Association");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

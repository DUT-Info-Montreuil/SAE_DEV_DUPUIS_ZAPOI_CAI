<?php
include_once("utils/connexion.php"); // creer le fichier base de données


Connexion::initConnexion();
class Modele_connexion extends Connexion{
    private $id_solde=0;


    public function __construct(){

    }
public function ajout_formulaire_inscription() {
    // 1. Vérification des champs obligatoires
    if (empty($_POST["login_inscription"]) || empty($_POST["mdp_inscription"])) {
        die("Champs manquants");
    }

    $input_login = $_POST["login_inscription"];
    $input_mdp = $_POST["mdp_inscription"];
    $input_asso = $_POST["asso_inscription"];

    $existedeja = "SELECT idUtilisateur FROM Utilisateur WHERE nom = :login";
    $existedeja_s_sql = self::$bdd->prepare($existedeja);
    $existedeja_s_sql->execute(['login' => $input_login]);

    if ($existedeja_s_sql->fetch()) {
        die("Vous avez déjà un compte ou ce nom est pris.");
    }

    $compte = "INSERT INTO Compte (idCompte, solde) VALUES (DEFAULT, 0)";
    $v_compte = self::$bdd->prepare($compte);
    $v_compte->execute();


    $nouveau_id_compte = self::$bdd->lastInsertId();


    $hash_mdp = password_hash($input_mdp, PASSWORD_DEFAULT);
    $sql = "INSERT INTO Utilisateur (nom, mdp, idCompte, idAssoc) VALUES (:nom, :mdp, :idCompte, :idAssoc)";
    $s_sql = self::$bdd->prepare($sql);

    // 5. Exécution finale
    $success = $s_sql->execute([
        'nom'      => $input_login,
        'mdp'      => $hash_mdp,
        'idCompte' => $nouveau_id_compte,
        'idAssoc'  => $input_asso
    ]);

    if ($success) {
        echo "Inscription réussie";
    } else {
        echo "Une erreur est survenue lors de la création de l'utilisateur.";
    }
}
public function ajout_formulaire_connexion() {
    if (empty($_POST["login_connexion"]) || empty($_POST["mdp_connexion"])) {
        die("Champs manquants");
    }
    else{

    $input_login = $_POST["login_connexion"];
    $input_mdp = $_POST["mdp_connexion"];


    $sql = "SELECT nom, mdp FROM Utilisateur WHERE nom = :login";
    $s_sql = self::$bdd->prepare($sql);
    $s_sql->execute(['login' => $input_login]);
    $utilisateur = $s_sql->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur || !password_verify($input_mdp, $utilisateur['mdp'])) {
        die("Mot de passe ou login incorrect");
    }

    $_SESSION['login'] = $input_login;
    $_SESSION['mdp'] = $input_mdp;
    echo "Connexion réussie !";
    }
}
public function déconnexion() {
    session_destroy();
    unset($_SESSION['actif']);
    header("Location: index.php");
    exit();
}
public function getAssos() : array{
    $selectAssoc = self::$bdd->prepare("SELECT idAsso,nomAsso from Association");
    $selectAssoc -> execute();
    $array = $selectAssoc->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}







}
?>

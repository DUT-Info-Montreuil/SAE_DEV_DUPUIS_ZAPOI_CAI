<?php
include_once("utils/connexion.php"); // fichier de connexion à la base

Connexion::initConnexion();

class Modele_connexion extends Connexion {

    public function __construct() {
        // constructeur vide
    }

    public function ajout_formulaire_inscription() {
        if (empty($_POST["login_inscription"]) || empty($_POST["mdp_inscription"])) {
            return "Champs manquants";
        }

        $input_login = $_POST["login_inscription"];
        $input_mdp   = $_POST["mdp_inscription"];

        // Vérifier si le nom existe déjà
        $existedeja = self::$bdd->prepare("SELECT idCompte FROM compte WHERE nom = :login");
        $existedeja->execute(['login' => $input_login]);

        if ($existedeja->fetch()) {
            return "Vous avez déjà un compte ou ce nom est pris.";
        }

        $hash_mdp = password_hash($input_mdp, PASSWORD_DEFAULT);


        $sql = "INSERT INTO compte (solde,nom,mdp) VALUES (0, :login, :mdp)";
        $stmt = self::$bdd->prepare($sql);
        $success = $stmt->execute([
            'login' => $input_login,
            'mdp' => $hash_mdp
        ]);

        if (!$success) {
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

        $sql = "SELECT idCompte, nom, mdp FROM compte WHERE nom = :login";
        $stmt = self::$bdd->prepare($sql);
        $stmt->execute(['login' => $input_login]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$utilisateur || !password_verify($input_mdp, $utilisateur['mdp'])) {
            return "Login ou mot de passe incorrect.";
        }



        $_SESSION['login'] = $utilisateur['nom'];
        $_SESSION['connecté'] = true;
        $sql_solde = "SELECT solde from compte where nom = ?";
        $ssql_solde = self::$bdd->prepare($sql_solde);
        $ssql_solde->execute([$_SESSION['login']]);
        $res_solde = $ssql_solde->fetchColumn();
        $_SESSION['solde'] = $res_solde;
        $_SESSION['idCompte'] = $utilisateur['idCompte'];
        header("Location: index.php?module=connexion&action=choisirAsso");

        return "Connexion réussie !";

    }

    public function existe($idCompte, $idAsso) : bool {
        $sql = "SELECT * FROM Utilisateur WHERE idCompte = :idCompte AND idAsso = :idAsso";
        $stmt = self::$bdd->prepare($sql);
        $stmt->execute([
            'idCompte' => $idCompte,
            'idAsso' => $idAsso
        ]);
        $count = $stmt->fetchColumn();
        return !empty($count);
    }

    public function newUtilisateurClient() {
        $sql_Utilisateur = "INSERT INTO Utilisateur (idCompte,idRole,idAsso) VALUES (:idCompte, 3, :idAsso)";
        $stmt = self::$bdd->prepare($sql_Utilisateur);
        $stmt->execute([
            'idCompte' => $_SESSION['idCompte'],
            'idAsso' => $_SESSION['idAsso']
        ]);

    }
public function déconnexion() {
    session_unset();
    session_destroy();

    session_start();
    $_SESSION['connecté'] = false;
    $_SESSION['token'] = bin2hex(random_bytes(32));

    header("Location: index.php?module=accueil");
    exit();


}

    public function getRole(){
        if(isset($_SESSION['login']) && isset($_SESSION['idAsso'])){
        $input_login = $_SESSION['login'];
        $sql = "SELECT idRole FROM Utilisateur NATURAL JOIN compte WHERE nom = :login AND idAsso = :idAsso";
            $stmt = self::$bdd->prepare($sql);
            $stmt->execute(['login' => $input_login, 'idAsso' => $_SESSION['idAsso']]);
            $role = $stmt->fetchColumn();

        $_SESSION['role']=$role;
        return $role;
        }
    }

    public function getAssos(): array {
        $stmt = self::$bdd->prepare("SELECT idAsso,nomAsso,chemin_logo FROM association");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajout_formulaire_nouvelleAssoAttente(){
        $input_nom = $_POST["nomAsso"];
        $input_siege_social = $_POST["siege_social"];

        $sql=self::$bdd->prepare("INSERT INTO associationtemp (nomAsso, siege_social, idCompte) VALUES ( :nomAsso, :siege_social, :idCompte)");
        $sql->execute([
            'nomAsso' => $input_nom, 
            'siege_social' => $input_siege_social, 
            'idCompte' => $_SESSION['idCompte']
        ]);
        $id = self::$bdd->lastInsertId();
        $logo=$this->uploadLogoAsso($id);
        $docs=$this->uploadFichiersLegaux($id);
        if($logo!=null){
            $update_sql = "UPDATE associationtemp SET chemin_logo = ? WHERE IDTemp = ?";
            $s_sql=self::$bdd->prepare($update_sql);
            $s_sql->execute([$logo,$id]);
        }
        else{
            return "Problème avec le fichier image";
        }

        return "Votre demande d'association a été envoyée et est en attente de validation.";
    }

    public function valideAsso(){
        $sql=self::$bdd->prepare("SELECT * FROM associationtemp WHERE IDTemp = ?");
        $donneAssoFinal[]= array();
        $id=0;
        foreach($_POST['asso'] as $assoTemp) {
            $sql->execute([$assoTemp['IDTemp']]);
            $donneAsso = $sql->fetchALL(PDO::FETCH_ASSOC);
            $donneAssoFinal[$id]= $donneAsso;
            $id+=1;
        }

        return $donneAssoFinal;
    }
    public function nouvelleAssoValidee($donneAsso){
        $sql=self::$bdd->prepare("INSERT INTO association (nomAsso, siege_social,chemin_logo) VALUES (?,?,?)");
        $sql_init_gestionnaire=self::$bdd->prepare('INSERT INTO Utilisateur (idCompte,idRole,idAsso) VALUES (?,1,?)');
        $sql_init_suppadmin=self::$bdd->prepare('INSERT INTO Utilisateur (idCompte,idRole,idAsso) VALUES (1,4,?)');

        foreach($donneAsso as $inter){
            foreach($inter as $ajout){
            $sql->execute([$ajout['nomAsso'],$ajout['siege_social'],$ajout['chemin_logo']]);
            $lastID=self::$bdd->lastInsertId();
            $sql_init_gestionnaire->execute([$ajout['idCompte'],$lastID]);
            $sql_init_suppadmin->execute([$lastID]);
            }
        }
        
        $sql_cleaning=self::$bdd->prepare('DELETE FROM associationtemp WHERE nomAsso = ? AND siege_social = ?');
        foreach($donneAsso as $inter){
            foreach($inter as $del){
            $sql_cleaning->execute([$del['nomAsso'],$del['siege_social']]);
            }
        }
    }
    public function getListeAssoTemp(){
        $sql=self::$bdd->prepare("SELECT * FROM associationtemp");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
public function uploadLogoAsso($id): ?string {
    if (isset($_FILES['logo_asso']) && $_FILES['logo_asso']['error'] === 0) {
        $tmp = $_FILES['logo_asso']['tmp_name'];


        $mime = mime_content_type($tmp);
        if (strpos($mime, "image/") !== 0) {
            echo "Le fichier n'est pas une image.";
            return null;
        }



        $extension = pathinfo($_FILES['logo_asso']['name'], PATHINFO_EXTENSION);

        $destination = "images/" . $id . "_" . $_POST['nomAsso'] . "." . $extension;

        if (move_uploaded_file($tmp, $destination)) {
            return $destination;
        } else {
            echo "Erreur lors du déplacement du fichier.";
            return null;
        }
    }
    return null;
}
public function uploadFichiersLegaux($id): array {
    $destinations = [];
    $champs = ['docLegal1', 'docLegal2', 'docLegal3'];

    $nomAssoClean = preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['nomAsso']);


    $i = 1;

    foreach ($champs as $champ) {
        if (isset($_FILES[$champ]) && $_FILES[$champ]['error'] === 0) {
            $tmp = $_FILES[$champ]['tmp_name'];
            $extension = pathinfo($_FILES[$champ]['name'], PATHINFO_EXTENSION);
            $mime = mime_content_type($tmp);
            if ($mime === "application/pdf") {
                $destination = "docsLegaux/" . $id . "_" . $nomAssoClean . "_doc" . $i . "." . $extension;
                if (move_uploaded_file($tmp, $destination)) {
                    $destinations[$champ] = $destination;
                } else {
                    echo "Erreur lors du déplacement du fichier : $champ";
                }
            } else {
                echo "Format de fichier non autorisé pour : $champ";
            }
        }
        $i++;
    }
    return $destinations;
}

    
}
?>

<?php
require_once('utils/vue_generique.php');

    Class Vue_connexion extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }
    public function formulaire_inscription(){
        echo
        '
            <form method="post" action="index.php?module=connexion&action=ajout_inscription">
                <p>Identifiant</p>
                <input type="text" name="login_inscription" maxlength="50" required="true">
                <p>Mot de passe</p>
                <input type="password" name="mdp_inscription" maxlength="50" required="true">
                <br>
                <input type="submit" value="Inscription">
            </form>
        ';
    }
    public function formulaire_connexion(){
        echo
        '
            <form method="post" action="index.php?module=connexion&action=ajout_connexion">
                    <p>Identifiant</p>
                    <input type="text" name="login_connexion" maxlength="50" required="true">

                    <p>Mot de passe</p>
                    <input type="password" name="mdp_connexion" maxlength="50" required="true">
                    <br>
                    <input type="submit" value="Connexion">
            </form>
        ';
    }

    public function formulaireAsso(){
        echo "
            <h2>Créer une nouvelle association</h2>
            <form method='post' action='index.php?module=connexion&action=ajout_association' enctype='multipart/form-data'>
                <p>Nom de l'association :<p>
                <input type='text' id='nomAsso' name='nomAsso'/>
                <p>Siege social de l\'association :</p>
                <input type='text' id='siege_social' name='siege_social'/>
                <p>Logo de l'association :</p>
                <input type='file' name='logo_asso'/>

                <button type='submit'>Envoyez</button>
            </form>
        ";
    }
    
    public function choisirAsso($liste_asso){// choix de l'association après connexion
        echo '<h2>Choisissez une association :</h2>';
        foreach($liste_asso as $asso){
            echo '<form method="post" action="index.php?module=connexion&action=redirection">
            <input type="hidden" name="association" value="'. h($asso['idAsso']) .'">
            <button type="submit"><img src="'. h($asso['chemin_logo']).'" alt="Une image du logo" style="width: 20%; height: 80%;"></button>
            </form>';
        }
    }
    public function listeNVAsso($liste_asso){// liste des nouvelles associations en attente de validation
        $idAsso = 0;
        echo '<h2>Associations en attente de validation :</h2>
            <form method="post" action="index.php?module=connexion&action=validationAsso">
            <div id="listeNVAsso">
            <div class=TitreColonne></div>
            <div class=TitreColonne>Nom de l\'association</div>
            <div class=TitreColonne>Siège social</div>';
        foreach($liste_asso as $asso_option_attente){
            echo'<input type="checkbox" name="asso['. h($idAsso) .'][IDTemp]" value="'. h($asso_option_attente['IDTemp']) .'"><p>Nom de l\'association : '. h($asso_option_attente['nomAsso']) ."</p>". h($asso_option_attente['siege_social']) ."<br>";

            $idAsso+=1;
        }
        echo '</div>
        <button type="submit" value="Valider">Valider</button>
        </form>';
    }
    public function affiche(){
        return $this->getAffichage();
    }

    public function message($txt){
        echo "<p>". $txt ."</p>";
    }


}


?>

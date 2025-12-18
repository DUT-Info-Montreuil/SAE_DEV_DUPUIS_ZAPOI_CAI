<?php
require_once('utils/vue_generique.php');
    Class Vue_connexion extends VueGenerique{
        public function __construct(){

        }
    public function formulaire_inscription($liste_asso){
        echo
        '
            <form method="post" action="index.php?module=connexion&action=ajout_inscription">
                    <p>Association</p>
                    <select name = "asso_inscription">
                        <option value"" selected disabled> Choisissez une Association</option>
                       ';

                   foreach($liste_asso as $asso_courante){
                        echo '<option value="'.$asso_courante['idAssoc'].'"">'.$asso_courante['nomAssoc'].'</option>';
                   }
               echo '</select>';

               echo'

                    <p>Identifiant</p>
                    <input type="text" name="login_inscription" maxlength="50">
                    <p>Mot de passe</p>
                    <input type="text" name="mdp_inscription" maxlength="50">
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
                    <input type="text" name="login_connexion" maxlength="50">

                    <p>Mot de passe</p>
                    <input type="text" name="mdp_connexion" maxlength="50">
                    <br>
                    <input type="submit" value="Connexion">
         </form>
        ';
    }
    public function menu(){
        echo
        '
            <a href="index.php?module=connexion&action=inscription">Inscription</a>
            <a href="index.php?module=connexion&action=connexion">Connexion</a>
        ';

        }
    public function affiche(){
        return $this->getAffichage();
    }
    }

?>
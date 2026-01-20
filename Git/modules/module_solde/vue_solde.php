<?php
require_once ('utils/vue_generique.php');
    Class Vue_solde extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }
        public function formulaire_solde(){
            echo
            '
            <form method = "post" action="index.php?module=solde&action=ajout_solde">
                <label for="solde">Solde à ajouter :</label>
                <input type="number" id="solde" name="solde" min="10" max="100"/>
                <br>
                <button type="submit">Valider</button>
            </form>

            ';
        }
        public function afficheSolde($solde){
        if($solde != NULL){
            echo '<p>Votre solde est de : ' . h($solde) . '€</p>';
        }
        else{
            
            echo '<p>Votre solde est de : 0€ </p>';
        }

        }

        public function affiche(){
            return $this->getAffichage();
        }
        public function message($txt){
            echo "<p>$txt</p>";
        }
    }
?>
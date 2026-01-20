<?php
require_once ('utils/vue_generique.php');
    Class Vue_staff extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

        public function listeMembre($membres){
            echo"<h2>Liste des membres</h2>
            <form method='post' action='index.php?module=staff&action=promoteMembre'>
            <div id='listeMembres'>
            <div class=TitreColonne></div>
            <div class=TitreColonne>Nom</div>
            <div class=TitreColonne>Rôle</div>";
            $id = 0;
            foreach($membres as $membre){
                echo "<input type='checkbox' name='choix[".$membre['idCompte']."]' value='".$membre['idCompte']."'>
                <div>".$membre['nom']."</div> 
                <div>".$membre['role']."</div>";
                $id++;
            }
            echo"</div>
            <button type='submit' name='role' value='1'>Mettre en gestionnaire</button>
            <button type='submit' name='role' value='2'>Mettre en barman</button>
            <button type='submit' name='role' value='3'>Mettre en client</button>
            </form>";
        }

        public function affiche(){
            return $this->getAffichage();
        }

        public function message($txt){
            echo "<p>$txt</p>";
        }
    }
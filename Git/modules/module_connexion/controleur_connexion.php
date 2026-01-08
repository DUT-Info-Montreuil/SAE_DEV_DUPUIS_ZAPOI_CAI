<?php
    include_once 'vue_connexion.php';
    include_once 'modele_connexion.php';

    class Cont_connexion{
        private $vue;
        private $modele;
        public function __construct(){
            $this->vue = new Vue_connexion();
            $this->modele = new Modele_connexion();

        }
        //Fonctions de la vue
        public function afficher_formulaire_inscription($donneAsso){
            $this->vue->formulaire_inscription($donneAsso);

        }
        public function afficher_formulaire_connexion(){
            $this->vue->formulaire_connexion();

        }
        public function affiche() {
                return $this->vue->affiche();
        }

        //Fonctions du modèle
        public function envoyer_formulaire_inscription(){
            $this->modele->ajout_formulaire_inscription();

        }
        public function envoyer_formulaire_connexion(){
            $this->modele->ajout_formulaire_connexion();

        }
        public function déconnexion(){
            $this->modele->déconnexion();

        }

        public function exec() {
            $this->vue->menu();
        }
        public function getAssos() : array {
            return $this->modele->getAssos();
        }


}
?>
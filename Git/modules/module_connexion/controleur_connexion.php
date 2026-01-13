<?php
    include_once 'vue_connexion.php';
    include_once 'modele_connexion.php';

    class Cont_connexion{
        private $vue;
        private $modele;
        public function __construct(Vue_connexion $vue){
            $this->vue = $vue;
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
        public function getRole(){
                return $this->modele->getRole();
        }

        //Fonctions du modèle
        public function envoyer_formulaire_inscription(){
            $message = $this->modele->ajout_formulaire_inscription();
            $this->vue->message($message);


        }
        public function envoyer_formulaire_connexion(){
            $messsage = $this->modele->ajout_formulaire_connexion();
            $this->vue->message($messsage);
            $this->modele->créerLienHome();

        }
        public function déconnexion(){
            $this->modele->déconnexion();

        }


        public function getAssos() : array {
            return $this->modele->getAssos();
        }


}
?>
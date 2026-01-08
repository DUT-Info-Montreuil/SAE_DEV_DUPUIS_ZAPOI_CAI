<?php
    include_once 'vue_solde.php';
    include_once 'modele_solde.php';

    class Cont_solde{
        private $vue;
        private $modele;
        public function __construct(Vue_solde $vue){
            $this->vue = $vue;
            $this->modele = new Modele_solde();

        }
        //Fonctions de la vue
        public function afficher_formulaire_solde(){
            $this->vue->formulaire_solde();
        }
        public function afficher_solde($solde){
            $this->vue->afficheSolde($solde);
        }
        public function afficher_page_solde(){
            $solde = $this->modele->getSolde();
            $this->vue->afficheSolde($solde);
            $this->vue->formulaire_solde();
        }



        //Fonctions du modèle
        public function envoyer_formulaire_solde(){
            $message = $this->modele->ajout_formulaire_solde();
            $this->vue->message($message);

        }
        public function affiche() {
                return $this->vue->affiche();
        }



}
?>
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

        public function afficher_formulaire_asso(){
            $this->vue->formulaireAsso();
        }

        public function affiche() {
                return $this->vue->affiche();
        }
        public function getRole(){
                return $this->modele->getRole();
        }
        public function choixAsso($liste_asso){
            $this->vue->choisirAsso($liste_asso);
        }
    
        public function affiche_asso_Temp(){
            $donneAsso = $this->modele->getlisteAssoTemp();
            if(!empty($donneAsso)){
            $this->vue->listeNVAsso($donneAsso);
            }
            else{
                $this->vue->message("Aucune nouvelle association en attente de validation.");
            }
        }
        //Fonctions du modèle
        public function envoyer_formulaire_inscription(){
            $message = $this->modele->ajout_formulaire_inscription();
            $this->vue->message($message);
        }

        public function envoyer_formulaire_connexion(){
            $messsage = $this->modele->ajout_formulaire_connexion();
            $this->vue->message($messsage);

        }

        public function envoyer_formulaire_asso(){
            $messsage = $this->modele->ajout_formulaire_nouvelleAssoAttente();
            $this->vue->message($messsage);
        }

        public function valideAsso(){
            $donnees =$this->modele->valideAsso();
            $this->modele->nouvelleAssoValidee($donnees);
        }
        public function déconnexion(){
            $this->modele->déconnexion();

        }

        public function newUtilisateurClient(){
            $this->modele->newUtilisateurClient();
        }
        public function existe($idCompte, $idAsso) : bool {
            return $this->modele->existe($idCompte, $idAsso);
        }
        public function getAssos() : array {
            return $this->modele->getAssos();
        }


}
?>
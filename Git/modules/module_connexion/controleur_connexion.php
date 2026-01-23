<?php
    include_once 'vue_connexion.php';
    include_once 'modele_connexion.php';
    include_once 'utils/token_csrf.php';


    class Cont_connexion{
        private $vue;
        private $modele;
        private $check_csrf;
        public function __construct(Vue_connexion $vue){
            $this->vue = $vue;
            $this->modele = new Modele_connexion();
            $this->checker_csrf = new Token_CSRF();

        }

        public function afficher_formulaire_inscription(){
            $this->vue->formulaire_inscription();
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
            if($_SESSION['connecté'] && $liste_asso!=null){
                $this->vue->choisirAsso($liste_asso);
            }
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
            if($this->checker_csrf->check_csrf()==true){

            $message = $this->modele->ajout_formulaire_inscription();
            $this->vue->message($message);
            }
            else{
                $this->vue->message("Token invalide");
            }

        }

        public function envoyer_formulaire_connexion(){
            if($this->checker_csrf->check_csrf()){
            $messsage = $this->modele->ajout_formulaire_connexion();
            $this->vue->message($messsage);
            }
            else{
                $this->vue->message("Token invalide");
            }

        }


        public function envoyer_formulaire_asso(){
            if($this->checker_csrf->check_csrf()){
            $messsage = $this->modele->ajout_formulaire_nouvelleAssoAttente();
            $this->vue->message($messsage);
            }
            else{
                $this->vue->message("Token invalide");
            }
        }

        public function valideAsso(){
            if($this->checker_csrf->check_csrf()){
                if($_SESSION['role'] == 4){
                    $donnees =$this->modele->valideAsso();
                    $this->modele->nouvelleAssoValidee($donnees);
                }
                else{
                    $this->vue->message('Droit requis non perçu.');
                }
            }
            else{
                $this->vue->message("Token invalide");
            }
    }
        public function déconnexion(){
            $this->modele->déconnexion();

        }

        public function newUtilisateurClient(){
            if($this->checker_csrf->check_csrf()){
                if(isset($_SESSION['connecté']) ){//TODO ajouter une vérification modèle pour vérifier l'appartenance à la liste d'adhérent
                    $this->modele->newUtilisateurClient();
                }
                else{
                    $this->vue->message('Droit requis non perçu.');
                }
            }
            else{
                $this->vue->message("Token invalide");
            }
    }
        public function existe($idCompte, $idAsso) : bool { // vérifie si l'utilisateur existe dans l'association
            if(isset($_SESSION['connecté'])){
                return $this->modele->existe($idCompte, $idAsso);
            }
            return false;
        }
        public function getAssos() : array {
            if($_SESSION['connecté'] && isset($_SESSION['idCompte'])){
                return $this->modele->getAssos();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }


}
?>
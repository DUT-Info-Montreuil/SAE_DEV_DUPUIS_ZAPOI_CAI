<?php
    include_once 'vue_historique.php';
    include_once 'modele_historique.php';

    class Cont_historique{
        private $vue;
        private $modele;
        public function __construct(Vue_historique $vue){
            $this->vue = $vue;
            $this->modele = new Modele_historique();

        }
        //Fonctions de la vue
        public function afficherHistoriqueClient(array $histo){
            if($_SESSION['role']==3 || $_SESSION['role']==1){
                $this->vue->historiqueClient($histo);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }

        public function afficherDetailsCommande(array $details){

            if($_SESSION['role']==3 || $_SESSION['role']==2 || $_SESSION['role']==1){
                  $this->vue->detailsCommande($details);

            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }

        public function afficherHistoriqueAsso(array $histo){
            if($_SESSION['role']==1 || $_SESSION['role']==4){
                $this->vue->historiqueClient($histo);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }




        //Fonctions du modèle
        public function modHistoriqueClient() {
            if($_SESSION['role']==3 || $_SESSION['role']==1){
                return $this->modele->getHistoriqueClient();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        
        }


        public function modHistoriqueAsso() {
            if($_SESSION['role']==1 || $_SESSION['role']==4){
                return $this->modele->getHistoriqueAsso();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }

        public function modDetailsCommande($details) : array {
            if($_SESSION['role']==3 || $_SESSION['role']==2 ){
                return $this->modele->getDetailsCommande($details);
            }
            else if($_SESSION['role']==1 ){
                return $this->modele->getDetailsAchat($details);
            }


            else{
                $this->vue->message('Droit requis non perçu.');
            }

        }





        public function affiche() {
                return $this->vue->affiche();
        }



}
?>
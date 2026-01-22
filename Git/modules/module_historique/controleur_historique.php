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
            if($_SESSION['role']==3){
                $this->vue->historiqueClient($histo);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        
        }

        public function afficherDetailsCommande(array $details){
            if($_SESSION['role']==2){

            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
            $this->vue->detailsCommande($details);
        }


        //Fonctions du modèle
        public function modHistoriqueClient() {
            if($_SESSION['role']==3){
                return $this->modele->getHistoriqueClient();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        
        }

        public function modDetailsCommande($details) : array {
            if($_SESSION['role']==2){
                return $this->modele->getDetailsCommande($details);
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
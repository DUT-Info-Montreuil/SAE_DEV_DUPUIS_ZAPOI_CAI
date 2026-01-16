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
            $this->vue->historiqueClient($histo);
        }

        public function afficherDetailsCommande(array $details){
            $this->vue->detailsCommande($details);
        }


        //Fonctions du modèle
        public function modHistoriqueClient() : array {
            return $this->modele->getHistoriqueClient();
        }

        public function modDetailsCommande($details) : array {
            return $this->modele->getDetailsCommande($details);
        }




        public function affiche() {
                return $this->vue->affiche();
        }



}
?>
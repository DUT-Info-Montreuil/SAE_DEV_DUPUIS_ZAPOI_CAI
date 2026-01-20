<?php
    include_once 'vue_commande.php';
    include_once 'modele_commande.php';

    class Cont_commande{
        private $vue;
        private $modele;
        public function __construct(Vue_commande $vue){
            $this->vue = $vue;
            $this->modele = new Modele_commande();

        }
        //Fonctions de la vue
        public function afficher_formulaire_débutCommande(){
            if ($_SESSION['role']==3) {
                $this->vue->formulaire_début_commande();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }
        public function afficher_formulaire_Commande(){
                if ($_SESSION['role']==3) {
                $produits = $this->modele->getProduitsMenu();
                $this->vue->formulaire_commande($produits);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
            
        }

        public function envoyer_formulaire_débutCommande(){
            if ($_SESSION['role']==3) {
                $this->modele->ajout_début_commande();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }
        public function envoyer_formulaire_Commande(){
            if ($_SESSION['role']==3) {
                $this->modele->ajout_commande();
                $prix = $this->modele->calculerPrixTotalCommande();
                $this->modele->déduireSolde($prix);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
            
        }
        public function prix_total(){

            if ($_SESSION['role']==3) {
                $total=$this->modele->calculerPrixTotalCommande();
                header('Content-Type: application/json');
                echo json_encode(['total' => number_format($total, 2, '.', ''),['status'=>'success']]);
            exit;
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }


        }
        public function commande_valide() {
            if ($_SESSION['role']==3) {
                $prixAchat = $this->modele->calculerPrixTotalCommande();
            return $this->modele->commandeEstValide($_SESSION['solde'], $prixAchat);
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
            
        }

       	public function affiche(){
		    return $this->vue->affiche();
       	}

        public function updatecommande(){
            if($_SESSION['role']==3){
                return $this->modele->updatecommande();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
            return vide[null];
        }
    public function finaliserCommande(){
         if($_SESSION['role']==2){
            if (isset($_POST['idCommande'])) {
                $this->modele->finaliserCommande($_POST['idCommande']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
                exit;
            }
            else{
                $commandesEnCours = $this->modele->commandesEnCours();
                $this->vue->finaliser_commande($commandesEnCours);
            }
        }
        else{
             $this->vue->message('Droit requis non perçu.');
        }
    }

        public function getProduits() {
            if($_SESSION['role']==3){
                return $this->modele->getProduits();
            }
            else{
                $this->vue->message('Droit requis non perçu.');
            }
        }

        public function annulationCommande($id){
            return $this->modele->annulerCommande($id);
        }
        public function messageAnnulation(){
            return $this->vue->message("Commande annulée");
        }

        public function recupCommandeProduit($id){
            return $this->modele->commandeProduit($id);
        }
        public function afficheCommandeProduit($prod){
            return $this->vue->vueCommandeProduit($prod);
        }


}
?>

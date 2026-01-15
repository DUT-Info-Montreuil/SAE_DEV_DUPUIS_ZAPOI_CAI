<?php
require_once ('utils/vue_generique.php');
    Class Vue_historique extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }


        public function historiqueClient(array $historique){
            echo "<h2>Historique des commandes :</h2> <br>";
            foreach($historique as $commande){
               echo "<p class='historique'> Commande ".$commande['id'].' du '.$commande['jour'].
                   ' (<a href="index.php?module=historique&action=detailHistoClient&idCommande='.$commande['id'].'">détails</a>) : Total : '.number_format($commande['total'],2).' € ,  état : '.$commande['etat'].'';

            }
            echo "</p><br>";
        }

        public function detailsCommande(array $lignesCommande){
            echo "<h3> Détails de la commande: </h3> <br>";
            foreach($lignesCommande as $ligne){
                echo "<br><p class='details'>".$ligne['nom']." x ".$ligne['quantite'].", total : ".number_format($ligne['total'],2)." € ";
            }
        }


        public function affiche(){
            return $this->getAffichage();
        }

    }
?>
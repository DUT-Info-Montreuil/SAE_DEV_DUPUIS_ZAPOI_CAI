<?php
require_once ('utils/vue_generique.php');
    Class Vue_historique extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }


        public function historiqueClient(array $historique){
            echo "<h2>Historique des commandes :</h2> <br>";
            foreach($historique as $commande){
               echo "<div><p class='historique'> Commande ". h($commande['id']) .' du '. h($commande['jour']) .
                   ' (<a href="index.php?module=historique&action=detailHistoClient&idCommande='. h($commande['id']) .'">détails</a>) : Total :
                   '.number_format($commande['total'],2).' € ,  état : '. h($commande['etat']) .'';

                   if(strcmp($commande['etat'], "en cours de validation") == 0){
                    echo ' <a href="index.php?module=commande&action=annulation&idCommande='. h($commande['id']) .'">(annuler la commande)</a>';
                   }


                   echo '</div></p>';

            }

        }

        public function detailsCommande(array $lignesCommande){
            echo "<h3> Détails de la commande: </h3> <br>";
            echo "<lu>";
            foreach($lignesCommande as $ligne){
                echo "<br><li><p class='details'>". h($ligne['nom']) ." x ". h($ligne['quantite']) .", total : ". number_format($ligne['total'],2)." € </li>";
            }
            echo "</lu>";
        }


        public function affiche(){
            return $this->getAffichage();
        }

    }
?>
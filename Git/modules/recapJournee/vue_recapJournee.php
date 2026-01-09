<?php
require_once('utils/vue_generique.php');
    Class vue_recapJournee extends VueGenerique{
        public function __construct(){

        }
        
    public function recapitulatif(){
        echo
            "
            <h1> Récapitulatif de la journée </h1>
            <br>
            <p> ".benefDuJour()." </p> .......à voir comment on s'organise </p>
            "
        ;
    }

    public function benefDuJour(int $recette){
        echo 
            "
            Recettes du jour : ".$recette." euros
            "
        ;
    }

    public function recap_semaine(array $semaine){
        echo "Recapitulatif des 7 deniers jours :";
        foreach($semaine as $jour){
           echo "<br>
                   Recettes de tel jour (à voir comment afficher les jours) : ".$jour."";
        }
    }

    public function transactionsDuJour($transactions){
        foreach ($transactions as $t) {
            echo $t['produits'] . ' — ' . number_format($t['total'], 2) . " €<br>";
        }

    }

    public function ProduitsVendus($produits){
        foreach ($produits as $p) {

             echo ''.$p['nom'].' —  x' . $p['quantite'] .", total : ".$p['total']." €<br>";
        }
    }
    

    public function menu(){
        echo
        '
            <a href="index.php?module=gestionnaire&action=recap">Récapitulatif de la semaine</a>
            
        ';

        }


    public function affiche(){
        return $this->getAffichage();
    }
    }
?>
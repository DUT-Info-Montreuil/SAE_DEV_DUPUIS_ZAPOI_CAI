<?php
require_once('utils/vue_generique.php');
    Class vue_recapJournee extends VueGenerique{
        public function __construct(){

        }
        
    public function benefDuJour(array $recette){
        echo "<h2> Bénéfices du jour :</h2>";
        echo 
            "
            Recettes du jour (".$recette['jour']."): ".$recette['recette']." euros
            "
        ;
        echo "<br>";
    }

    public function recap_semaine(array $semaine){
        echo "<h2> Recapitulatif des 7 jours précédants :</h2>";
        $i = 0;
        foreach($semaine as $jour){
           $i++;
           echo "<p class='ligneRecette'> Recettes du ".$jour['jour']." : ".$jour['recette'].' € <a href="index.php?module=recapJournee&action=recapCertainJour&jour='.$i.'">Récapitulatif de ce jour</a> </p>';
        }
        echo "<br>";
    }

    public function transactionsDuJour(array $transactions){
        echo "<h2> Transactions effectuées :</h2>";
        foreach ($transactions as $t) {
            echo $t['produits'] . ' — ' . number_format($t['total'], 2) . " €<br>";
        }
        echo "<br>";
    }

    public function ProduitsVendus(array $produits){
        echo "<h2> Produits vendus :</h2>";
        foreach ($produits as $p) {
             echo "".$p['nom'].' —  x' . $p['quantite'] .", total : ".$p['total']." €<br>";
        }
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
    }

    public function menu(){
        echo
        '
            <a href="index.php?module=gestionnaire&action=recapDuJour">Récapitulatif de la semaine</a>
            
        ';

        }


    public function affiche(){
        return $this->getAffichage();
    }
}
?>
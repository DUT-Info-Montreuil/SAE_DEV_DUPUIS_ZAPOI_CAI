<?php
require_once('utils/vue_generique.php');
    Class vue_recapJournee extends VueGenerique{
        public function __construct(){

        }
        
    public function benefDuJour(array $recette){
        echo "<h2> Bénéfices du jour :</h2>";
        echo 
            "
            Recettes du jour (".$recette['jour']."): ".number_format($recette['recette'],2)." euros
            "
        ;
        echo "<br>";
    }

    public function recap_semaine(array $semaine){
        echo "<h2>Recapitulatif des 7 deniers jours :</h2>";
        foreach($semaine as $jour){
           echo "<p class='ligneRecette'> Recettes de tel jour (à voir comment afficher les jours) : ".$jour."</p>";
        echo "<h2> Recapitulatif des 7 jours précédants :</h2>";
        $i = 0;
        foreach($semaine as $jour){
           $i++;
           echo "<p class='ligneRecette'> Recettes du ".$jour['jour'].' (<a href="index.php?module=recapJournee&action=recapCertainJour&jour='.$i.'">récap</a>) : '.number_format($jour['recette'],2).' € </p>';
        }
        echo "<br>";
    }

    public function moyenneRecetteJour(float $moy){
        echo "<h2> Transactions effectuées : </h2>";

        echo "<p> Moyenne des transactions effectuées : ".number_format($moy,2)." € </p>";
        echo "<br>";
    }

    public function transactionsDuJour(array $transactions){
        foreach ($transactions as $t) {
            echo $t['produits'] . ' — ' . number_format($t['total'], 2) . " €<br>";
        }
        echo "<br>";
    }

    public function ProduitsVendus(array $produits){
        echo "<h2> Produits vendus :</h2>";
        foreach ($produits as $p) {
             echo "".$p['nom'].' —  x' . $p['quantite'] .", total : ".number_format($p['total'], 2)." €  (stock restant : ".$p['stock']." )<br>";
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
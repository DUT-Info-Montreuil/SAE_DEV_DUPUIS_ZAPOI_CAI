<?php
require_once('utils/vue_generique.php');
    Class vue_recapJournee extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }
        

    public function benefDuJour(int $recette){
        echo 
            "<h2> Récapitulatif de la journée </h2>
            <p>Recettes du jour : ".($recette/100)." euros</p>
            "
        ;
    }

    public function recap_semaine(array $semaine){
        echo "<h2>Recapitulatif des 7 deniers jours </h2>";
        foreach($semaine as $jour){
           echo "<p class='ligneRecette'> Recettes de tel jour (à voir comment afficher les jours) : ".($jour/100)."</p>";
        }
    }

    public function transactionsDuJour($transactions){
        echo"<h2>Transactions du jour</h2>";
        echo"<div class='TransactionsDuJour'>";
        foreach ($transactions as $t) {
            echo "<div>".$t['produits'] . "</div> <div>" . number_format($t['total'], 2) . " €</div >";
        }
        echo "</div>";

    }

    public function ProduitsVendus($produits){
        echo"<h2>Recap Produit Vendu dans la journée</h2>";
        foreach ($produits as $p) {
             echo "".$p['nom'].' —  x' . $p['quantite'] .", total : ".($p['total']/100)." €<br>";
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
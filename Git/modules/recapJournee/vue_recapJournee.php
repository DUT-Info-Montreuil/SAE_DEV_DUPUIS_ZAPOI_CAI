<?php
require_once('utils/vue_generique.php');
    Class vue_recapJournee extends VueGenerique{
        public function __construct(){

        }
        
    public function benefDuJour(array $recette){
        echo "<h2> Bénéfices du jour :</h2>";
        echo 
            "
            Recettes du jour (". h($recette['jour'])."): ".number_format($recette['recette'],2)." euros
            "
        ;
        echo "<br>";
    }

    public function recap_semaine(array $semaine){
        echo "<h2>Recapitulatif des 7 derniers jours :</h2>";
        $i = 0;
        foreach($semaine as $jour){
           $i++;
           echo "<p class='ligneRecette'> Recettes du ". h($jour['jour']) .' (<a href="index.php?module=recapJournee&action=recapCertainJour&jour='.$i.'">récap</a>) : '.number_format($jour['recette'],2).' € </p>';
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
            echo h($t['produits']) . ' — ' . number_format($t['total'], 2) . " €<br>";
        }
        echo "<br>";
    }

    public function ProduitsVendus(array $produits){
        echo "<h2> Produits vendus :</h2>";
        foreach ($produits as $p) {
             echo "". h($p['nom']).' —  x' . h($p['quantite']) .", total : ".number_format($p['total'], 2)." €  (stock restant : ". h($p['stock']) ." )<br>";
        }
    }

    public function afficheEcart($liste_Ecart){
        echo "<h2>Ecart du jour</h2>";
        foreach($liste_Ecart as $prod){
            echo "<p>";
            if($prod['ecart']<0){
                echo"Il y a ".(h($prod['ecart'])*-1)." ". h($prod['nom']) ." vendu aujourd'hui";
            }
            
            
            echo "</p>";
        }
        echo "<p>";
    }

    public function form_ecartJours(){

        echo '<form method="post" action="index.php?module=recapJournee&action=ecartStockEntre2J">

                <p> Choix du premier jour  :<p>
                <input type="date" name="J1"/>
                <p>Choix du second jour  :</p>
                <input type="date" name="J2"/>

                <button type="submit">Envoyez</button>

            </form>';

    }

    public function afficheEcartJ1J2($liste_qte){
        echo "<h2>Ecart entre ". h($_POST['J1'])." et ". h($_POST['J2'])."</h2>";
        if($liste_qte!=null){
            foreach($liste_qte as $prod){

                echo "<p>";
                if(($prod['qteJ1']-$prod["qteJ2"])<0){
                    echo"Il y a ".(h(($prod['qteJ1']-$prod["qteJ2"]))*-1)." ". h($prod['nom']) ." de moins au ". h($_POST['J1']) . " qu'au ".h($_POST['J2']);
                }
                else if(($prod['qteJ1']-$prod["qteJ2"])==0){
                    echo"Pas de produit ont été vendu";
                }
                else{
                    echo"Il y a ".(h(($prod['qteJ1']-$prod["qteJ2"])))." ". h($prod['nom']) ." de plus au ".h($_POST['J1']) . " qu'au ".h($_POST['J2']);//date2
                }
                echo "</p>";
            }
            echo "<p>";
        }
        else{
            echo"<p>N'existe pas</p>";
        }
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
    public function message($txt){
        echo "<p>$txt</p>";
    }
}
?>
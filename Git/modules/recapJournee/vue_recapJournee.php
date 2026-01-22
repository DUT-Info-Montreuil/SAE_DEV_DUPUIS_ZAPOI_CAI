<?php
require_once('utils/vue_generique.php');

class vue_recapJournee extends VueGenerique {
    
    public function __construct() {

        parent::__construct();
    }
        
    public function benefDuJour(array $recette) {
        echo '
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header bg-dark text-white text-center py-3">
                            <h2 class="card-title mb-0"> Bénéfices du jour </h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center mb-0">
                                <span class="fw-bold text-success fs-4">
                                    Recettes du jour (' . h($recette["jour"]) . '): ' . number_format($recette["recette"], 2) . ' €
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>';
    }

    public function recap_semaine(array $semaine) {
        $i = 0;
        echo '
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header bg-dark text-white text-center py-3">
                            <h2 class="card-title mb-0"> Récapitulatif des 7 derniers jours </h2>
                        </div>
                        <div class="card-body p-4">'; 

        foreach($semaine as $jour) {
            $i++;
            echo "
            <div class='d-flex justify-content-center mb-3'>
                <span class='fw-bold fs-4'>
                    Recettes du " . h($jour['jour']) . " 
                    (<a href='index.php?module=recapJournee&action=recapCertainJour&jour=" . $i . "' class='text-decoration-none'>récap</a>) : " 
                    . number_format($jour['recette'], 2) . " €
                </span>
            </div>";
        }

        echo '          </div> 
                    </div> 
                </div> 
            </div> 
        </div>';
    }

    public function moyenneRecetteJour(float $moy,array $transactions) {
        echo '
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header bg-dark text-white text-center py-3">
                            <h2 class="card-title mb-0"> Transactions effectuées </h2>
                        </div>
                        <div class="card-body p-4 text-center">
                            <span class="fw-bold text-success fs-4">
                                Moyenne des transactions effectuées : ' . number_format($moy, 2) . ' €
                            </span>
                            ';
                            echo '<div class="container mt-5">';
                            foreach ($transactions as $t) {
                                echo"
                                <span class='fw-bold fs-4'>";
                                    echo h($t['produits']) . ' — ' . number_format($t['total'], 2) . " €<br>";
                                echo "</span>";
                                    }
                                    
                                
                        echo'
                        </div>
                    </div>
                </div>
            </div>
        </div><br>';
    }



public function ProduitsVendus(array $produits) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="card-title mb-0"> Produits vendus </h2>
                    </div>
                    <div class="card-body p-4">';

    foreach ($produits as $p) {

        echo "
        <div class='mb-2'>
            <span class='fw-bold fs-5'>" . 
                h($p['nom']) . " — x" . h($p['quantite']) . ", total : " . number_format($p['total'], 2) . " € (stock : " . h($p['stock']) . ")
            </span>
        </div>";
    } 

    echo '
                    </div> </div> </div> </div> </div>'; // Fin container
}

    public function menu() {
        echo '
            
                <a class="btn btn-outline-dark" href="index.php?module=gestionnaire&action=recapDuJour">Récapitulatif de la semaine</a>
            ';
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
    public function affiche() {
        return $this->getAffichage();
    }

    public function message($txt) {
        echo "<p>$txt</p>";
    }
}
?>
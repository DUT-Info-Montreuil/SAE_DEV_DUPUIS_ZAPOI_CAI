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
                        <h2 class="card-title mb-0"> Produits vendus du jour</h2>
                    </div>
                    <div class="card-body p-4">';

    foreach ($produits as $p) {

        echo "
        <div class='mb-2'>
            <span class='fw-bold fs-5'>" . 
                h($p['nom']) . " — x" . h($p['quantite']) . ", total : " . number_format($p['total'], 2) . " € (stock : " . h($p['stock']) . " restant)
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
    
        echo '
            <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="card-title mb-0">Ecart du jour</h2>
                    </div>
                    <div class="card-body p-4">
                        
        
                    ';
        foreach($liste_Ecart as $prod){
            if($prod['ecart']<0){
                echo"
                <div class='d-flex justify-content-center mb-0'>
                            <span class='fw-bold text-success fs-4'>
                                Il y a ".(h($prod['ecart'])*-1)." ". h($prod['nom']) ." vendu aujourd'hui
                            </span>
                        </div>";
            }

            

            
        }
        echo '
                    </div> </div> </div> </div> </div>'; 
        
    }

    public function form_ecartJours(){
                echo'
            <div class="container mt-5">
            <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
            <div class="card-header bg-dark text-white text-center py-3">
            <h2 class="card-title text-center mb-4"> Ecart entre 2 jours</h2>
            </div>
            
            <div class="card-body card-body p-4">
                
                <form method="post" action="index.php?module=recapJournee&action=ecartStockEntre2J">
                <div class="mb-3">
                    <label for="J1" class="form-label"> Choix du premier jour : </label>
                    <input type="date" id="J1" name="J1" class="form-control" >
                </div>
                <div class="mb-3">
                    <label for="J2" class="form-label"> Choix du second jour :</label>
                    <input type="date" id="J2" name="J2" class="form-control" >
                </div>    

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg"> Envoyez </button>
                </div>



                <input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">
            </form>
        </div>
        </div>
        </div>
        </div>
        </div>
        ';



    }

public function afficheEcartJ1J2($liste_qte) {

    $date1 = h($_POST['J1']);
    $date2 = h($_POST['J2']);

    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="card-title h4 mb-0">Écart entre le ' . $date1 . ' et le ' . $date2 . '</h2>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">';

    if (!empty($liste_qte)) {
        foreach ($liste_qte as $prod) {
            $diff = $prod['qteJ1'] - $prod['qteJ2'];
            $nom = h($prod['nom']);

            echo '<li class="list-group-item py-3">';
            
            if ($diff < 0) {
                
                echo '<div class="d-flex justify-content-between align-items-center">
                        <span>' . $nom . '</span>
                        <span class="badge bg-danger rounded-pill">-' . ($diff * -1) . '</span>
                      </div>
                      <small class="text-muted">Il y en a moins au ' . $date1 . ' qu\'au ' . $date2 . '</small>';
            } 
            else if ($diff == 0) {
         
                echo '<div class="d-flex justify-content-between align-items-center">
                        <span>' . $nom . '</span>
                        <span class="badge bg-secondary rounded-pill">Stable</span>
                      </div>
                      <small class="text-muted">Aucune variation de stock</small>';
            } 
            else {
                
                echo '<div class="d-flex justify-content-between align-items-center">
                        <span>' . $nom . '</span>
                        <span class="badge bg-success rounded-pill">+' . $diff . '</span>
                      </div>
                      <small class="text-muted">Il y en a plus au ' . $date1 . ' qu\'au ' . $date2 . '</small>';
            }
            
            echo '</li>';
        }
    } 
    else {
        echo '<li class="list-group-item text-center py-4 text-muted">Aucune donnée disponible pour ces dates.</li>';
    }

    echo '
                        </ul>
                    </div>
                    <div class="card-footer text-center bg-light">
                        <a href="index.php?module=recapJournee&action=recapDuJour" class="btn btn-sm btn-outline-dark">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
    public function affiche() {
        return $this->getAffichage();
    }

    public function message($txt) {
        echo "<p>$txt</p>";
    }
}
?>
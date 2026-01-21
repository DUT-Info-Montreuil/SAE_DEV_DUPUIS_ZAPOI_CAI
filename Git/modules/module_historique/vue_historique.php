<?php
require_once ('utils/vue_generique.php');
    Class Vue_historique extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }


public function historiqueClient(array $historique) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Historique de vos commandes</h2>';

                            if (empty($historique)) {
                                echo '<p class="text-center text-muted">Aucune commande enregistrée.</p>';
                            }
                            else {

                                foreach ($historique as $commande) {
                                    echo '
                                    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Commande #' . h($commande['id']) . '</strong>
                                            <span class="text-muted small"> du ' . h($commande['jour']) . '</span><br>
                                            <span>Total : <strong>' . number_format($commande['total'], 2) . ' €</strong></span> -
                                            <span class="badge bg-secondary">' . h($commande['etat']) . '</span>
                                        </div>
                                        <div>
                                            <a href="index.php?module=historique&action=detailHistoClient&idCommande=' . h($commande['id']) . '" class="btn btn-sm btn-info">Détails</a>';

                                    if ($commande['etat'] === "en cours de validation") {
                                        echo ' <a href="index.php?module=commande&action=annulation&idCommande=' . h($commande['id']) . '"
                                                  class="btn btn-sm btn-outline-danger">Annuler</a>';
                                    }

                                    echo '
                                        </div>
                                    </div>';
                                }
                            }

    echo '
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
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

        public function message($txt){
                    echo "<p>$txt</p>";
                }

    }
?>
<?php
require_once ('utils/vue_generique.php');
    Class Vue_solde extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }
        public function formulaire_solde(){
            echo
            '
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-body card-body p-4">
                                <h2 class="card-title text-center mb-4"> Ajouter solde </h2>
                                <form method = "post" action="index.php?module=solde&action=ajout_solde">
                                <div class="mb-3">
                                <label for="solde" class="form-label">Solde à ajouter :</label>
                                <input type="number" class="form-control" id="solde" name="solde" min="10" max="100"/ placeholder="Entrez un montant entre 10 et 100"required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg"> Valider </button>
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
        public function afficheSolde($solde){
        if($solde != NULL){
            echo '<p>Votre solde est de : ' . h($solde) . '€</p>';
        }
        else{
            
            echo '<p>Votre solde est de : 0€ </p>';
        }

        }

        public function affiche(){
            return $this->getAffichage();
        }
        public function message($txt){
            echo "<p>$txt</p>";
        }
    }
?>
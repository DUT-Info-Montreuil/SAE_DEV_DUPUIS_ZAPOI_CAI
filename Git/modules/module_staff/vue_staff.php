<?php
require_once ('utils/vue_generique.php');
    Class Vue_staff extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

public function listeMembre($membres) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="card-title mb-0"> Gestion des Membres </h2>
                    </div>
                    <div class="card-body p-0">
                        <form method="post" action="index.php?module=staff&action=promoteMembre">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>';
                                           
                                            if ($_SESSION['role'] == 1) {
                                                echo '<th class="text-center" style="width: 50px;">Sélection</th>';
                                            }
    echo '                                  <th class="ps-4">Nom</th>
                                            <th class="pe-4">Rôle actuel</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    foreach ($membres as $membre) {
        echo '<tr>';
        
      
        if ($_SESSION['role'] == 1) {
            echo '<td class="text-center">
                    <input class="form-check-input" type="checkbox" name="choix[' . $membre['idCompte'] . ']" value="' . $membre['idCompte'] . '">
                  </td>';
        }

        echo '  <td class="ps-4 fw-bold">' . h($membre['nom']) . '</td>
                <td class="pe-4 text-muted">' . h($membre['role']) . '</td>
              </tr>';
    }

    echo '          </tbody>
                                </table>
                            </div>';

 
    if ($_SESSION['role'] == 1) {
        echo '
        <div class="card-footer bg-light p-3 text-center">
            <div class="btn-group" role="group">
                <button type="submit" name="role" value="1" class="btn btn-outline-primary">Mettre en Gestionnaire</button>
                <button type="submit" name="role" value="2" class="btn btn-outline-info">Mettre en Barman</button>
                <button type="submit" name="role" value="3" class="btn btn-outline-secondary">Mettre en Client</button>
            </div>
            <input type="hidden" name="token_csrf" value="' . $_SESSION['token'] . '">
        </div>';
    }

    echo '      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

        public function affiche(){
            return $this->getAffichage();
        }

        public function message($txt){
            echo "<p>$txt</p>";
        }
    }
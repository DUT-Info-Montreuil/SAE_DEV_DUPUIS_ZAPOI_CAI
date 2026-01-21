<?php
require_once('utils/vue_generique.php');

    Class Vue_connexion extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }
    public function formulaire_inscription(){
        echo'
            <div class="container mt-5">
            <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
            <div class="card-body card-body p-4">
                <h2 class="card-title text-center mb-4"> Inscription</h2>
                <form method="post" action="index.php?module=connexion&action=ajout_inscription">
                <div class="mb-3">
                    <label for="login_inscription" class="form-label"> Identifiant </label>
                    <input type="text" id="login_inscription" name="login_inscription" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="mdp_inscription" class="form-label"> Mot de passe </label>
                    <input type="password" id="mdp_inscription" name="mdp_inscription" class="form-control" aria-describedby="passwordHelpBlock" required>
                     <small id="passwordHelpBlock" class="form-text text-muted">Le mot de passe doit faire 12 caractères alpha-numériques</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg"> Inscription </button>
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
    public function formulaire_connexion(){
         echo'
             <div class="container mt-5">
             <div class="row justify-content-center">
             <div class="col-md-8 col-lg-6">
             <div class="card shadow-lg">
             <div class="card-body card-body p-4">
                 <h2 class="card-title text-center mb-4"> Connexion</h2>
                 <form method="post" action="index.php?module=connexion&action=ajout_connexion">
                 <div class="mb-3">
                     <label for="login_connexion" class="form-label"> Identifiant </label>
                     <input type="text" id="login_connexion" name="login_connexion" class="form-control" required>
                 </div>
                 <div class="mb-3">
                     <label for="mdp_connexion" class="form-label"> Mot de passe </label>
                     <input type="password" id="mdp_connexion" name="mdp_connexion" class="form-control" required>

                 </div>

                 <div class="d-grid">
                     <button type="submit" class="btn btn-primary btn-lg"> Connexion </button>
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

public function formulaireAsso(){
    echo "
    <div class='container mt-5'>
        <div class='row justify-content-center'>
            <div class='col-md-8 col-lg-6'>
                <div class='card shadow-lg'>
                    <div class='card-body p-4'>
                        <h2 class='card-title text-center mb-4'>Créer une nouvelle association</h2>

                        <form method='post'
                              action='index.php?module=connexion&action=ajout_association'
                              enctype='multipart/form-data'>

                            <div class='mb-3'>
                                <label for='nomAsso' class='form-label'>Nom de l'association</label>
                                <input type='text' id='nomAsso' name='nomAsso' class='form-control' required>
                            </div>

                            <div class='mb-3'>
                                <label for='siege_social' class='form-label'>Siège social</label>
                                <input type='text' id='siege_social' name='siege_social' class='form-control' required>
                            </div>

                            <div class='mb-3'>
                                <label for='logo_asso' class='form-label'>Logo de l'association</label>
                                <input type='file' name='logo_asso' class='form-control' accept='image/*'>
                            </div>

                            <hr>

                            <h5 class='mb-3'>Documents légaux</h5>

                            <div class='mb-3'>
                                <input type='file' name='docLegal1' class='form-control'>
                            </div>

                            <div class='mb-3'>
                                <input type='file' name='docLegal2' class='form-control'>
                            </div>

                            <div class='mb-4'>
                                <input type='file' name='docLegal3' class='form-control'>
                            </div>

                            <div class='d-grid'>
                                <button type='submit' class='btn btn-primary btn-lg'>
                                    Envoyer
                                </button>
                            </div>

                            <input type='hidden' name='token_csrf' value='".$_SESSION['token']."'>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    ";
}

    
    public function choisirAsso($liste_asso){
        echo '<h2>Choisissez une association :</h2>
             <div class="d-flex gap-3">';
        foreach($liste_asso as $asso){
            echo '<form method="post" action="index.php?module=connexion&action=redirection">
            <input type="hidden" name="association" value="'. h($asso['idAsso']) .'">

            <button type="submit"><img src="'. h($asso['chemin_logo']).'" class="img-fluid" alt="Une image du logo" style="width: 200px; height: 200px;object-fit:contain;"></button>

            <input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">
            </form>';
        }
   echo' </div>';
    }
public function listeNVAsso($liste_asso) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="card-title mb-0"> Associations en attente de validation </h2>
                    </div>
                    <div class="card-body p-0">
                        <form method="post" action="index.php?module=connexion&action=validationAsso">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;"></th>
                                            <th class="ps-4">Nom de l\'association</th>
                                            <th>Siège social</th>
                                            <th class="text-center">Documents légaux</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    $idAsso = 0;
    foreach ($liste_asso as $asso) {
        echo '<tr>
                <td class="text-center">
                    <input class="form-check-input" type="checkbox" name="asso['. h($idAsso) .'][IDTemp]" value="'. h($asso['IDTemp']) .'">
                </td>
                <td class="ps-4 fw-bold">' . h($asso['nomAsso']) . '</td>
                <td>' . h($asso['siege_social']) . '</td>
                <td class="text-center">';

                // Boucle pour les documents
                for ($i = 1; $i <= 3; $i++) {
                    $chemin = "docsLegaux/" . h($asso['IDTemp']) . "_" . h($asso['nomAsso']) . "_doc" . $i . ".pdf";
                    echo '<a href="' . $chemin . '" target="_blank" class="btn btn-sm btn-outline-danger me-1">
                            <i class="bi bi-file-pdf"></i> Doc ' . $i . '
                          </a>';
                }

        echo '  </td>
              </tr>';
        $idAsso++;
    }

    echo '          </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light p-3 text-end">
                                <input type="hidden" name="token_csrf" value="' . $_SESSION['token'] . '">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-circle"></i> Valider la sélection
                                </button>
                            </div>
                        </form>
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
        echo "<p>". $txt ."</p>";
    }


}


?>

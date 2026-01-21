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

    
    public function choisirAsso($liste_asso){// choix de l'association après connexion
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
    public function listeNVAsso($liste_asso){// liste des nouvelles associations en attente de validation
        $idAsso = 0;
        echo '<h2>Associations en attente de validation :</h2>
            <form method="post" action="index.php?module=connexion&action=validationAsso">
            <div id="listeNVAsso">
            <div class=TitreColonne></div>
            <div class=TitreColonne>Nom de l\'association</div>
            <div class=TitreColonne>Siège social</div>';
            foreach($liste_asso as $asso_option_attente){
                echo '<input type="checkbox" name="asso['. h($idAsso) .'][IDTemp]" value="'. h($asso_option_attente['IDTemp']) .'">';
                echo '<p>Nom de l\'association : '. h($asso_option_attente['nomAsso']) .'</p>';
                echo h($asso_option_attente['siege_social']) . "<br>";

                echo '<div>';

                    for ($i = 1; $i <= 3; $i++) {
                        $chemin = "docsLegaux/" . h($asso_option_attente['IDTemp']) . "_" . h($asso_option_attente['nomAsso']) . "_doc" . $i . ".pdf";
                        var_dump($chemin);
                        echo '<a href="' . $chemin . '" target="_blank">Document ' . $i . '</a> ';
                    }
                echo '</div>';

                $idAsso += 1;
            }

        echo '</div>
        <button type="submit" value="Valider">Valider</button>
        <input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">
        </form>';
    }
    public function affiche(){
        return $this->getAffichage();
    }

    public function message($txt){
        echo "<p>". $txt ."</p>";
    }


}


?>

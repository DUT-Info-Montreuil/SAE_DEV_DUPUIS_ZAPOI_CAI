<?php
require_once('utils/vue_generique.php');
    Class Vue_restock extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

 public function produitsAchat(array $produits) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h3 class="card-title h5 mb-0">Produits à l\'achat</h3>
                    </div>
                    <div class="card-body p-0">
                        <form method="post" action="index.php?module=restock&action=ajoutAchat">
                            <input type="hidden" name="token_csrf" value="'.$_SESSION['token'].'"/>
                            
                            <ul class="list-group list-group-flush">';

    $index = 0;
    foreach ($produits as $p) {
        echo '
                                <li class="list-group-item py-3 px-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <h6 class="mb-1">' . h($p['nom']) . '</h6>
                                            <p class="mb-0 text-muted small">
                                                Prix : <strong>' . number_format($p['prix'], 2) . ' €</strong> | 
                                                Fournisseur : <span class="text-dark">' . h($p['fournisseur']) . '</span>
                                            </p>
                                        </div>
                                        
                                        <div class="col-md-5 mt-2 mt-md-0">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light small">Qté</span>
                                                <input type="hidden" name="produit['.$index.'][idProd]" value="'. h($p['idProd']) .'">
                                                <input type="hidden" name="produit['.$index.'][prix]" value="'. h($p['prix']) .'">
                                                <input type="hidden" name="produit['.$index.'][idF]" value="'. h($p['idF']) .'">
                                                <input type="number" name="produit['.$index.'][qte]" 
                                                       class="form-control" 
                                                       min="0" max="1000" 
                                                       value="0" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </li>';
        $index++;
    }

    echo '
                            </ul>
                            
                            <div class="card-footer bg-light text-center py-3">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                    Commander
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

    public function fournisseurs(array $fournisseurs){
         echo '<h2>Choisissez un fournisseur :</h2>';

        foreach($fournisseurs as $f){
            echo '<form method="post" action="index.php?module=restock&action=produitsF&fournisseur='. h($f['id']) .'">
            <input type="hidden" name="fournisseur" value="'. h($f['nom']) .'">
            <button type="submit"><img src="fonce-fond-abstrait.jpg" alt="Une image du logo" style="width: 15%; height: 2%;">'
            . h($f['nom']) .'</button>
            <input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">
            </form>';
        }

    }

public function finaliserAchats($liste_achats) {
    echo '
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-dark text-white text-center py-3">
                <h2 class="card-title mb-0">Historique et Finalisation des Achats</h2>
            </div>
            <div class="card-body p-0">
                <form method="POST" action="index.php?module=restock&action=ajoutStock">
                    <input type="hidden" name="token_csrf" value="'.$_SESSION['token'].'"/>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Prix Total</th>
                                    <th>État</th>
                                    <th class="text-center">Détails</th>';
                                    if($_SESSION['role']==1){
                                        echo'<th class="text-end px-4">Action</th>';
                                    }
                                    echo'
                                </tr>
                            </thead>
                            <tbody>';

    foreach($liste_achats as $a) {
        $idAchat = h($a["id"]);
        $etat = h($a['état']);
        
        $badgeClass = (strcmp($etat, "en cours") == 0) ? "bg-warning text-dark" : "bg-success";

        echo '
                                <tr id="achat-'.$idAchat.'">
                                    <td class="fw-bold">#'.$idAchat.'</td>
                                    <td>'.h($a["date"]).'</td>
                                    <td class="text-primary fw-bold">'.number_format($a['total'], 2).' €</td>
                                    <td><span class="badge '.$badgeClass.'">'.$etat.'</span></td>
                                    <td class="text-center">
                                        <a href="index.php?module=restock&action=detailsAchat&idAchat='.$idAchat.'" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-eye"></i> Voir détails
                                        </a>
                                    </td>
                                ';
        if($_SESSION['role']==1){
            echo'
                <td class="text-end px-4">';

        if(strcmp($etat, "en cours") == 0) {
            echo '
                                        <button type="submit" name="idAchat" value="'.$idAchat.'" class="btn btn-success btn-sm shadow-sm">
                                            Finaliser l\'achat
                                        </button>';
        } else {
            echo '<span class="text-muted small italic">Aucune action</span>';
        }

        echo '
                                    </td>';}
                                    echo'
                                </tr>';
    }

    echo '
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>';
}

    public function detailsAchat(array $lignesAchat){
        echo "<h3> Détails de la commande: </h3> <br>";
        echo "<lu>";
        foreach($lignesAchat as $ligne){
            echo "<br><li><p class='details'>". h($ligne['nom']) ." x ". h($ligne['qte']) .", total : ". number_format($ligne['prix'],2)." € </li>";
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

<?php
require_once('utils/vue_generique.php');
    Class Vue_commande extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

    public function formulaire_début_commande(){

        echo '
            <form method="post" action="index.php?module=commande&action=traiter_debut_commande">
                    <p>Cliquer pour commencer une nouvelle commande : </p>

                    <button type="submit" class="btn btn-success btn-lg mt-3">
                        Commander
                    </button>

                    <input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">
            </form>
        ';
    }

    public function formulaire_commande($liste_prod, $type){


        echo '
        <form method="post" id="form-type" action="index.php?module=commande&action=reload" class="mb-4">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
        ';

        foreach($type as $i){
            echo '
                <button
                    type="submit"
                    name="bouton_type"
                    value="'.h($i['idType']).'"
                    class="btn btn-outline-primary"
                >
                    '.h($i['type']).'
                </button>
            ';
        }

        echo '
                <button type="submit" name="bouton_type" value="reset" class="btn btn-outline-secondary">
                    Tout
                </button>
            </div>
        </form>
        ';


        echo '
        <form method="post" action="index.php?module=commande&action=ajout_produit" id="form-commande">
            <div class="row g-4">
        ';

        $elem = 0;
        foreach($liste_prod as $p){
            $id = $p['idProd'];

            echo '
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <img src="'.$p["image"].'" class="card-img-top p-3" alt="'.h($p["nom"]).'" style="height:180px; object-fit:contain;">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">'.h($p["nom"]).'</h5>
                        <p class="card-text fw-bold">'.(h($p["prix"]/100)).' €</p>

                        <input type="hidden" name="produits['.$elem.'][id]" value="'.$id.'">

                        <div class="mt-auto">
                            <label class="form-label">Quantité</label>
                            <input
                                type="number"
                                class="form-control"
                                name="produits['.$elem.'][qte]"
                                min="0"
                                max="'.$p["quantite"].'"
                                value="0"
                                oninput="refreshPanier()"
                            >
                        </div>
                    </div>
                </div>
            </div>
            ';
            $elem++;
        }

        echo '
            </div>

            <div class="mt-4 p-4 bg-light rounded shadow-sm text-center">
                <h3>
                    Total commande :
                    <span id="total-prix" class="fw-bold text-success">0 €</span>
                </h3>

                <button type="submit" class="btn btn-success btn-lg mt-3">
                    🛒 Ajouter au panier
                </button>
            </div>

            <input type="hidden" name="token_csrf" value="'.$_SESSION['token'].'">
        </form>
        ';


        echo '
        <script>
        async function refreshPanier(){
            const formulaire = document.getElementById("form-commande");
            const données = new FormData(formulaire);

            const reponse = await fetch("index.php?module=commande&action=prix_total",{
                method: "POST",
                body : données
            });

            const reponseJSON = await reponse.json();
            document.getElementById("total-prix").innerText = reponseJSON.total + " €";
        }
        </script>
        ';
    }

public function finaliser_commande($liste_commande) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h3 class="mb-0 text-uppercase fw-bold" style="letter-spacing: 2px;">Historique de vos commandes</h3>
                    </div>
                    <div class="card-body p-0"> <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID Commande</th>
                                        <th>Prix Total</th>
                                        <th class="text-center">Détails</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>';

    foreach ($liste_commande as $c) {
        echo '
                                    <tr id="commande-'.$c["id"].'">
                                        <td class="ps-4 fw-bold text-primary">#'.$c["id"].'</td>
                                        <td><span class="badge bg-success fs-6">'.number_format($c['total_commande'], 2, ',', ' ').' €</span></td>
                                        <td class="text-center">
                                            <a href="index.php?module=historique&action=detailHistoClient&idCommande='.$c['id'].'" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-search me-1"></i> Voir Détails
                                            </a>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-dark btn-sm px-4" onclick="finCommandeAJAX('.$c['id'].')">
                                                Finaliser
                                            </button>
                                        </td>
                                    </tr>';
    }

    echo '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';


        echo'

    <script>
    async function finCommandeAJAX(id){
            const formulaire = document.getElementById("form-finCommande");
            const données = new FormData(formulaire);
            données.append("idCommande",id);


            const response = await fetch("index.php?module=commande&action=finCommande",{
                method: "POST",
                body : données
            });
            const reponseJSON = await response.json();

            const prodàEnlever = document.getElementById("commande-"+ id);
            alert("Commande finalisée avec succès !");
            prodàEnlever.remove();


    }

    </script>


    ';
}

    public function afficher_confirmation_commande($prix) {
        echo "<p>Le prix total est de : " . h($prix) . " €</p>";
    }

    public function affiche(){
        return $this->getAffichage();
    }
    public function message($txt){
        echo "<p>". $txt ."</p>";
    }

    

    public function vueCommandeProduit($prod){
        foreach($prod as $p){

            echo '<form method="post" action="index.php?module=restock&action=ajoutStock">';
            echo "<div id='restockDiv'>";
            echo "<p> Fournissseur : ". h($p['nomF']) ."</p>";
            echo "<p> Produit : ". h($p['nom']) ."</p>";
            echo "<p> Prix : ". number_format($p['prix']/100,2) ." €</p>";
            echo "<input type='hidden' name='idProd' value='". h($p['id']) ."'>";
            echo "<input type='number' name='quantite' min='1' max='1000' placeholder='0'>";

            echo " <button type='submit'>Valider</button>";
            echo "</div>";
            echo '<input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">';
            echo "</form>";
        }
    }


}


?>

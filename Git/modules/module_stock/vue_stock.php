<?php
require_once('utils/vue_generique.php');
Class Vue_stock extends VueGenerique{
    public function __construct(){
        parent::__construct();
    }

    public function afficheStock($liste_stock){
        echo'
        <form method="post" id="form-produit">
         <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="mb-0 text-uppercase fw-bold" style="letter-spacing: 2px;">Stock</h2><br>
                        <input type="search" name="rechercher" autocomplete="off" placeholder="Rechercher un produit" id="rechercher" oninput="rechercher_produit()">
                    </div>

                    <div class="card-body p-0"> <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                                        <th class="ps-4">Nom Produit</th>
                                        <th>Quantité</th>
                                        <th class="text-center">Seuil minimum</th>
                                        <th class="text-end pe-4">Statut</th>
                                        <th class="text-end pe-4">Action</th>
                    </tr>
                    </thead>
                    <tbody id="corps-tableau">
                     
                    ';                    
                     $this->affichageDefaut($liste_stock);
                     $this->recherche_dynamique();
                        echo'
                    </tbody>
                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>';



            


        }
private function recherche_dynamique() {
    echo '
    <script>
        async function rechercher_produit() {
            const formulaire = document.getElementById("form-produit");
            const donnees = new FormData(formulaire);

            const reponse = await fetch("index.php?module=stock&action=recherche", {
                method: "POST",
                body: donnees
            });

            const produits = await reponse.json();
            let corps = document.getElementById("corps-tableau");
            corps.innerHTML = "";

            produits.forEach(p => {
                // Détermination du badge selon le stock
                const estDispo = parseInt(p.quantite) >= parseInt(p.seuil);
                const badgeClass = estDispo ? "bg-success" : "bg-danger";
                const texte = estDispo ? "Disponible" : "Faible";

                // Construction de la ligne de tableau
                corps.innerHTML += `
                    <tr>
                        <td class="ps-4">${p.nom}</td>
                        <td>${p.quantite}</td>
                        <td class="text-center">${p.seuil}</td>
                        <td class="text-end pe-4">
                            <span class="badge ${badgeClass} text-white">${texte}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a class="btn btn-sm btn-outline-primary" href="index.php?module=commande&action=commandeProduit&idProd=${p.idProd}">
                                Restock
                            </a>
                        </td>
                    </tr>
                `;
            });
        }
    </script>';
}

        
        


    public function afficheNBProduit($nb){
        echo '<p id="nb">Nombre de produits en stock : '.$nb.'</p>';
    }
 public function affichageDefaut($liste_stock){
    foreach($liste_stock as $item){
        echo '<tr>
                <td class="ps-4">'. h($item['nom']) .'</td>
                <td>'. h($item['quantite']) .'</td>
                <td class="text-center">'. h($item['seuil']) .'</td>
                <td class="text-end pe-4">';
                    if($item["quantite"] >= $item["seuil"]){
                        echo '<span class="badge bg-success text-white">Disponible</span>';
                    } else {
                        echo '<span class="badge bg-danger text-white">Faible</span>';
                    }
        echo '  </td>
                <td class="text-end pe-4">
                    <a class="btn btn-sm btn-outline-primary" href="index.php?module=commande&action=commandeProduit&idProd='. h($item['idProd']) .'"> 
                        Restock 
                    </a>
                </td>
              </tr>';
    }
}
    public function affiche_menu($menu) {
    echo '
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                <h2 class="h4 mb-0">Menu de la buvette</h2>
                <div>
                    <a href="index.php?module=stock&action=afficheProdAjouter" class="btn btn-sm btn-success me-2">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                    <a href="index.php?module=stock&action=afficheProdRetirer" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Retirer
                    </a>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" action="index.php?module=stock&action=changeInfoMenu">
                    <input type="hidden" name="token_csrf" value="'.$_SESSION['token'].'"/>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom Produit</th>
                                    <th style="width: 200px;">Prix (en cts)</th>
                                    <th style="width: 200px;">Seuil minimum</th>
                                </tr>
                            </thead>
                            <tbody>';

    foreach($menu as $item) {
        $id = h($item['idProd']);
        echo '
                                <tr>
                                    <td>
                                        <input type="hidden" name="produit['.$id.'][idProd]" value="'.$id.'"/>
                                        <span class="fw-bold">'.h($item['nom']).'</span>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="produit['.$id.'][prix]" class="form-control" value="'.h($item['prix']).'">
                                            <span class="input-group-text">cts</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="produit['.$id.'][seuil]" class="form-control" value="'.h($item['seuil']).'">
                                    </td>
                                </tr>';
    }

    echo '
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>';
}

    public function afficheProduit($liste){
        echo'<h2>Produit à vendre</h2>
        <form method="POST" action="index.php?module=stock&action=ajouteProduit">
        <div class="TitreColonne">Nom Produit</div>
        <div class="TitreColonne">Prix</div>
        <div class="TitreColonne">Seuil minimum</div>';

        foreach($liste as $item){
            echo '
                        <input type="hidden" name="produit['.h($item['idProd']).'][idProd]" value="'.h($item['idProd']).'"/>
                        <div>'.h($item['nom']).'</div>
                        <div><input type="number" name="produit['.h($item['idProd']).'][prix]" placeholder="100"/></div>
                        <div><input type="number" name="produit['.h($item['idProd']).'][seuil]" placeholder="50"/></div>';
        }
        echo'<button type="submit"> Changer </button>
            </form>
            ';
    }

    public function afficheMenuDel($liste){
        echo'<h2>Produit à vendre</h2>
        <form method="POST" action="index.php?module=stock&action=retireProduit">
        <div class="TitreColonne">Nom Produit</div>';

        foreach($liste as $item){
            echo '
                <input type="checkbox" name="produit['.h($item['idProd']).'][idProd]" value="'.h($item['idProd']).'">
                <div>'.h($item['nom']).'</div>';
        }
        echo'<button type="submit"> Retirer </button>
            </form>
            ';
    }

 public function afficheInitStockJour($produits) {
    echo '
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="card-title mb-0">Vérification des arrivages</h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="index.php?module=stock&action=creeInventaire" id="form-produit">
                            <input type="hidden" name="token_csrf" value="'.$_SESSION['token'].'"/>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Nom Produit</th>
                                            <th scope="col" style="width: 200px;">Quantité Reçue</th>
                                            <th scope="col" class="text-center">Quantité prévue</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    foreach ($produits as $item) {
        $id = h($item['idProd']);
        $nom = h($item['nom']);
        $qteActuelle = h($item['quantite']);
        $qtePrevue = $item['quantite'] + 25; // Ta logique métier (+25)

        echo '
                                        <tr>
                                            <td>
                                                <input type="hidden" name="produit['.$id.'][idProd]" value="'.$id.'">
                                                <span class="fw-bold">'.$nom.'</span>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" 
                                                           name="produit['.$id.'][qteArrive]" 
                                                           class="form-control" 
                                                           value="'.$qteActuelle.'" 
                                                           min="0">
                                                    <span class="input-group-text">unités</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark fs-6">'.$qtePrevue.'</span>
                                            </td>
                                        </tr>';
    }

    echo '
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                    <i class="bi bi-check-circle me-2"></i>Valider l\'inventaire
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

    public function afficheNBDispo($dispo){
        echo '<p id="nb">Nombre de produits disponibles : '. h($dispo) .'</p>';
    }

    public function afficheNBFaible($faible){
        echo '<p id="nb">Nombre de produits en faible quantité : '. h($faible) .'</p>';
    }
    public function affiche(){
        return $this->getAffichage();
    }

    public function message($txt){
        echo "<p>$txt</p>";
    }
}

?>
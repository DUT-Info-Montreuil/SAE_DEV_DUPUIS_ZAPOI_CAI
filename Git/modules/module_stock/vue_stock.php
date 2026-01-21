<?php
require_once('utils/vue_generique.php');
Class Vue_stock extends VueGenerique{
    public function __construct(){
        parent::__construct();
    }

    public function afficheStock($liste_stock){
        echo'
        <form method="post" id="form-produit">
         <input type="search" name="rechercher" autocomplete="off" placeholder="Rechercher un produit" id="rechercher" oninput="rechercher_produit()">
         <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="mb-0 text-uppercase fw-bold" style="letter-spacing: 2px;">Stock</h2>
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
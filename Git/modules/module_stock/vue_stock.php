<?php
require_once('utils/vue_generique.php');
Class Vue_stock extends VueGenerique{
    public function __construct(){
        parent::__construct();
    }

    public function afficheStock($liste_stock){
        echo '<h2>Stock des Produits</h2>';

        echo '
                <form method="post" id="form-produit">
                <input type="search" name="rechercher" autocomplete="off" placeholder="Rechercher un produit" id="rechercher" oninput="rechercher_produit()">
                <div id="tableauStock">

                    <div class="TitreColonne">Nom Produit</div>
                    <div class="TitreColonne">Quantité</div>
                    <div class="TitreColonne">Seuil minimum</div>
                    <div class="TitreColonne">Statut</div>
                    <div class="TitreColonne">Action</div>



                    <div id="corps-tableau" style="display : contents;">';
                    $this->affichageDefaut($liste_stock);
                    echo'</div>';


                    $this->recherche_dynamique();

                echo '</div>';


            


        }
    private function recherche_dynamique() {
        echo '
        <script>


            async function rechercher_produit() { // Mémo pour moi , pour me familiariser avec JS
                //DEBUT récolte de données

                const formulaire = document.getElementById("form-produit"); // document est la page web entière, .getElementById recupère la balise html avec l id spécifié
                const donnees = new FormData(formulaire); // FormData est un objet qui stocke tt les donnees du formulaire

                const reponse = await fetch("index.php?module=stock&action=recherche", { // await sert à dire qu on doit pas passer à autre chose tant qu on a pas reçu une réponse ensuite je fetch des données (ici appel à la méthode getRecherche())
                    method: "POST",
                    body: donnees
                });

                const produits = await reponse.json();
                //FIN récolte de données

                //Je récupère la div crée dans la fonction d affichage et en faisant .innerHTML je peux manipuler du HTML
                let corps = document.getElementById("corps-tableau");
                corps.innerHTML = "";

                //forEach dans le style d un lambda un peu, je dis que pour chaque p dans produit je vais faire l instruction qui se trouve dans les {}
                // utiliser des back ticks (`) si on utilise l insertion avec $
                produits.forEach(p => {
                    const couleur = (parseInt(p.quantite) >= parseInt(p.seuil)) ? "green" : "red";
                    const texte = (parseInt(p.quantite) >= parseInt(p.seuil)) ? "Disponible" : "Faible";
                    corps.innerHTML += `
                        <div>${p.nom}</div>
                        <div>${p.quantite}</div>
                        <div>${p.seuil}</div>
                        <div style="color:${couleur}">${texte}</div>
                        <div><a href="index.php?module=commande&action=commandeProduit&idProd=${p.idProd}">
                                    Restock
                                </a></div>
                    `;
                });
            }
        </script>';
    }

        
    public function affichageDefaut($liste_stock){
        foreach($liste_stock as $item){
                        echo '

                        <div>'. h($item['nom']) .'</div>
                        <div>'. h($item['quantite']) .'</div>
                        <div>'. h($item['seuil']) .'</div>
                        <div>';
                            if($item["quantite"] >= $item["seuil"]){
                                echo '<span style="color:green">Disponible</span>';
                            }
                            else{
                                echo '<span style="color:red">Faible</span>';
                            }
                        echo'</div>';
                        echo' <div> <a href="index.php?module=commande&action=commandeProduit&idProd='. h($item['idProd']) .'"> Restock </a></div>';
                    }
                    echo '</div>';
    }
    public function afficheNBDispo($dispo){
        echo '<div>Nombre de produits disponibles : '. h($dispo) .'</div>';
    }

    public function afficheNBFaible($faible){
        echo '<p id="nb">Nombre de produits en faible quantité : '. h($faible) .'</p>';
    }

    public function affiche_menu($menu){
        echo'<h2>Menu de la buvette</h2> <a href="index.php?module=stock&action=afficheProdAjouter" id="add"> Ajouter des produits au menu</a>
        <form method="POST" action="index.php?module=stock&action=changeInfoMenu">
        <div class="TitreColonne">Nom Produit</div>
        <div class="TitreColonne">Prix</div>
        <div class="TitreColonne">Seuil minimum</div>';
        
        foreach($menu as $item){
            echo '
                        <input type="hidden" name="produit['.h($item['idProd']).'][idProd]" value="'.h($item['idProd']).'"/>
                        <div>'.h($item['nom']).'</div>
                        <div><input type="number" name="produit['.h($item['idProd']).'][prix]" value="'. h($item['prix']) .'"/></div>
                        <div><input type="number" name="produit['.h($item['idProd']).'][seuil]" value="'. h($item['seuil']) .'"/></div>';
        }
        echo'<button type="submit"> Changer </button>
            </form>
            ';
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
    public function affiche(){
        return $this->getAffichage();
    }

    public function message($txt){
        echo "<p>$txt</p>";
    }
}

?>
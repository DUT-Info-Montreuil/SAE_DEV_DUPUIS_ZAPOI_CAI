<?php
require_once('utils/vue_generique.php');
Class Vue_stock extends VueGenerique{
    public function __construct(){
        parent::__construct();
    }

    public function afficheStock($liste_stock){
        echo '<h2>Stock des Produits</h2>
             
                <input type="search" placeholder="Rechercher un produit"><button id="rechercher" type="submit">Rechercher</button>
                <div id="tableauStock">
                

                    <div class="TitreColonne">Nom Produit</div>
                    <div class="TitreColonne">Quantité</div>
                    <div class="TitreColonne">Seuil minimum</div>
                    <div class="TitreColonne">Statut</div>
                
                  ';
        
            
            foreach($liste_stock as $item){
                echo '

                <div>'. $item['nom'] .'</div>
                <div>'. $item['quantite'] .'</div>
                <div>'. $item['seuil'] .'</div>
                <div>';
                    if($item["quantite"] >= $item["seuil"]){
                        echo '<span style="color:green">Disponible</span>';
                    }
                    else{
                        echo '<span style="color:red">En faible quantité</span>';
                    }

                echo'</div>';
            }
            echo '</div>';
        
        
    }

    public function afficheNBProduit($nb){
        echo '<p id="nb">Nombre de produits en stock : '.$nb.'</p>';
    }
    public function afficheNBDispo($dispo){
        echo '<p id="nb">Nombre de produits disponibles : '.$dispo.'</p>';
    }

    public function afficheNBFaible($faible){
        echo '<p id="nb">Nombre de produits en faible quantité : '.$faible.'</p>';
    }
    public function affiche(){
        return $this->getAffichage();
    }
}

?>
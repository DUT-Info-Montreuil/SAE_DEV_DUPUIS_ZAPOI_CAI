<?php
require_once('utils/vue_generique.php');
Class Vue_stock extends VueGenerique{
    public function __construct(){
        parent::__construct();
    }

    public function afficheStock($liste_stock){
        echo '<h2>Stock des Produits</h2>
                <table>
                <input type="search" placeholder="Rechercher un produit"><button type="submit">Rechercher</button>
                <thread>
                    <tr>
                        <th>ID</th>
                        <th>Nom Produit</th>
                        <th>Quantité</th>
                        <th>Seuil</th>
                        <th>Statut</th>
                    </tr>
                    </thread>
                    <tbody>';
        for($i= 0;$i<25;$i++){
            
        foreach($liste_stock as $item){
            echo '<tr>
            <td>'.$item['idProd'] .'</td>
            <td>'. $item['nom'] .'</td>
            <td>'. $item['quantite'] .'</td>
            <td>'. $item['seuil'] .'</td>
            <td>';
                if($item['quantite'] >= $item['seuil']){
                    echo "<span style='color:green'>Disponible</span>";
                }
                else{
                    echo "<span style='color:red'>En faible quantité</span>";
                }
            '</td>
            </tr>';
        }
    }
        echo '</tbody> </table>';
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
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
                <input type="submit" value="Démarrer la commande">
        </form>
    ';
}

public function formulaire_commande($liste_prod){
echo '<form method="post" action="index.php?module=commande&action=ajout_produit" id="form-commande">';
    $elem = 0;
    foreach($liste_prod as $p){
        $id = $p['idProd'];
        echo '
            <input type="hidden" name="produits['.$elem.'][id]" value="'.h($id).'">
            <input type="number" name="produits['.$elem.'][qte]" min="1" max="100" placeholder="0" oninput="refreshPanier()">
                <img src="'.h($p["image"]).'" alt="'.h($p["nom"]).'" width="100">

            <p>'.h($p["nom"]).' - '.( h($p["prix"]/100)).' €</p>

        ';
        $elem += 1;
    }
    echo '<h3> Total commande : <span id="total-prix">0</span></h3>';
    echo'<button type="submit">Panier</button>';
    echo '</form>';
    echo '
    <script>
     function refreshPanier(){
        const formulaire = document.getElementById("form-commande");
        const données = new FormData(formulaire);

        fetch("index.php?module=commande&action=prix_total",{
            method: "POST",
            body : données
        })
        .then(response=>response.json())
        .then(data=> {
        document.getElementById("total-prix").innerText = data.total +"€";
        })
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
            echo "</form>";
        }
    }


}


?>

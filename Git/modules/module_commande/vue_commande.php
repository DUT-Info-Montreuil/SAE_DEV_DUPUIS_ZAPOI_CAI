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
echo '<form method="post" action="index.php?module=commande&action=ajout_produit">';
    $elem = 0;
    foreach($liste_prod as $p){
        $id = $p['idProd'];
        echo '
            <input type="hidden" name="produits['.$elem.'][id]" value="'.$id.'">
            <input type="number" name="produits['.$elem.'][qte]" min="1" max="100" placeholder="0">
                <img src="'.$p["image"].'" alt="'.$p["nom"].'" width="100">

            <p>'.$p["nom"].' - '.($p["prix"]/100).' €</p>

        ';
        $elem += 1;
    }
echo'<button type="submit">Panier</button>
</form>';
}
    public function afficher_confirmation_commande($prix) {
        echo "<p>Le prix total est de : " . $prix . " €</p>";
    }

    public function affiche(){
        return $this->getAffichage();
    }
    public function message($txt){
        echo "<p>$txt</p>";
    }

    }

?>
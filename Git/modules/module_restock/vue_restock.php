<?php
require_once('utils/vue_generique.php');
    Class Vue_restock extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

    public function produitsAchat(array $produits){
        echo '<h3> Produits à l\'achat : </h3> <br>
        
        <form method="post" action="index.php?module=restock&action=ajoutStock">';
        
        foreach($produits as $p){
            echo '<br><li><p>'. h($p['nom']) .' , Prix : '.number_format($p['prix'],2).' € , Fournisseur : '. h($p['fournisseur']) .'

                <input type="hidden" name="inventaire[]" value="'. h($p['idInventaire']) .'">
                <input type="hidden" name="produit[]" value="'. h($p['idProd']) .'">
                <input type="number" name="quantite[]" min="0" max="1000" placeholder="0">';
           
        }

        echo '<button type="submit"> Commander </button>
        </form>
        ';
             
    }

    public function fournisseurs(array $fournisseurs){
         echo '<h2>Choisissez un fournisseur :</h2>';

        foreach($fournisseurs as $f){
            echo '<form method="post" action="index.php?module=restock&action=produitsF&fournisseur='. h($f['id']) .'">
            <input type="hidden" name="fournisseur" value="'. h($f['nom']) .'">
            <button type="submit"><img src="fonce-fond-abstrait.jpg" alt="Une image du logo" style="width: 15%; height: 2%;">'
            . h($f['nom']) .'</button>
            </form>';
        }

    }

    public function affiche(){
        return $this->getAffichage();
    }

    public function message($txt){
                echo "<p>$txt</p>";
            }

}

?>

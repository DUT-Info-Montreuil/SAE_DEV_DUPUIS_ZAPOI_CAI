<?php
require_once('utils/vue_generique.php');
    Class Vue_restock extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

    public function produitsAchat(array $produits){
        echo "<h3> Produits à l'achat : </h3> <br>";
        echo "<lu>";
        foreach($produits as $p){
            echo "<br><li><p>".$p['nom']." , Prix : ".number_format($p['prix'],2)." € , Fournisseur : ".$p['fournisseur']."
                (ICI POSSIBILITE DE COMMANDER CE PRODUIT)
                </li>";
        }
        echo "</lu>";
    }

    public function fournisseurs(array $fournisseurs){
         echo '<h2>Choisissez un fournisseur :</h2>';
        foreach($fournisseurs as $f){
            echo '<form method="post" action="index.php?module=restock&action=produitsF&fournisseur='.$f['id'].'">
            <input type="hidden" name="fournisseur" value="'.$f['nom'].'">
            <button type="submit"><img src="fonce-fond-abstrait.jpg" alt="Une image du logo" style="width: 15%; height: 2%;">'
            .$f['nom'].'</button>
            </form>';
        }

    }



    public function affiche(){
        return $this->getAffichage();
    }


}

?>

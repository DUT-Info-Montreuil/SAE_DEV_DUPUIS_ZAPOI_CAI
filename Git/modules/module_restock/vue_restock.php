<?php
require_once('utils/vue_generique.php');
    Class Vue_restock extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

    public function produitsAchat(array $produits){
        echo '<h3> Produits à l\'achat : </h3> <br>
        
        <form method="post" action="index.php?module=restock&action=ajoutAchat">';
        $index=0;
        foreach($produits as $p){

            echo '<br><li><p>'. h($p['nom']) .' , Prix : '.number_format($p['prix'],2).' € , Fournisseur : '. h($p['fournisseur']) .'


                <input type="hidden" name="produit['.$index.'][idProd]" value="'. h($p['idProd']) .'">
                <input type="hidden" name="produit['.$index.'][prix]" value="'. h($p['prix']) .'">
                <input type="hidden" name="produit['.$index.'][idF]" value="'. h($p['idF']) .'">
                <input type="number" name="produit['.$index.'][qte]" min="0" max="1000" value="0" placeholder="0">';
           $index++;

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
            <input type="hidden" name="token_csrf" value = "'.$_SESSION['token'].'">
            </form>';
        }

    }

    public function finaliserAchats($liste_achats) {

        echo '
            <form method = "POST" action="index.php?module=restock&action=ajoutStock">
            <div id="listeCommande">
                <div class="TitreColonne">ID de la commande</div>
                <div class="TitreColonne">Date de la commande</div>
                <div class="TitreColonne">Prix de la commande</div>
                <div class="TitreColonne">Etat commande </div>
                <div class="TitreColonne">Détails de la commande </div>
                <div class ="TitreColonne">Action</div>';
                foreach($liste_achats as $a){

                echo'

                    <input type="hidden" name="idAchat" value="'. h($a['id']) .'">

                    <div id = "achat-'. h($a["id"]) .'" class=ligneCommande style="display : contents;">

                    <div class="elt">'. h($a["id"]) .'</div>
                    <div class="elt">'. h($a["date"]) .'</div>
                    <div class="elt">'. h($a['total']) .'</div>
                    <div class="elt">'. h($a['état']) .'</div>
                    <a href="index.php?module=restock&action=detailsAchat&idAchat='. h($a["id"]) .'" class="elt"> Détails </a>';

                    if(strcmp($a['état'], "en cours") == 0){
                        echo ' <button type="submit" name="finAchat">
                                    Finaliser
                                </button>';
                    }


                echo '</div>

                ';
                }

            echo '</div>';

            echo '</form>';

    }

    public function detailsAchat(array $lignesAchat){
        echo "<h3> Détails de la commande: </h3> <br>";
        echo "<lu>";
        foreach($lignesAchat as $ligne){
            echo "<br><li><p class='details'>". h($ligne['nom']) ." x ". h($ligne['quantite']) .", total : ". number_format($ligne['total'],2)." € </li>";
        }
        echo "</lu>";
    }

    public function affiche(){
        return $this->getAffichage();
    }

    public function message($txt){
                echo "<p>$txt</p>";
            }

}

?>

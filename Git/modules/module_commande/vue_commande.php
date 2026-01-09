<?php
require_once('utils/vue_generique.php');
    Class Vue_commande extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

    public function formulaire_début_commande(){
        echo
        '
            <form method="post" action="index.php?module=commande&action=ajout_début_commande">
                    <p>Ajouter une commande : </p>
                    <input type="text" name="ajouter_commande" maxlength="50">
                    <input type="submit" value="Commande">
            </form>
        ';
    }
    public function formulaire_commande($liste_prod){
        foreach($liste_prod as $p){
        echo'
            <form method="post" action="index.php?module=commande&action=ajout_produit">
                <input type="hidden" name="id_produit['.$p["id"].'][id] value="'.$p["id"].'">
                <input type="number" name="nb_produit['.$p["id"].'][quantite]" max="100">
                <button type="submit" style="border:none;background:none;padding:0;cursor:pointer;">
                <img src="'.$p["image"].'" alt="'.$p["nom"].'" width="200">
                </button>
                <p>'.$p["nom"].'</p>
                <p>'.$p["prix"].' €</p>
            </form>
            ';
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
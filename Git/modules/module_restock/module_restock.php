<?php
include_once "controleur_restock.php";
class Mod_restock {
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "listProduits";

        $this->vue = new Vue_restock();
        $this->cont = new Cont_restock($this->vue);

        switch($action) {
            case "listProduits":
                $produits = $this->cont->recupProduitsAchat();
                $this->cont->afficherProduitsAchat($produits);
                break;

            case "fournisseurs":
                $fournisseurs = $this->cont->recupFournisseurs();
                $this->cont->afficherFournisseurs($fournisseurs);
                break;

            case "produitsF":
                $f = (int) $_GET['fournisseur'] ?? "0";
                $prodF = $this->cont->recupProduitsF($f);
                $this->cont->afficherProduitsAchat($prodF);
                break;

            case "ajoutStock":
                $idProd = (array) $_POST['produit'] ?? null;
                $idInventaire = (array) $_POST['inventaire'] ?? null;
                $quantite = (array) $_POST['quantite'] ?? null;


                if($idProd && $idInventaire && $quantite){
                    $this->cont->ajoutStock($idProd, $idInventaire, $quantite);
                    echo "Stock mis à jour avec succès !";
                }
                break;

        }
	}

    public function affiche(){
        return $this->cont->affiche();
    }
}

?>
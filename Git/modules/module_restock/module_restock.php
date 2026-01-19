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
                $f = $_GET['fournisseur'] ?? "0";
                $prodF = $this->cont->recupProduitsF($f);
                $this->cont->afficherProduitsAchat($prodF);
                break;

            case "ajoutStock":
                $id = $_POST['idProd'] ?? null; 
                $quantite = $_POST['quantite'] ?? 0;

                if($id && $quantite > 0){
                    $this->cont->ajoutStock($id, $quantite);
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
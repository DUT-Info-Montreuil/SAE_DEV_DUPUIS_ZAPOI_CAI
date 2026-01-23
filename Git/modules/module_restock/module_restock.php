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
                if(isset($_GET['idProd'])){
                    $produits = $this->cont->recupProduitsAchat($_GET['idProd']);
                }
                else{
                    $produits = $this->cont->recupProduitsAchat(null);
                }

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

            case "ajoutAchat":

                $fonds = $this->cont->fondsSuffisants();

                if($fonds){
                    $this->cont->ajoutAchat();
                }

                break;

            case "afficherAchats";

                $achats = $this->cont->recupAchats();
                $this->cont->afficherAchats($achats);
                break;

            case "detailsAchat";
                $idAchat = $_GET['idAchat'];
                $details = $this->cont->recupDetailsAchat($idAchat);
                $this->cont->afficherDetailsAchat($details);
                break;

            case "ajoutStock";
                $idAchat = $_POST['idAchat'];
                $details = $this->cont->recupDetailsAchat($idAchat);

                $this->cont->parcourirLignes($details);

                $this->cont->finaliserAchat($idAchat);

                echo "Stock mis à jour avec succès !";
                break;

        }
	}

    public function affiche(){
        return $this->cont->affiche();
    }
}

?>
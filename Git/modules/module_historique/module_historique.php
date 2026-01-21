<?php
include_once "controleur_historique.php";
include_once "vue_historique.php";
class Mod_historique{
    private $vue;
    private $cont;

    public function __construct(){
        $action = $_GET['action'] ?? "historique_client";

        $this->vue = new Vue_historique();
        $this->cont = new Cont_historique($this->vue);


        switch($action) {

            case "historique_client":
                $histo = $this->cont->modHistoriqueClient();
                $this->cont->afficherHistoriqueClient($histo);
                break;
            case "detailHistoClient":
                $idCommande = (int) $_GET['idCommande'];
                $detailsCommande = $this->cont-> modDetailsCommande($idCommande);
                $this->cont->afficherDetailsCommande($detailsCommande);
                break;

            case "historiqueAchatFournisseur":
                $histo = $this->cont->modHistoriqueAsso(); //TODO utiliser vue deja fait pour client
                $this->cont->afficherHistoriqueAsso($histo);
                break;

            case "detailHistoAchat":
                $idCommande = (int) $_GET['idCommande'];
                $detailsCommande = $this->cont->modDetailsCommande($idCommande);
                $this->cont->afficherDetailsCommande($detailsCommande);
                break;
        }

    }

    public function affiche(){
        return $this->cont->affiche();
    }
}
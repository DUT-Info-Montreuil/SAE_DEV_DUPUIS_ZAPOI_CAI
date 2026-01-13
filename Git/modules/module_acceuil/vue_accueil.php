<?php
require_once("utils/vue_generique.php");
Class Vue_accueil extends VueGenerique{
    public function __construct(){
        parent::__construct();
    }
    public function afficher_accueil() {

            echo '<div class="card shadow-sm p-4">
                    <h1 class="text-center mb-4 text-primary">Bienvenue sur ALACOOL</h1>

                    <h3 class="mt-4">Objectifs du Projet</h3>
                    <p>L’objectif principal est de remplacer l’ancien système de cartes physiques par une solution numérique sécurisée et fluide, permettant une gestion en temps réel des stocks et des flux financiers.</p>

                    <h3 class="mt-4">Points clés</h3>
                    <ul>
                        <li>Gérer plusieurs associations de manière indépendante.</li>
                        <li>Sécuriser les transactions via un système de comptes prépayés.</li>
                        <li>Interface mobile fluide pour les barmen.</li>
                        <li>Automatiser le suivi logistique et les inventaires.</li>
                    </ul>
                  </div>';
        }
    public function affiche(){
         return $this->getAffichage();
        }
}
?>
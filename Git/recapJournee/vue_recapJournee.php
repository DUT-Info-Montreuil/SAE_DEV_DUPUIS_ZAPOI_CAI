<?php
require_once('utils/vue_generique.php');
    Class Vue_connexion extends VueGenerique{
        public function __construct(){

        }
        
    public function recapitulatif(int $recette){
        echo
            '
            
            '
        ;
    }

    public function benefDuJour(){
        echo 
            '
            
            '
        ;
    }

    public function recap_semaine(){
        echo
            '
            
            '
        ;
    }

    public function recap_semaine(){
        echo
            '
            
            '
        ;
    }

    public function transactionsDuJour(){
        echo
            '
            
            '
        ;
    }

    public function Produits_vendus(){
        echo
            '
            
            '
        ;
    }
    

    public function menu(){
        echo
        '
            <a href="index.php?module=gestionnaire&action=recap">Récapitulatif de la semaine</a>
            
        ';

        }


    public function affiche(){
        return $this->getAffichage();
    }
    }
?>
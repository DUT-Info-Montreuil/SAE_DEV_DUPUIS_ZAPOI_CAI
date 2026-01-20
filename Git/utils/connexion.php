<?php
    Class Connexion {
        protected static PDO $bdd;
        public function __construct() {

        }

        public static function initConnexion() {
            self::$bdd = new PDO($dsn='mysql:host=database-etudiants.iut.univ-paris8.fr;dbname=dutinfopw201699',$user='dutinfopw201699',$password='juhugege');


            //self::$bdd = new PDO('mysql:host=localhost;dbname=gestion_bar;charset=utf8', 'root', '');
           //self::$bdd = new PDO('mysql:host=localhost;dbname=dutinfopw201699;charset=utf8', 'root', '');



        }
}


?>

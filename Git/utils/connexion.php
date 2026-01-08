<?php
    Class Connexion {
        protected static PDO $bdd;
        public function __construct() {

        }

        public static function initConnexion() {
        self::$bdd = new PDO('mysql:host=localhost;dbname=gestion_bar;charset=utf8', 'root', '');
        }
}


?>

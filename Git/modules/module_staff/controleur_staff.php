<?php
include_once("modele_staff.php");
include_once("vue_staff.php");
include_once("utils/token_csrf.php");

class Cont_staff {
    private $vue;
    private $modele;
    private $checker_csrf;

    public function __construct($vue) {
        $this->vue = $vue;
        $this->modele = new Mod_staff();
        $this->checker_csrf = new Token_CSRF();
    }

    public function gestionMembre() {
        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 4) {
            $membres = $this->modele->getMembres();
            $this->vue->listeMembre($membres);
        } else {
            $this->vue->message('Droit requis non perçu.');
        }
    }

    public function promoteMembre() {
        if ($this->checker_csrf->check_csrf()) {
            if ($_SESSION['role'] == 1) {
                if (isset($_POST['role']) && isset($_POST['choix'])) {
                    $role = $_POST['role'];
                    foreach ($_POST['choix'] as $idCompte) {
                        $this->modele->promoteMembre($idCompte, $role);
                    }
                }
                $this->gestionMembre();
            } else {
                $this->vue->message('Droit requis non perçu.');
            }
        } else {
            $this->vue->message("Token invalide");
        }
    }

    public function affiche() {
        return $this->vue->affiche();
    }
}
?>
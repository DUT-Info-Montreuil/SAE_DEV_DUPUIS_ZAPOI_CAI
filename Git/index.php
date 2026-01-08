<?php
session_start();
$module = $_GET['module'] ?? 'connexion';




if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = [];
}

switch($module) {
    case 'connexion':
        include_once "modules/module_connexion/module_connexion.php";
        $mod = new Mod_connexion();
        $moduleContent=$mod->affiche();
        break;
    case 'solde':
        include_once "modules/module_solde/module_solde.php";
        $mod = new Mod_solde();
        $moduleContent=$mod->affiche();
        break;
    default:
        echo "<p>Module inconnu.</p>";
        break;
}
echo $moduleContent;

?>

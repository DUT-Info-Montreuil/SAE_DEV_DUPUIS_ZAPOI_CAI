<?php

$module = $_GET['module'] ?? 'stock';


session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = [];
}

switch($module) {
    case 'connexion':
        include_once "modules/module_connexion/module_connexion.php";
        $mod = new Mod_connexion();
        $moduleContent=$mod->affiche();
        break;
    case 'stock':
        include_once "modules/module_stock/module_stock.php";
        $mod = new Mod_stock();
        $moduleContent=$mod->affiche();
        break;
    default:
        echo "<p>Module inconnu.</p>";
        break;
}
echo $moduleContent;

include_once "template.php";
?>

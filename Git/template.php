<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="template.css">
    <title>Alacool</title>
</head>
<body>
    <header>
        <h1>Alacool</h1>
        <?php
        if ($_SESSION['connecté'] == true){ ?>
            <form id="formDeco" method="post" action="index.php?module=connexion&action=deconnexion">
                <button id="Deco" type="submit" name="deconnexion">Déconnexion</button>
            </form>
            <a href="index.php?module=connexion&action=nouvelleAsso">Association</a>
        <?php
        }
        ?>

        <nav>
        <?php
        if ($_SESSION['connecté'] == false){ ?>
            <a href="index.php?module=connexion&action=inscription">Inscription</a>
            <a href="index.php?module=connexion&action=connexion">Connexion</a>
        <?php
        }
        else if (isset($_SESSION['role']) == true){
            if ($_SESSION['role'] == 1){ // Gestionnaire ?>
                <a href="index.php?module=recapJournee&action=recap">Récapitulatif du jour</a>
                <a href="index.php?module=recapJournee&action=recapSemaine">Récapitulatif de la semaine</a>
                <a href="index.php?module=stock&action=affiche_stock">Stock</a>
        <?php
            }
            else if ($_SESSION['role'] == 2){ // Barman ?>
                <a></a>
        <?php
            }
            else { // Client ?>
                <a href="index.php?module=solde&action=page_solde">Solde</a>
        <?php
            }
        } ?>
        </nav>
    </header>

    <main>
        <?php echo $moduleContent; ?>
    </main>

    <footer>
        Site réalisé par Luc CAI & Edin DUPUIS & Denis ZAPOI | Tous droits réservés
    </footer>
</body>
</html>


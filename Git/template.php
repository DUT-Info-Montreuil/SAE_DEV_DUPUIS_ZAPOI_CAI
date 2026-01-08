<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alacool</title>
</head>
<body>
    <header>
        <h1>Alacool</h1>
        <nav>
             <?php
             if ($_SESSION['connecté']==true){ ?>
                            <form method="post" action="index.php?module=connexion&action=deconnexion">
                                <button type="submit" name="deconnexion">Déconnexion</button>
                            </form>
            <?php
            }
            ?>
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
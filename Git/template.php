<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Alacool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="template3.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header class="bg-dark text-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark container">
            <a class="navbar-brand fw-bold" href="index.php">ALACOOL
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if ($_SESSION['connecté'] && isset($_SESSION['role']) ): ?>
                        <?php if ($_SESSION['role'] == 1): // Gestionnaire ?>
                            <li class="nav-item"><a class="nav-link" href="index.php?module=recapJournee&action=recapDuJour">Récap Jour</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?module=stock&action=affiche_stock">Stock</a></li>
                        <?php elseif ($_SESSION['role'] == 3): // Client ?>
                            <li class="nav-item"><a class="nav-link" href="index.php?module=solde&action=page_solde">Mon Solde</a></li>
                            <li class="nav-item"><a class="nav-link btn btn-primary btn-sm text-white ms-lg-2" href="index.php?module=commande&action=ajout_debut_commande">Commander</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?module=historique&action=historique_client">Historique des commandes</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?module=connexion&action=inscription">Inscription</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?module=connexion&action=connexion">Connexion</a></li>
                    <?php endif; ?>
                </ul>

                <?php if ($_SESSION['connecté']): ?>
                    <form class="d-flex" method="post" action="index.php?module=connexion&action=deconnexion">
                        <button class="btn btn-outline-danger btn-sm" type="submit">Déconnexion</button>
                    </form>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container my-5 flex-grow-1">
        <div class="card shadow-sm p-4">
            <?php echo $moduleContent; ?>
        </div>
    </main>

    <footer class="bg-light text-center py-4 border-top mt-auto">
        <div class="container text-muted">
            <small>Site réalisé par Luc CAI & Edin DUPUIS & Denis ZAPOI | Tous droits réservés</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


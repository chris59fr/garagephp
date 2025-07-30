<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Garage V. Parrot') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <h1><a href="/">Garage V. Parrot</a></h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/cars">Tableau de bord</a>
                <form action="/logout" method="post" style="display:inline;">
                    <button type="submit" class="button-link">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="/login">Espace Pro</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">
        <?= $content ?? '' ?>
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> Garage V. Parrot - Tous droits réservés.</p>
    </footer>
</body>
</html>
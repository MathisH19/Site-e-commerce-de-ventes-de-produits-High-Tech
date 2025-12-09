<?php
// S'assurer que la connexion PDO est disponible même en accès direct
if (!isset($pdo)) {
    $pdo = require_once dirname(__DIR__) . '/config/config.php';
}

$promos = $pdo->query("SELECT * FROM produits WHERE is_promo = 1 LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$nouveautes = $pdo->query("SELECT * FROM produits WHERE is_new = 1 LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$bestsellers = $pdo->query("SELECT * FROM produits WHERE is_best_seller = 1 LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TechFlow - Accueil</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>


<main class="main">

    <section class="bloc">
        <h2 id="title">Promotions</h2>
        <div class="carousel">
            <?php foreach ($promos as $p): ?>
                <a class="card" href="index.php?page=pageProduit&id=<?= (int)$p['idProduits'] ?>">
                    <img src="assets/img/<?= htmlspecialchars($p['image_produit']) ?>"
                         alt="<?= htmlspecialchars($p['nom_produit']) ?>">
                    <div class="card-info">
                        <span class="card-name"><?= htmlspecialchars($p['nom_produit']) ?></span>
                        <span class="card-price"><?= number_format($p['prix'], 2) ?> €</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bloc">
        <h2 id="title">Les nouveautés</h2>
        <div class="carousel">
            <?php foreach ($nouveautes as $p): ?>
                <a class="card" href="index.php?page=pageProduit&id=<?= (int)$p['idProduits'] ?>">
                    <img src="assets/img/<?= htmlspecialchars($p['image_produit']) ?>"
                         alt="<?= htmlspecialchars($p['nom_produit']) ?>">
                    <div class="card-info">
                        <span class="card-name"><?= htmlspecialchars($p['nom_produit']) ?></span>
                        <span class="card-price"><?= number_format($p['prix'], 2) ?> €</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bloc">
        <h2 id="title">Best-sellers</h2>
        <div class="carousel">
            <?php foreach ($bestsellers as $p): ?>
                <a class="card" href="index.php?page=pageProduit&id=<?= (int)$p['idProduits'] ?>">
                    <img src="assets/img/<?= htmlspecialchars($p['image_produit']) ?>"
                         alt="<?= htmlspecialchars($p['nom_produit']) ?>">
                    <div class="card-info">
                        <span class="card-name"><?= htmlspecialchars($p['nom_produit']) ?></span>
                        <span class="card-price"><?= number_format($p['prix'], 2) ?> €</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

</main>

</body>
</html>


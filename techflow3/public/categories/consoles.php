<?php
// S'assurer que la connexion PDO est disponible même en accès direct
if (!isset($pdo)) {
    $pdo = require_once dirname(__DIR__) . '/../config/config.php';
}

$idCategorie = 4;

$sql = "SELECT idProduits, nom_produit, image_produit, prix FROM produits WHERE Categories_idCategories = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $idCategorie]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/categories.css">
    <title>Liste des produits</title>

</head>
<body>

<h2>Consoles</h2>

<div class="container-produits">
    <?php foreach ($produits as $produit):?>

        <div class="card-produit">
            <img src="assets/img/<?= htmlspecialchars($produit['image_produit']) ?>" alt="<?= htmlspecialchars($produit['nom_produit']) ?>">
            <h3><?= htmlspecialchars($produit['nom_produit']) ?></h3>
            <a href="index.php?page=pageProduit&id=<?= (int)$produit['idProduits'] ?>" class="btn"><?= htmlspecialchars($produit['prix']) ?> €</a>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>

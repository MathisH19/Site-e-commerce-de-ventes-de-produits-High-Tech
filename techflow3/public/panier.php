<?php
// public/panier.php

// Sécurité : on vérifie que l'on passe bien par l'index
if (!isset($pdo)) { exit("Accès direct interdit"); }

// Calcul du total global
$totalPanier = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $totalPanier += $item['prix'] * $item['quantite'];
    }
}
?>

<style>
    /* --- CSS MODERNE STYLE APPLE --- */
    .cart-wrapper {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Roboto', -apple-system, sans-serif;
        color: #1d1d1f;
    }

    h1 { font-size: 2.5rem; margin-bottom: 30px; font-weight: 700; }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }

    .cart-table th {
        text-align: left;
        padding: 15px;
        border-bottom: 1px solid #e5e5e5;
        color: #86868b;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .cart-table td {
        padding: 20px 15px;
        border-bottom: 1px solid #e5e5e5;
        vertical-align: middle;
    }

    /* Colonne Image */
    .cart-img-col { width: 100px; }
    .cart-thumb {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border-radius: 8px;
        background: #fbfbfd;
        padding: 5px;
    }

    .product-name { font-weight: 600; font-size: 1.1rem; color: #1d1d1f; text-decoration: none;}
    .product-name:hover { color: #0071e3; }

    .product-price { color: #86868b; }

    /* Contrôles Quantité (+/-) */
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-qty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f5f5f7;
        color: #1d1d1f;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.2s;
    }
    .btn-qty:hover { background-color: #e8e8ed; }

    /* Bouton Supprimer */
    .btn-delete {
        color: #d93025;
        font-size: 0.9rem;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid transparent;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-delete:hover {
        background-color: #fff2f1;
        border-color: #ffcccc;
    }

    /* Footer */
    .cart-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 40px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e5e5;
    }
    .total-price { font-size: 1.6rem; font-weight: 700; }

    .btn-checkout {
        background-color: #488a86; /* Vert Sarcelle */
        color: white;
        padding: 14px 35px;
        border-radius: 99px;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.2s, opacity 0.2s;
    }
    .btn-checkout:hover { opacity: 0.9; transform: scale(1.02); }

    .empty-cart { text-align: center; padding: 60px; color: #86868b; font-size: 1.2rem; }
</style>

<div class="cart-wrapper">
    <h1>Votre Panier</h1>

    <?php if (empty($_SESSION['panier'])): ?>
        <div class="empty-cart">
            <p>Votre panier est vide pour le moment.</p>
            <br>
            <a href="/techflow3/index.php?page=dashboard" class="btn-checkout" style="background-color:#d62828;">Découvrir nos produits</a>
        </div>
    <?php else: ?>

        <table class="cart-table">
            <thead>
            <tr>
                <th>Image</th>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['panier'] as $id => $item):
                // Gestion sécurisée de l'image (la clé en session peut être 'image' ou 'image_produit')
                $imgRaw = $item['image'] ?? ($item['image_produit'] ?? 'Techflow.png');
                $imgName = basename($imgRaw);
                // Chemin web relatif (sans slash initial pour supporter un sous-dossier comme /techflow3)
                $imgWebPath = 'assets/img/' . $imgName;
                // Vérification côté serveur: si l'image n'existe pas, fallback sur le logo
                $imgFsPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $imgName;
                if (!is_file($imgFsPath)) {
                    $imgWebPath = 'assets/img/Techflow.png';
                }
                ?>
                <tr>
                    <td class="cart-img-col">
                        <img src="<?= htmlspecialchars($imgWebPath) ?>" alt="<?= htmlspecialchars($item['nom'] ?? 'Produit') ?>" class="cart-thumb">
                    </td>

                    <td>
                        <a href="/techflow3/index.php?page=pageProduit&id=<?= $id ?>" class="product-name">
                            <?= htmlspecialchars($item['nom']); ?>
                        </a>
                    </td>

                    <td class="product-price">
                        <?= number_format($item['prix'], 2); ?> €
                    </td>

                    <td>
                        <div class="qty-controls">
                            <a href="/techflow3/index.php?page=retirerPanier&id=<?= $id; ?>" class="btn-qty" title="Enlever 1">−</a>

                            <span><?= $item['quantite']; ?></span>

                            <a href="/techflow3/index.php?page=ajouterPanier&id=<?= $id; ?>" class="btn-qty" title="Ajouter 1">+</a>
                        </div>
                    </td>

                    <td>
                        <strong><?= number_format($item['prix'] * $item['quantite'], 2); ?> €</strong>
                    </td>

                    <td>
                        <a href="/techflow3/index.php?page=supprimerPanier&id=<?= $id; ?>"
                           class="btn-delete"
                           onclick="return confirm('Supprimer cet article du panier ?');">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-footer">
            <div class="total-price">
                Total : <?= number_format($totalPanier, 2); ?> €
            </div>
            <a href="/techflow3/?page=panierindex.php?page=page_de_paiement" class="btn-checkout">Valider la commande</a>
        </div>

    <?php endif; ?>
</div>
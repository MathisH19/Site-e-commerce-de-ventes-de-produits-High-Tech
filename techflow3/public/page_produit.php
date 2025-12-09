<?php
// --- 1. SÉCURITÉ ET RÉCUPÉRATION ---

// On vérifie que la connexion BDD est bien là (venant de index.php)
if (!isset($pdo)) {
    die("Erreur : Ce fichier doit être ouvert via index.php?page=pageProduit");
}

// Vérification de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div style='padding:50px; text-align:center;'>Aucun produit sélectionné. <a href='index.php'>Retour</a></div>";
    exit();
}

$id = (int) $_GET['id'];

// Récupération du produit
try {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE idProduits = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}

if (!$p) {
    echo "<div style='padding:50px; text-align:center;'>Produit introuvable. <a href='index.php'>Retour</a></div>";
    exit();
}

// --- 2. GESTION DES CHEMINS ---
// Nom de fichier image (on ne garde que le basename pour éviter tout chemin erroné)
$imageName = $p['image_produit'] ?? 'default.jpg';
$imageName = basename($imageName);

// Chemin web par défaut (utilisé dans l'attribut src)
$imageWebPath = 'assets/img/' . $imageName;

// Vérification côté serveur: si le fichier n'existe pas, on bascule sur un placeholder
$imageFsPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $imageName;
if (!is_file($imageFsPath)) {
    $imageWebPath = 'assets/img/Techflow.png'; // image de secours existante dans le repo
}

?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

    :root {
        --col-teal: #488a86;
        --col-red: #d93025;
        --col-gold: #c39642;
        --text-main: #1d1d1f;
        --text-sub: #86868b;
        --bg-page: #ffffff;
    }

    .techflow-product-wrapper {
        font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--text-main);
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
    }

    /* Lien retour */
    .tf-back-link {
        display: inline-block;
        text-decoration: none;
        color: var(--text-sub);
        font-size: 0.9rem;
        margin-bottom: 40px;
        transition: color 0.2s;
    }
    .tf-back-link:hover { color: var(--text-main); }

    /* Conteneur principal */
    .tf-container {
        display: flex;
        flex-wrap: wrap;
        gap: 60px;
        align-items: center;
    }

    /* Image */
    .tf-image-col {
        flex: 1;
        min-width: 300px;
        text-align: center;
    }
    .tf-image-col img {
        width: 100%;
        max-width: 450px;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 15px 30px rgba(0,0,0,0.15));
    }

    /* Infos */
    .tf-info-col {
        flex: 1;
        min-width: 300px;
    }

    .tf-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 15px 0;
        line-height: 1.1;
    }

    .tf-desc {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--text-sub);
        margin-bottom: 25px;
    }

    .tf-stock {
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 30px;
    }
    .tf-stock span.ok { color: #008a00; }
    .tf-stock span.ko { color: var(--col-red); }

    /* Boutons */
    .tf-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .tf-btn-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .tf-btn {
        border: none;
        border-radius: 99px;
        padding: 14px 28px;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        text-decoration: none;
        cursor: pointer;
        text-align: center;
        transition: opacity 0.2s, transform 0.1s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .tf-btn:hover { opacity: 0.9; transform: scale(1.01); }

    .btn-buy { background-color: var(--col-teal); flex: 1; min-width: 160px; }
    .btn-add { background-color: var(--col-red); flex: 1; min-width: 160px;}
    .btn-service { background-color: var(--col-gold); width: 100%; font-size: 0.9rem;}

    /* --- MENU DÉROULANT CARACTÉRISTIQUES --- */
    .tf-features-wrapper {
        margin-top: 80px;
        padding-top: 20px;
        border-top: 1px solid #e5e5e5;
        width: 100%; /* Prend toute la largeur */
    }

    .tf-details {
        width: 100%;
        cursor: pointer;
    }

    /* Style du titre du menu (SUMMARY) */
    .tf-summary {
        font-size: 1.8rem;
        font-weight: 700;
        list-style: none; /* Cache la flèche par défaut */
        display: flex;
        justify-content: space-between; /* Pousse le + à droite */
        align-items: center;
        padding: 10px 0;
        transition: color 0.2s;
    }

    /* Masquer la flèche par défaut sur Chrome/Safari */
    .tf-summary::-webkit-details-marker { display: none; }

    .tf-summary:hover {
        color: var(--col-teal);
    }

    /* Le petit + à droite */
    .tf-summary::after {
        content: '+';
        font-size: 2rem;
        font-weight: 300;
        color: var(--text-sub);
        transition: transform 0.3s;
    }

    /* Quand c'est ouvert */
    .tf-details[open] .tf-summary::after {
        content: '-'; /* Ou rotate(45deg) si on garde le + */
        transform: rotate(0deg);
        color: var(--text-main);
    }

    /* Le contenu caché */
    .tf-details-content {
        padding-top: 10px;
        font-size: 1rem;
        line-height: 1.8;
        color: var(--text-sub);
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tf-container { flex-direction: column; gap: 30px; }
        .tf-title { font-size: 2rem; }
    }
</style>

<div class="techflow-product-wrapper">

    <a href="index.php?page=dashboard" class="tf-back-link">← Retour au catalogue</a>

    <div class="tf-container">

        <div class="tf-image-col">
            <img src="<?= htmlspecialchars($imageWebPath) ?>"
                 alt="<?= htmlspecialchars($p['nom_produit']) ?>">
        </div>

        <div class="tf-info-col">
            <h1 class="tf-title"><?= htmlspecialchars($p['nom_produit']); ?></h1>

            <div class="tf-desc">
                <?= nl2br(htmlspecialchars($p['descriptions'])); ?>
            </div>

            <div class="tf-stock">
                Stock :
                <?php if ($p['stock'] > 0): ?>
                    <span class="ok">Disponible</span>
                <?php else: ?>
                    <span class="ko">Rupture de stock</span>
                <?php endif; ?>
            </div>

            <div class="tf-actions">
                <div class="tf-btn-row">
                    <div class="tf-btn btn-buy">
                        <?= number_format($p['prix'], 2, ',', ' '); ?> € - Commander
                    </div>

                    <a href="index.php?page=ajouterPanier&id=<?= (int)$p['idProduits']; ?>" class="tf-btn btn-add">
                        Ajouter au panier
                    </a>
                </div>

                <a href="index.php?page=services" class="tf-btn btn-service">
                    Livraison rapide / Article garanti 1 an
                </a>
            </div>
        </div>
    </div>

    <div class="tf-features-wrapper">
        <details class="tf-details">
            <summary class="tf-summary">Caractéristiques</summary>
            <div class="tf-details-content">
                <p><?= nl2br(htmlspecialchars($p['caracteristiques_produit'])); ?></p>
            </div>
        </details>
    </div>

</div>
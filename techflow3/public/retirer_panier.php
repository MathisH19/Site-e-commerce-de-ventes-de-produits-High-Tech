<?php
// Action: retirer un article (−1) du panier
// Utilisation via le routeur: index.php?page=retirerPanier&id=XX

// Empêcher l'accès direct sans passage par le routeur
if (!isset($pdo)) { exit('Accès direct interdit'); }

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '?page=panier');
    exit();
}

$id = (int) $_GET['id'];

if (isset($_SESSION['panier'][$id])) {
    $_SESSION['panier'][$id]['quantite']--;

    if ($_SESSION['panier'][$id]['quantite'] <= 0) {
        unset($_SESSION['panier'][$id]);
    }
}

header('Location: ' . BASE_URL . '?page=panier');
exit();
?>

<?php
// public/ajouter_panier.php

// Pas de session_start() (fait dans index)
// Pas de require config (fait dans index)

if (!isset($pdo)) { exit("Accès direct interdit"); }

if (!isset($_GET['id'])) {
    // Redirection ABSOLUE
    header('Location: ' . BASE_URL . '/index.php?page=panier');
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM produits WHERE idProduits = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    header('Location: ' . BASE_URL . '/index.php?page=panier');
    exit();
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (isset($_SESSION['panier'][$id])) {
    $_SESSION['panier'][$id]['quantite']++;
} else {
    $_SESSION['panier'][$id] = [
        'id' => $produit['idProduits'],
        'nom' => $produit['nom_produit'],
        'prix' => $produit['prix'],
        'image' => $produit['image_produit'],
        'quantite' => 1
    ];
}

// Redirection finale ABSOLUE
header('Location: ' . BASE_URL . '/index.php?page=panier');
exit();
?>


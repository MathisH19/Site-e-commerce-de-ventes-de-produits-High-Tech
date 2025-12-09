<?php



if (!isset($pdo)) { exit('Accès direct interdit'); }

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '?page=panier');
    exit();
}

$id = (int)$_GET['id'];

if (isset($_SESSION['panier'][$id])) {
    unset($_SESSION['panier'][$id]);
}

header('Location: ' . BASE_URL . '?page=panier');
exit();
?>
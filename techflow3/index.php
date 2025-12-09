<?php
session_start(); // Important de démarrer la session ici, UNE SEULE FOIS pour tout le site
$pdo = require_once('./config/config.php'); // On récupère $pdo ici

// ... le reste du code du routeur ...

// 2. On définit la page
$page = $_GET['page'] ?? 'dashboard';

// 3. La liste des routes
$route = [
    'login' => __DIR__ . '/public/login.php',
    'register' => __DIR__ . '/public/register.php',
    'panier' => __DIR__ . '/public/panier.php',
    'services' => __DIR__ . '/public/services.php',
    'apropos' => __DIR__ . '/public/aPropos.php',
    'faq' => __DIR__ . '/public/faq.php',
    'conditions' => __DIR__ . '/public/conditions.php',
    'infosLegales' => __DIR__ . '/public/infosLegales.php',
    'dashboard' => __DIR__ . '/public/dashboard.php',
    '404' => __DIR__ . '/public/404.php',
    'ordiPortables' => __DIR__ . '/public/categories/ordi_portables.php',
    'pcFixes' => __DIR__ . '/public/categories/pc_fixes.php',
    'composants' => __DIR__ . '/public/categories/composants.php',
    'ecranGaming' => __DIR__ . '/public/categories/ecran_gaming.php',
    'peripheriques' => __DIR__ . '/public/categories/peripheriques.php',
    'telephones' => __DIR__ . '/public/categories/telephones.php',
    'tablettes' => __DIR__ . '/public/categories/tablettes.php',
    'montres' => __DIR__ . '/public/categories/montres.php',
    'audios' => __DIR__ . '/public/categories/audios.php',
    'consoles' => __DIR__ . '/public/categories/consoles.php',
    'ajouterPanier' => __DIR__ . '/public/ajouterPanier.php',
    'page_de_paiement' => __DIR__ .'/public/page_de_paiement.php',
    'commande_valide' => __DIR__ .'/public/page_commande_valide.php',
    'oublimdp'        => __DIR__ . '/public/oublimdp.php',
    'paiement'        => __DIR__ . '/public/page_de_paiement.php',
    'pageProduit'     => __DIR__ . '/public/page_produit.php',
    'cgv'             => __DIR__ . '/public/cgv.php',


    // ACTIONS (Pas de vue HTML, juste du traitement)
        'ajouterPanier'   => __DIR__ . '/public/ajouter_panier.php',
        'retirerPanier'   => __DIR__ . '/public/retirer_panier.php',
        'supprimerPanier' => __DIR__ . '/public/supprimer_panier.php',
];

// 4. Vérification si la page existe
$view = $route[$page] ?? __DIR__ . '/public/404.php';

// 5. Liste des pages qui NE DOIVENT PAS avoir de Header/Footer HTML (juste de la logique)
$pages_sans_affichage = ['ajouterPanier', 'retirerPanier', 'supprimerPanier'];

// 6. Affichage conditionnel
if (in_array($page, $pages_sans_affichage)) {
    // On inclut juste le fichier pour qu'il fasse son travail et sa redirection
    include_once $view;
} else {
    // C'est une page normale : on met le HTML, le Header, la Vue et le Footer
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Techflow</title>
        <link rel="stylesheet" href="assets/css/header.css">
        <link rel="stylesheet" href="assets/css/footer.css">
    </head>
    <body>
    <?php
    include_once('include/header.php');

    // On affiche le Hero seulement sur le dashboard pour ne pas polluer les autres pages ?
    // Ou partout si c'est votre choix. Ici je le laisse comme vous l'aviez :
    include_once('include/sectionHero_Categories.php');

    include_once $view;

    include_once('include/footer.php');
    ?>
    </body>
    </html>
    <?php
}
?>
<?php
// Récupérer une instance PDO de façon robuste (compatible appel direct et via routeur)
$pdo = require_once dirname(__DIR__) . '/config/config.php';
if (!($pdo instanceof PDO)) {
    if (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
        $pdo = $GLOBALS['pdo'];
    } else {
        http_response_code(500);
        die('Erreur de connexion à la base de données (PDO non initialisé).');
    }
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPwd = trim($_POST['confirmPassword']);
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $adresse_1 = htmlspecialchars(trim($_POST['adresse_1']));
    $adresse_2 = htmlspecialchars(trim($_POST['adresse_2']));
    $code_postal = htmlspecialchars(trim($_POST['nombre']));
    $ville = htmlspecialchars(trim($_POST['ville']));



    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } elseif ($password !== $confirmPwd) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $message = "Le mot de passe doit faire 8 caractères minimum, contenir une majuscule, un chiffre et un caractère spécial.";
    } elseif (!isset($_POST['cgv'])) {
        $message = "Vous devez accepter les conditions générales pour continuer.";
    } else {

        try {

            $check = $pdo->prepare("SELECT idUsers FROM users WHERE email = ?");
            $check->execute([$email]);

            if ($check->rowCount() > 0) {
                $message = "Cet email est déjà utilisé.";
            } else {


                $hashPwd = password_hash($password, PASSWORD_DEFAULT);


                $insertUser = $pdo->prepare("INSERT INTO users (email, password, prenom, nom)VALUES (?, ?, ?, ?)");

                $insertUser->execute([
                        $email,
                        $hashPwd,
                        $prenom,
                        $nom
                ]);


                $Users_idUsers = $pdo->lastInsertId();


                $insertAdresse = $pdo->prepare("INSERT INTO adresses (Users_idUsers, adresse_1, adresse_2, code_postal, ville)VALUES (?, ?, ?, ?, ?)");

                $insertAdresse->execute([
                        $Users_idUsers,
                        $adresse_1,
                        $adresse_2,
                        $code_postal,
                        $ville
                ]);

                echo "<p style='color:green'>Compte créé avec succès !</p>";
                exit;
            }

        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }


    if (isset($message)) {
        echo "<p style='color:red;'>$message</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/register.css">
    <title>formulaire</title>
</head>
<body>
<h2>Page d'inscription</h2>

<section id="formInscription">

    <form action="#" method="POST">
        <input type="text" name="nom" placeholder="Nom">
        <input type="text" name="prenom" placeholder="Prénom">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="adresse_1" placeholder="Adresse">
        <input type="text" name="adresse_2" placeholder="Complément Adresse">
        <input type="text" name="ville" placeholder="Ville">
        <input type="number" name="nombre" placeholder="Code postal">
        <input type="password" name="password" placeholder="mot de passe">
        <input type="password" name="confirmPassword" placeholder="Confirmation mot de passe">
        <label for="cgv">J’accepte les <a href="index.php?page=cgv" target="_blank">Conditions Générales</a></label>
        <input type="checkbox" id="cgv" name="cgv" required>
        <button type="submit">Envoyer</button>

    </form>
</section>
</body>
</html>

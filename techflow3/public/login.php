<?php
// ON SUPPRIME LA RE-CONNEXION ET LE SESSION_START
// (Car index.php l'a déjà fait et nous a passé la variable $pdo)

// On vérifie juste par sécurité que $pdo existe (optionnel si l'index est bien fait)
if (!isset($pdo)) {
    exit("Erreur : Accès direct interdit ou connexion manquante.");
}

$message = ""; // Initialisation de la variable pour éviter les erreurs "undefined variable"

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['identifiant']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } else {

        try {
            // Vérifier si l'utilisateur existe
            $stmt = $pdo->prepare("SELECT idUsers, password FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Vérifier le mot de passe
                if (password_verify($password, $user['password'])) {

                    // Connexion OK -> stocker en session
                    $_SESSION['id_user'] = $user['idUsers'];
                    $_SESSION['email'] = $email;

                    // Option "rester connecté"
                    if ($remember) {
                        setcookie("remember_user", $user['idUsers'], time() + (30 * 24 * 60 * 60), "/");
                    }

                    // Redirection propre vers le dashboard ou la page d'accueil
                    // Utiliser header() plutôt que echo
                    header('Location: index.php?page=dashboard');
                    exit;

                } else {
                    $message = "Mot de passe incorrect.";
                }
            } else {
                $message = "Aucun compte trouvé avec cet email.";
            }

        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }
}
?>

<?php if (!empty($message)) : ?>
    <p class="login-message" style="color: red; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<link rel="stylesheet" href="assets/css/login.css">

<form action="" method="post" class="login-form">
    <div class="form-group">
        <label for="identifiant">Identifiant/Mail</label>
        <input
                type="email"
                id="identifiant"
                name="identifiant"
                required
                placeholder="Entrez votre email"
                value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
        >
    </div>

    <div class="form-group">
        <label for="password">Mot de Passe</label>
        <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Entrez votre mot de passe"
        >
    </div>

    <div class="form-extra">
        <a href="index.php?page=oublimdp" class="forgot-link">Mot de passe oublié</a>
    </div>

    <div class="form-remember">
        <label>
            <input type="checkbox" name="remember">
            Rester connecté
        </label>
    </div>

    <div class="form-actions">
        <a href="?page=register" class="btn btn-secondary">Inscription</a>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </div>
</form>
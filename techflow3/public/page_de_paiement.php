<head>
<link rel="stylesheet" href="assets/css/css_page_de_paiement.css">
</head>
<section id="bloc">
    <div class="titreC">
        <h2>TechFlow</h2>
    </div>
    <div class="logoP">
        <p>Moyen de Paiement</p>
        <a href="https://www.paypal.com/signin?locale.x=fr_FR"><img src="../assets/img/footer/paiement_paypal.webp" alt="Paiement Paypal"></a>
        <a href="https://www.apple.com/fr/apple-pay/"><img src="../assets/img/footer/paiement_applepay.webp" alt="Apple Pay"></a>
        <img src="../assets/img/footer/paiement_visa.webp" alt="Visa">
        <img src="../assets/img/footer/paiement_americanExpress.webp" alt="Amercan Express">
        <img src="../assets/img/footer/téléchargement.webp" alt="MasterCard">
    </div>
        <h3 id="carte">Votre Carte</h3>
    <form class="infoC">
        <div class="ligne">
            <label for="name">Nom / Prénom</label>
            <input type="text" name="name" id="name" placeholder="Nom Prénom">required
        </div>
        <div class="ligne">
            <label for="card_number">Numéro de carte</label>
            <input type="number" name="card_number" id="card_number" placeholder="Numéro de carte">required
        </div>

        <div class="double">
            <div class="ligne">
                <label for="date">Date d'expiration</label>
                <input type="text" name="date" id="date" placeholder="01/27">required
            </div>

            <div class="ligne">
                <label for="crypto">Cryptogramme</label>
                <input type="number" name="crypto" id="crypto" placeholder="000">required
            </div>
        </div>
        <a class="btnc1" href="index.php?page=commande_valide">Valider le paiement</a>
        <a class="btnc2" href="index.php?page=panier">Annuler</a>
    </form>
</section>

<?php
/*
$host = "localhost";
$user = "root";
$pass = "";
$db = "paiement_fictif";

if ($conn->connect_error) {
    die("Erreur connexion : " . $conn->connect_error);

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$montant = $_POST['montant'];
$numeroCarte = $_POST['card_number'];
$expiration = $_POST['date'];
$cvc = $_POST['crypto'];

$statut = "PAYÉ";

$sql = "INSERT INTO transactions (nom, prenom, montant, card_number, date, crypto, statut) 
        VALUES ('$nom', '$prenom', '$montant', '$card_number', '$date', '$crypto', '$statut')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Paiement fictif réussi ! ✔</h2>";
    echo "<p>Merci $prenom $nom</p>";
    echo "<p>Montant : $montant €</p>";
} else {
    echo "Erreur : " . $conn->error;
}

$conn->close();
?>

*/
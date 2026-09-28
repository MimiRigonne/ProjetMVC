<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
<style>
form {
    max-width: 350px;
    margin: 60px auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
input, button {
    padding: 8px;
    font-size: 14px;
}
.erreur {
    color: #c0392b;
    font-weight: bold;
}
</style>
</head>
<body>

<h1>Connexion</h1>

<?php if (!empty($erreur)): ?>
    <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="POST" action="./?action=verifierConnexion">

    <label for="mail">Mail :</label>
    <input id="mail" type="email" name="mail" required>

    <label for="mdp">Mot de passe :</label>
    <input id="mdp" type="password" name="mdp" required>

    <button type="submit">Se connecter</button>
</form>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter une tâche</title>
<style>
form {
    max-width: 400px;
    margin: 30px auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
input, textarea, button {
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

<h1>Ajouter une tâche</h1>

<?php if (!empty($erreur)): ?>
    <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="POST" action="./?action=ajouter">

    <label for="descriptif_tache">Descriptif :</label>
    <textarea id="descriptif_tache" name="descriptif_tache" required></textarea>

    <label for="date_debut">Date de début :</label>
    <input id="date_debut" type="date" name="date_debut" required>

    <label for="idC">Catégorie :</label>
    <select id="idC" name="idC" required>
        <option value="">-- Choisir --</option>
        <?php foreach ($categories as $categorie): ?>
            <option value="<?= htmlspecialchars($categorie['idC']) ?>">
                <?= htmlspecialchars($categorie['nom_categorie']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Pas de champ "mail" : il est déduit de la session côté contrôleur -->

    <button type="submit">Ajouter la tâche</button>
</form>

<p><a href="./?action=liste">&larr; Retour à la liste</a></p>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des tâches</title>
<style>
.descrCard {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    margin: 15px 0;
    background-color: #f9f9f9;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
}

.descrCard a {
    font-size: 20px;
    font-weight: bold;
    text-decoration: none;
    color: #2c3e50;
}

.descrCard p {
    margin: 5px 0;
}

.photoCard img {
    max-width: 150px;
    margin-right: 8px;
    border-radius: 6px;
}
</style>
</head>
<body>

<h1>Liste des tâches</h1>

<p>
    Connecté en tant que <strong><?= htmlspecialchars($_SESSION['mail']) ?></strong>
    — <a href="./?action=deconnexion">Se déconnecter</a>
    — <a href="./?action=formulaire">Ajouter une tâche</a>
</p>

<?php if (empty($taches)): ?>
    <p>Aucune tâche trouvée.</p>
<?php endif; ?>

<?php foreach ($taches as $tache): ?>
    <div class="descrCard">

        <?php if (count($tache['photos']) > 0): ?>
            <div class="photoCard">
                <?php foreach ($tache['photos'] as $photo): ?>
                    <img src="photos/<?= htmlspecialchars($photo['chemin_Photo']) ?>"
                         alt="photo de la tâche" />
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a href="./?action=detail&idR=<?= (int) $tache['idT'] ?>">
            <?= htmlspecialchars($tache['descriptif_tache']) ?>
        </a>

        <p><strong>ID :</strong> <?= (int) $tache['idT'] ?></p>
        <p><strong>Date début :</strong> <?= htmlspecialchars($tache['date_debut'] ?: "Non renseignée") ?></p>
        <p><strong>Status :</strong> <?= htmlspecialchars($tache['status']) ?></p>
        <p><strong>Date fin :</strong> <?= htmlspecialchars($tache['date_fin'] ?: "Non renseignée") ?></p>
        <p><strong>Mail :</strong> <?= htmlspecialchars($tache['mail']) ?></p>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($tache['nom_categorie'] ?? $tache['idC']) ?></p>

    </div>
<?php endforeach; ?>

</body>
</html>

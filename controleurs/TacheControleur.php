<?php

require_once __DIR__ . '/../modeles/TacheModele.php';

/**
 * Contrôleur : récupère les données via le modèle,
 * les prépare pour la vue, puis inclut la vue.
 * Ne contient ni SQL, ni HTML.
 *
 * Version "fonctions" (sans classe) : chaque action est une fonction
 * globale, appelée directement depuis index.php.
 */

function afficherListe(): void
{
    exigerConnexion();
    $mail = $_SESSION['mail'];

    $taches = listerTachesParMail($mail);

    // On enrichit chaque tâche avec ses photos, pour que
    // la vue n'ait jamais besoin d'appeler le modèle elle-même.
    foreach ($taches as &$tache) {
        $tache['photos'] = getPhotosByIdT((int) $tache['idT']);
    }
    unset($tache);

    require __DIR__ . '/../vues/vueListeTaches.php';
}

/**
 * Affiche simplement le formulaire vide (requête GET).
 */
function afficherFormulaire(): void
{
    exigerConnexion();
    $categories = listerCategories();
    require __DIR__ . '/../vues/vueAjoutTache.php';
}

/**
 * Traite l'envoi du formulaire (requête POST).
 * Le mail est verrouillé ici, jamais lu depuis $_POST.
 */
function traiterAjout(): void
{
    exigerConnexion();
    $mail = $_SESSION['mail'];

    $descriptif = trim($_POST['descriptif_tache'] ?? '');
    $dateDebut  = trim($_POST['date_debut'] ?? '');
    $idC        = trim($_POST['idC'] ?? '');

    if ($descriptif === '' || $dateDebut === '' || $idC === '') {
        // Validation minimale : on renvoie vers le formulaire avec une erreur.
        $erreur     = "Merci de remplir tous les champs.";
        $categories = listerCategories();
        require __DIR__ . '/../vues/vueAjoutTache.php';
        return;
    }

    ajouterTache($mail, $descriptif, $dateDebut, $idC);

    // Pattern "Post/Redirect/Get" : après un POST réussi, on redirige
    // vers la liste plutôt que de l'afficher directement. Ça évite
    // qu'un F5 du navigateur ne recrée la tâche en double.
    header('Location: ./?action=liste');
    exit;
}

/**
 * Vérifie qu'un utilisateur est connecté ; sinon, redirige vers la connexion.
 * À appeler en tout premier dans toute action qui nécessite d'être connecté.
 */
function exigerConnexion(): void
{
    if (empty($_SESSION['mail'])) {
        header('Location: ./?action=connexion');
        exit;
    }
}

/**
 * Affiche le formulaire de connexion (requête GET).
 */
function afficherConnexion(): void
{
    require __DIR__ . '/../vues/vueConnexion.php';
}

/**
 * Traite l'envoi du formulaire de connexion (requête POST).
 */
function traiterConnexion(): void
{
    $mail = trim($_POST['mail'] ?? '');
    $mdp  = trim($_POST['mdp'] ?? '');

    if ($mail === '' || $mdp === '') {
        $erreur = "Merci de remplir tous les champs.";
        require __DIR__ . '/../vues/vueConnexion.php';
        return;
    }

    $utilisateur = verifierIdentifiants($mail, $mdp);

    if ($utilisateur === null) {
        $erreur = "Mail ou mot de passe incorrect.";
        require __DIR__ . '/../vues/vueConnexion.php';
        return;
    }

    // On stocke le mail en session : c'est LUI qui verrouillera
    // toutes les actions suivantes (liste, ajout...).
    $_SESSION['mail'] = $utilisateur['mail_Utilisateur'];

    header('Location: ./?action=liste');
    exit;
}

/**
 * Déconnecte l'utilisateur (vide la session).
 */
function deconnexion(): void
{
    $_SESSION = [];
    session_destroy();
    header('Location: ./?action=connexion');
    exit;
}

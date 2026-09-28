<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/controleurs/TacheControleur.php';

$action = $_GET['action'] ?? 'liste';

switch ($action) {
    case 'liste':
        afficherListe();
        break;

    case 'formulaire':
        afficherFormulaire();
        break;

    case 'ajouter':
        traiterAjout();
        break;

    case 'connexion':
        afficherConnexion();
        break;

    case 'verifierConnexion':
        traiterConnexion();
        break;

    case 'deconnexion':
        deconnexion();
        break;

    // case 'detail':
    //     afficherDetail((int) ($_GET['idR'] ?? 0));
    //     break;

    default:
        afficherListe();
        break;
}

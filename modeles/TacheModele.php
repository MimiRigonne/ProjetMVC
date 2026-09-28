<?php

/**
 * Modèle : responsable UNIQUEMENT de l'accès aux données.
 * Aucun HTML ici, aucune logique d'affichage.
 *
 * Version "fonctions" (sans classe) : chaque fonction ouvre sa propre
 * connexion via connexionPDO(), comme dans le fichier d'origine.
 */

function connexionPDO(): PDO
{
    $login   = "fleuryy";
    $mdp     = "21012005";
    $bd      = "fleuryy_bd6";
    $serveur = "http://mariadb.btssiobayonne.fr/phpmyadmin/";

    try {
        $conn = new PDO(
            "mysql:host=$serveur;dbname=$bd;charset=utf8",
            $login,
            $mdp
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Erreur de connexion PDO : " . $e->getMessage());
    }
}

/**
 * Récupère la liste unifiée des tâches en cours (table Taches)
 * et des tâches archivées (table Historique) pour un mail donné.
 *
 * IMPORTANT : avec le driver PDO MySQL natif, on ne peut PAS
 * réutiliser le même nom de paramètre (:mail) deux fois dans
 * une requête avec bindValue(). D'où :mail1 / :mail2 ci-dessous.
 *
 * On joint aussi la table Categorie pour afficher le libellé
 * plutôt que l'idC brut.
 */
function listerTachesParMail(string $mail): array
{
    $sql = "
        SELECT t.idT, t.descriptif_tache, t.date_debut, t.date_fin,
               t.status, t.idC, t.mail, c.nom_categorie
        FROM Taches t
        LEFT JOIN Categorie c ON c.idC = t.idC
        WHERE t.mail = :mail1

        UNION

        SELECT h.idT, h.descriptif_tache, h.date_debut, h.date_fin,
               h.status, h.idC, h.mail, c.nom_categorie
        FROM Historique h
        LEFT JOIN Categorie c ON c.idC = h.idC
        WHERE h.mail = :mail2

        ORDER BY idT
    ";

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare($sql);
        $req->bindValue(':mail1', $mail, PDO::PARAM_STR);
        $req->bindValue(':mail2', $mail, PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur !: " . $e->getMessage());
    }
}

/**
 * Insère une nouvelle tâche. Le mail vient TOUJOURS du contrôleur
 * (donc de la session), jamais d'un champ de formulaire.
 */
function ajouterTache(string $mail, string $descriptif, string $dateDebut, string $idC): bool
{
    $sql = "INSERT INTO Taches (descriptif_tache, date_debut, status, mail, idC)
            VALUES (:descriptif, :date_debut, 'En Cours', :mail, :idC)";

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare($sql);
        $req->bindValue(':descriptif', $descriptif, PDO::PARAM_STR);
        $req->bindValue(':date_debut', $dateDebut, PDO::PARAM_STR);
        $req->bindValue(':mail', $mail, PDO::PARAM_STR);
        $req->bindValue(':idC', $idC, PDO::PARAM_STR);
        return $req->execute();
    } catch (PDOException $e) {
        die("Erreur !: " . $e->getMessage());
    }
}

/**
 * Retourne toutes les catégories, pour remplir le <select> du formulaire.
 */
function listerCategories(): array
{
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("SELECT * FROM Categorie");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur !: " . $e->getMessage());
    }
}

function getPhotosByIdT(int $idT): array
{
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("SELECT * FROM photo WHERE idT = :idT");
        $req->bindValue(':idT', $idT, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur !: " . $e->getMessage());
    }
}

/**
 * Vérifie mail + mot de passe contre la table Utilisateur.
 * Retourne les infos de l'utilisateur si ok, null sinon.
 */
function verifierIdentifiants(string $mail, string $mdp): ?array
{
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare(
            "SELECT * FROM Utilisateur WHERE mail_Utilisateur = :mail AND mot_de_passe = :mdp"
        );
        $req->bindValue(':mail', $mail, PDO::PARAM_STR);
        $req->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $req->execute();
        $utilisateur = $req->fetch(PDO::FETCH_ASSOC);
        return $utilisateur ?: null;
    } catch (PDOException $e) {
        die("Erreur !: " . $e->getMessage());
    }
}

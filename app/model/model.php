<?php

function dbConnect()
{
    return require 'app/config/dbconnect.php';
}

function isLogged()
{
    return isset($_SESSION['user_id']);
}

function login($login, $password)
{
    $db = dbConnect();

    $query = $db->prepare('SELECT id AS id, nom AS name, prenom AS firstname, login AS login, password AS password, role FROM etudiant WHERE login = :login');
    $query->execute(['login' => $login]);
    $user = $query->fetch();

    if (!$user) {
        $query = $db->prepare('SELECT id AS id, nom AS name, prenom AS firstname, login AS login, password AS password, role FROM professeur WHERE login = :login');
        $query->execute(['login' => $login]);
        $user = $query->fetch();
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_firstname'] = $user['firstname'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }

    return false; 
}


function logout()
{
    session_unset();
    session_destroy();
}

function makeCurlRequest($url, $body)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Erreur cURL: ' . curl_error($ch);
    }

    curl_close($ch);

    return $response;
}

// Fonction pour récupérer les devoirs les plus récents d'un étudiant
function getLatestHomeworks($studentId)
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
            d.id_devoir, 
            d.titre, 
            d.date, 
            d.id_module, 
            m.titre AS module_titre
            FROM devoir d
            INNER JOIN devoir_etudiant de ON d.id_devoir = de.id_devoir
            INNER JOIN module m ON d.id_module = m.id_module
            WHERE de.id_etudiant = :studentId AND d.type = 'depot'
            ORDER BY d.date ASC
            LIMIT 3
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);

        $stmt->execute();

        $homeworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $homeworks;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les devoirs d'un étudiant
function getStudentsHomeworks($studentId)
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
            d.id_devoir, 
            d.titre, 
            d.date, 
            d.id_module, 
            m.titre AS module_titre
            FROM devoir d
            INNER JOIN devoir_etudiant de ON d.id_devoir = de.id_devoir
            INNER JOIN module m ON d.id_module = m.id_module
            WHERE de.id_etudiant = :studentId AND d.type = 'depot'
            ORDER BY d.date ASC
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);

        $stmt->execute();

        $homeworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $homeworks;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les devoirs par module 
function getCourseHomeworks($courseId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT
            d.id_devoir,
            d.titre,
            d.date,
            d.id_module,
            m.titre AS module_titre
        FROM devoir d
        INNER JOIN module m ON d.id_module = m.id_module
        WHERE d.id_module = :courseId AND d.type = 'depot'
        ORDER BY d.date ASC
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        $homeworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $homeworks;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les évaluations d'un étudiant
function getTests($studentId)
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
            d.id_devoir, 
            d.titre, 
            d.date, 
            d.id_module, 
            m.titre AS module_titre
            FROM devoir d
            INNER JOIN devoir_etudiant de ON d.id_devoir = de.id_devoir
            INNER JOIN module m ON d.id_module = m.id_module
            WHERE de.id_etudiant = :studentId AND d.type = 'evaluation'
            ORDER BY d.date ASC
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tests;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les absences d'un étudiant
function getAbsences($studentId)
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
                a.id_absence, 
                a.date AS date_absence, 
                a.debut AS heure_debut, 
                a.fin AS heure_fin, 
                a.duree, 
                a.motif
            FROM absence a
            WHERE a.id_etudiant = :studentId
            ORDER BY a.date DESC, a.debut DESC
            LIMIT 2
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $absences = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $absences;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les trois dernières notes d'un étudiant
function getLatestGrades($studentId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT 
            n.id_note, 
            n.date_note, 
            n.note, 
            n.note_max, 
            n.titre, 
            m.titre AS module_titre
        FROM note n
        JOIN module m ON n.id_module = m.id_module
        WHERE n.id_etudiant = :studentId
        ORDER BY n.date_note DESC
        LIMIT 3
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $grades;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer toutes les notes d'un étudiant
function getGrades($studentId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT 
            n.id_note, 
            n.date_note, 
            n.note, 
            n.note_max, 
            n.titre, 
            m.titre AS module_titre
        FROM note n
        JOIN module m ON n.id_module = m.id_module
        WHERE n.id_etudiant = :studentId
        ORDER BY n.date_note DESC
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $grades;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les infos des profs pour l'annuaire
function getTeachers()
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
            id, 
            nom, 
            prenom, 
            email, 
            discord
            FROM professeur
            ";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $teachers;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer tous les modules
function getCourses()
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
            id_module, 
            titre, 
            id_professeur
            FROM module
            ";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $modules;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les infos d'un module
function getCourse($id)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT 
            m.id_module, 
            m.titre, 
            m.id_professeur,
            p.nom AS prof_nom,
            p.prenom AS prof_prenom
        FROM module m
        JOIN professeur p ON m.id_professeur = p.id
        WHERE id_module = :id
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $module = $stmt->fetch(PDO::FETCH_ASSOC);
        return $module;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les fichiers d'un cours
function getCourseFiles($id)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT 
            id_cours, 
            nom_fichier
        FROM cours_module
        WHERE id_module = :id
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $files;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les infos d'un utilisateur
function getUser() {
    try {
        $db = dbConnect();
        $query = "
        SELECT * FROM etudiant WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

function updateProfilePicture($file, $role, $userId) {
    $uploadDir = 'public/uploads/profile-pictures/';
    $roleDir = ($role === 'teacher') ? 'teachers/' : 'students/';
    $uploadPath = $uploadDir . $roleDir . $userId;

    if (!is_dir($uploadDir . $roleDir)) {
        if (!mkdir($uploadDir . $roleDir, 0777, true)) {
            return "Erreur lors de la création du dossier de destination.";
        }
    }

    $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp', 'heic', 'heif'];
    $fileInfo = pathinfo($file['name']);
    $fileExtension = strtolower($fileInfo['extension']);

    if (!in_array($fileExtension, $allowedExtensions)) {
        return "Type de fichier non autorisé. Seules les images JPEG, PNG, JPG, WEBP, et HEIC sont acceptées.";
    }

    $existingFilePathToKeep = null;
    foreach ($allowedExtensions as $ext) {
        $existingFilePath = $uploadPath . '.' . $ext;
        if (file_exists($existingFilePath)) {
            $existingFilePathToKeep = $existingFilePath; 
            break;
        }
    }

    $uploadFilePath = $uploadPath . '.' . $fileExtension;
    if (isset($existingFilePathToKeep) && file_exists($existingFilePathToKeep)) {

        if ($existingFilePathToKeep === $uploadFilePath) {
            return true;  
        }
    }

    if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
        return "Erreur lors du téléchargement.";
    }

    if (isset($existingFilePathToKeep) && file_exists($existingFilePathToKeep)) {
        unlink($existingFilePathToKeep);
    }

    return true;
}




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

    $query = $db->prepare('
    SELECT id, nom AS name, prenom AS firstname, login, password, role 
    FROM utilisateurs 
    WHERE login = :login');
    $query->execute(['login' => $login]);
    $user = $query->fetch();

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
            INNER JOIN module m ON d.id_module = m.id_module
            INNER JOIN etudiant_module em ON m.id_module = em.id_module
            WHERE em.id_etudiant = :studentId AND d.type = 'depot'
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
            INNER JOIN module m ON d.id_module = m.id_module
            INNER JOIN etudiant_module em ON m.id_module = em.id_module
            WHERE em.id_etudiant = :studentId AND d.type = 'depot'
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
            INNER JOIN module m ON d.id_module = m.id_module
            INNER JOIN etudiant_module em ON m.id_module = em.id_module
            WHERE em.id_etudiant = :studentId AND d.type = 'evaluation'
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
            m.titre AS module_titre,
            d.titre AS titre, 
            d.note_max AS note_max
        FROM note n
        JOIN module m ON n.id_module = m.id_module
        LEFT JOIN devoir d ON n.id_devoir = d.id_devoir
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
            d.note_max, 
            d.titre AS titre, 
            m.titre AS module_titre
        FROM note n
        JOIN module m ON n.id_module = m.id_module
        JOIN devoir d ON n.id_devoir = d.id_devoir
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
            FROM utilisateurs
            WHERE role = 'professeur'
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
function getCourses($professorId = null)
{
    try {
        $db = dbConnect();
        $query = "
            SELECT 
                m.id_module, 
                m.titre
            FROM module AS m
            INNER JOIN professeur_module AS pm ON m.id_module = pm.id_module
        ";

        if ($professorId !== null) {
            $query .= " WHERE pm.id_professeur = :professorId";
        }

        $stmt = $db->prepare($query);

        if ($professorId !== null) {
            $stmt->bindParam(':professorId', $professorId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                m.id_professeur AS id_professeur, 
                u.nom AS prof_nom, 
                u.prenom AS prof_prenom
            FROM module m
            JOIN utilisateurs u ON m.id_professeur = u.id
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
function getUser()
{
    try {
        $db = dbConnect();
        $query = "
        SELECT * 
        FROM utilisateurs 
        WHERE id = :id
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les infos d'un utilisateur par son id
function getUserById($userId)
{
    $db = dbConnect();
    $query = $db->prepare("SELECT prenom, nom FROM utilisateurs WHERE id = :id");
    $query->execute(['id' => $userId]);
    return $query->fetch(PDO::FETCH_ASSOC);
}


// Fonction pour mettre à jour la photo de profil
function updateProfilePicture($file, $role, $userId)
{
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

// Fonction pour que l'étudiant upload ses fichiers
function uploadHomeworkFile($homeworkId, $userId, $file)
{
    if (empty($file['name'])) {
        return 'Aucun fichier sélectionné';
    }

    $uploadDir = "public/uploads/students-homework/{$homeworkId}/{$userId}/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return $file['name'];
    } else {
        return 'Erreur lors du téléchargement du fichier';
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function forgotPassword($email)
{
    $db = dbConnect();
    $query = $db->prepare('SELECT id FROM utilisateurs WHERE email = :email');
    $query->execute(['email' => $email]);
    $user = $query->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expiration = date('Y-m-d H:i:s', strtotime('+2 hour'));

        $query = $db->prepare('DELETE FROM reinitialisation_mot_de_passe WHERE email = :email');
        $query->execute(['email' => $email]);

        $query = $db->prepare('INSERT INTO reinitialisation_mot_de_passe (email, token, expiration) VALUES (:email, :token, :expiration)');
        $query->execute(['email' => $email, 'token' => $token, 'expiration' => $expiration]);

        $resetLink = 'https://jeremytahar.fr/ent/index.php?action=reset_password_form&token=' . $token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jeremytahar2@gmail.com';
            $mail->Password = 'jdaj sksj xktl wgia';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('jeremytahar2@gmail.com', 'ENT Gustave Eiffel');
            $mail->addAddress($email);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "Cliquez sur le lien pour réinitialiser votre mot de passe: $resetLink";

            $mail->send();
        } catch (Exception $e) {
            die('Erreur lors de l\'envoi de l\'e-mail : ' . $mail->ErrorInfo);
        }
    }
}


function updatePassword($token, $newPassword)
{
    $db = dbConnect();

    $query = $db->prepare('SELECT email FROM reinitialisation_mot_de_passe WHERE token = :token AND expiration > NOW()');
    $query->execute(['token' => $token]);
    $reset = $query->fetch();

    if ($reset) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = $db->prepare('UPDATE utilisateurs SET password = :password WHERE email = :email');
        $query->execute(['password' => $hashedPassword, 'email' => $reset['email']]);

        $query = $db->prepare('DELETE FROM reinitialisation_mot_de_passe WHERE token = :token');
        $query->execute(['token' => $token]);
    } else {
        throw new Exception('Invalid or expired token.');
    }
}

// Fonction pour upload un fichier en tant que prof
function uploadCourseFile($courseId, $fileName, $fileTmpPath)
{
    $uploadDir = "public/uploads/courses-files/{$courseId}/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
        try {
            $db = dbConnect();
            $query = "
            INSERT INTO cours_module (id_module, nom_fichier)
            VALUES (:courseId, :fileName)
            ";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            } else {
                unlink($targetFilePath);
                return false;
            }
        } catch (PDOException $e) {
            unlink($targetFilePath);
            die("Erreur : " . $e->getMessage());
        }
    }

    return false;
}

function deleteCourseFile($courseId, $fileName)
{
    $filePath = "public/uploads/courses-files/{$courseId}/{$fileName}";

    try {
        $db = dbConnect();
        $query = "
        DELETE FROM cours_module
        WHERE id_module = :courseId AND nom_fichier = :fileName
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return true;
        }
        return false;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les modules auxquels est rattaché un prof
function getCoursesByTeacher($profId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT 
            m.id_module, 
            m.titre
        FROM module AS m
        INNER JOIN professeur_module AS pm ON m.id_module = pm.id_module
        WHERE pm.id_professeur = :profId
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':profId', $profId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les devoirs d'un module
function getAssignmentsByCourse($moduleId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT
        d.id_devoir,
        d.titre,
        d.date
        FROM devoir AS d
        WHERE d.id_module = :moduleId
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Fonction pour récupérer tous les étudiants inscrits à un devoir
function getStudentsByAssignment($assignmentId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT
            u.id,
            u.nom,
            u.prenom
        FROM utilisateurs AS u
        INNER JOIN etudiant_module AS em ON u.id = em.id_etudiant
        INNER JOIN devoir AS d ON em.id_module = d.id_module
        WHERE d.id_devoir = :assignmentId
        AND u.role = 'etudiant'
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':assignmentId', $assignmentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}


function getGradeForAssignment($assignmentId, $studentId)
{
    try {
        $db = dbConnect();
        $query = "
        SELECT 
            n.note,
            d.note_max
        FROM note AS n
        LEFT JOIN devoir AS d ON n.id_devoir = d.id_devoir
        WHERE n.id_devoir = :assignmentId AND n.id_etudiant = :studentId
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':assignmentId', $assignmentId, PDO::PARAM_INT);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            $query_max = "SELECT note_max FROM devoir WHERE id_devoir = :assignmentId";
            $stmt_max = $db->prepare($query_max);
            $stmt_max->bindParam(':assignmentId', $assignmentId, PDO::PARAM_INT);
            $stmt_max->execute();
            $result_max = $stmt_max->fetch(PDO::FETCH_ASSOC);

            return ['note' => 'Non noté', 'note_max' => $result_max ? $result_max['note_max'] : null];
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

function updateGrade($assignmentId, $studentId, $grade)
{
    try {
        $db = dbConnect();

        $query = "
        SELECT date, id_module
        FROM devoir
        WHERE id_devoir = :assignmentId
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':assignmentId', $assignmentId, PDO::PARAM_INT);
        $stmt->execute();
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$assignment) {
            throw new Exception("Le devoir n'existe pas.");
        }

        $idModule = $assignment['id_module'];
        $dateNote = $assignment['date'];

        $query = "
        UPDATE note
        SET note = :grade, date_note = :dateNote
        WHERE id_devoir = :assignmentId
        AND id_etudiant = :studentId
        AND id_module = :idModule
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':assignmentId', $assignmentId, PDO::PARAM_INT);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':idModule', $idModule, PDO::PARAM_INT);
        $stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
        $stmt->bindParam(':dateNote', $dateNote, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $query = "
            INSERT INTO note (id_devoir, id_etudiant, id_module, note, date_note)
            VALUES (:assignmentId, :studentId, :idModule, :grade, :dateNote)
            ";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':assignmentId', $assignmentId, PDO::PARAM_INT);
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->bindParam(':idModule', $idModule, PDO::PARAM_INT);
            $stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
            $stmt->bindParam(':dateNote', $dateNote, PDO::PARAM_STR);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}


// Fonction pour que le prof puisse ajouter un devoir
function addHomework($title, $type, $courseId, $date, $noteMax)
{
    try {
        $db = dbConnect();
        $query = "
        INSERT INTO devoir (titre, type, id_module, date, note_max)
        VALUES (:title, :type, :courseId, :date, :noteMax)
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':noteMax', $noteMax, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
        return false;
    }
}


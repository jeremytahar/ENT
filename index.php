<?php
session_start();
require 'app/model/model.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'checkLogin':
        $login = $_POST['login'];
        $password = $_POST['password'];
        login($login, $password);
        $error = $_SESSION['login_error'] ?? null;
        if ($error === null) {
            header('Location: ?action=home');
            exit;
        } else {
            $_SESSION['login_error'] = $error;
            header('Location: ?action=login');
            exit;
        }
        break;
    case 'logout':
        logout();
        header('Location: ?action=home');
        break;
    // case 'calendar':
    //     $url = "https://ics-ade-api.mcb29.ovh";
    //     $body = [
    //         "url" => "https://edt.univ-eiffel.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=1328&projectId=26&calType=ical&nbWeeks=45",
    //         "schemas" => [
    //             [
    //                 "property" => "summary",
    //                 "fields" => [
    //                     [
    //                         "name" => "summary",
    //                         "pattern" => "string",
    //                         "type" => "string"
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ];
    //     $response = makeCurlRequest($url, $body);
    //     break;
    case 'updateProfilePicture':
        if (isset($_FILES['profile-picture'])) {

            $result = updateProfilePicture($_FILES['profile-picture'], $_SESSION['user_role'], $_SESSION['user_id']);

            if ($result === true) {
                header('Location: index.php?action=profile');
            } else {
                header('Location: index.php?action=profile&error=' . urlencode($result));
            }
        } else {
            header('Location: index.php?action=profile&error=' . urlencode("Aucun fichier sélectionné ou une erreur s'est produite."));
        }
        exit;
        break;
    case 'homework_upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_FILES['homework_file']) && $_FILES['homework_file']['error'] == 0) {
                $homeworkId = $_POST['homework_id'];
                $userId = $_SESSION['user_id'];
                $file = $_FILES['homework_file'];
                $courseId = $_POST['course_id'];

                $uploadResult = uploadHomeworkFile($homeworkId, $userId, $file);

                if ($uploadResult) {
                    header('Location: ?action=course&course_id=' . $courseId . '&success=' . urlencode("Devoir rendu avec succès."));
                } else {
                    echo "Erreur : " . $uploadResult;
                }
            } else {
                echo "Aucun fichier sélectionné ou une erreur s'est produite.";
            }
        }
        break;
        case 'delete_file':
            if (isset($_GET['file'], $_GET['homework_id'], $_GET['user_id'], $_GET['course_id'])) {
                $file = $_GET['file'];
                $homeworkId = $_GET['homework_id'];
                $userId = $_GET['user_id'];
                $courseId = $_GET['course_id'];
        
                $filePath = "public/uploads/students-homework/{$homeworkId}/{$userId}/{$file}";
        
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        header('Location: ?action=course&course_id=' . $courseId);
                        exit; // Assurez-vous que le script s'arrête après la redirection
                    } else {
                        echo "Erreur : Impossible de supprimer le fichier.";
                    }
                } else {
                    echo "Erreur : Le fichier n'existe pas.";
                    echo $filePath;
                }
            } else {
                echo "Erreur : Paramètres manquants.";
            }
            break;
        
    case 'reset_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            forgotPassword($email);
        }
        break;
    case 'update_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            updatePassword($token, $newPassword);
        }
        break;
    case 'upload_course_file':
        if ($_SESSION['user_role'] === 'professeur') {
            if (!empty($_FILES['course_file']['name'])) {
                $courseId = $_POST['course_id'];
                $fileName = basename($_FILES['course_file']['name']);
                $fileTmpPath = $_FILES['course_file']['tmp_name'];

                if (uploadCourseFile($courseId, $fileName, $fileTmpPath)) {
                    header("Location: ?action=course&course_id=$courseId&success=1");
                } else {
                    header("Location: ?action=course&course_id=$courseId&error=upload_failed");
                }
            } else {
                header("Location: ?action=course&course_id={$_POST['course_id']}&error=no_file");
            }
            exit;
        }
        break;
    case 'delete_course_file':
        if ($_SESSION['user_role'] === 'professeur') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $courseId = $_POST['course_id'];
                $fileName = $_POST['file_name'];

                if (deleteCourseFile($courseId, $fileName)) {
                    header("Location: ?action=course&course_id=$courseId");
                    exit();
                } else {
                    echo "Erreur lors de la suppression du fichier.";
                }
            }
        }
        break;
    case 'update_grade':
        if ($_SESSION['user_role'] === 'professeur' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignmentId = intval($_POST['assignment_id']);
            $studentId = intval($_POST['student_id']);
            $grade = intval($_POST['grade']);
            $date_note = $_POST['date_note'];

            updateGrade($assignmentId, $studentId, $grade, $date_note);

            // Rediriger vers la page des notes après mise à jour
            header('Location: index.php?action=grades');
            exit;
        }
        break;
        case 'addHomework':
            if ($_SESSION['user_role'] === 'professeur' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $courseId = $_POST['course_id'];
                $title = $_POST['title'];
                $type = $_POST['type'];
                $date = $_POST['date'];
                $noteMax = $_POST['max_grade'];

                $result = addHomework($title, $type, $courseId, $date, $noteMax);

                if ($result) {
                    header('Location: ?action=course&course_id=' . $courseId);
                } else {
                    echo "Erreur lors de l'ajout du devoir.";
                }
            }
            break;
}



if (!in_array($action, ['login', 'forgot_password', 'reset_password_form']) && !isLogged()) {
    header('Location: ?action=login');
    exit;
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENT</title>
    <link rel="stylesheet" href="public/styles/style.css">
    <link rel="shortcut icon" href="public/img/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php if (isLogged()): ?>
        <nav>
            <a href="?action=home" class="logo-link"><img src="public/img/logo.svg" alt="Aller à l'accueil"
                    class="logo"></a>
            <div class="nav-logo-burger">
                <div class="burger-btn">
                    <span></span>
                </div>
                <a href="?action=profile" class="profile-link"><img src="public/img/profile.svg" alt=""
                        class="profile-pic"></a>
                <a href="?action=logout" class="logout-link"><img src="public/img/logout.svg" alt=""></a>
            </div>
            <div class="nav-links">
                <a href="?action=home">Accueil</a>
                <a href="?action=dashboard">Tableau de bord</a>
                <a href="?action=calendar">Emploi du temps</a>
                <a href="?action=grades">Notes</a>
                <a href="?action=directory">Annuaire</a>
            </div>
        </nav>
    <?php endif; ?>

    <?php

    switch ($action) {
        case 'home':
            if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'etudiant') {
                $homeworks = getLatestHomeworks($_SESSION['user_id']);
                $absences = getAbsences($_SESSION['user_id']);
                $grades = getLatestGrades($_SESSION['user_id']);
            }
            require 'app/view/home.php';
            break;
        case 'dashboard':
            $homeworks = getStudentsHomeworks($_SESSION['user_id']);
            $tests = getTests($_SESSION['user_id']);
            if ($_SESSION['user_role'] === 'professeur') {
                $courses = getCourses($_SESSION['user_id']);
            } else {
                $courses = getCourses();
            }
            require 'app/view/dashboard.php';
            break;
        case 'calendar':
            require 'app/view/calendar.php';
            break;
        case 'grades':
            $grades = getGrades($_SESSION['user_id']);
            $teacherCourses = getCoursesByTeacher($_SESSION['user_id']);
            $assignmentsByCourse = [];
            $studentsByAssignment = [];

            foreach ($teacherCourses as $teacherCourse) {
                $assignmentsByCourse[$teacherCourse['id_module']] = getAssignmentsByCourse($teacherCourse['id_module']);

                foreach ($assignmentsByCourse[$teacherCourse['id_module']] as $assignment) {
                    $studentsByAssignment[$assignment['id_devoir']] = getStudentsByAssignment($assignment['id_devoir']);
                }
            }

            require 'app/view/grades.php';
            break;
        case 'directory':
            $teachers = getTeachers();
            require 'app/view/directory.php';
            break;
        case 'profile':
            $user = getUser($_SESSION['user_id']);
            $absences = getAbsences($_SESSION['user_id']);
            require 'app/view/profile.php';
            break;
        case 'course':
            $courseId = $_GET['course_id'];
            $course = getCourse($courseId);
            $courseFiles = getCourseFiles($courseId);
            $courseHomeworks = getCourseHomeworks($courseId);
            require 'app/view/course.php';
            break;
        case 'forgot_password':
            require 'app/view/forgot_password.php';
            break;
        case 'reset_password_form':
            require 'app/view/reset_password_form.php';
            break;
        default:
            if (isLogged()) {
                require 'app/view/home.php';
            } else {
                require 'app/view/login.php';
            }
            break;
    }

    ?>

    <footer>
        <a href="?action=home"><img src="public/img/logo.svg" alt=""></a>
    </footer>

    <script src="public/scripts/script.js"></script>
</body>

</html>
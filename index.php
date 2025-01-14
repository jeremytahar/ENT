<?php
session_start();
require 'app/model/model.php';

$action = $_GET['action'] ?? 'login';

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
    case 'calendar':
        $url = "https://ics-ade-api.mcb29.ovh";
        $body = [
            "url" => "https://edt.univ-eiffel.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=1328&projectId=26&calType=ical&nbWeeks=45",
            "schemas" => [
                [
                    "property" => "summary",
                    "fields" => [
                        [
                            "name" => "summary",
                            "pattern" => "string",
                            "type" => "string"
                        ]
                    ]
                ]
            ]
        ];
        $response = makeCurlRequest($url, $body);
        break;
        case 'updateProfilePicture':
            if (isset($_FILES['profile-picture'])) {
                
                $result = updateProfilePicture($_FILES['profile-picture'], $_SESSION['role'], $_SESSION['user_id']);
                
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
        
}


if ($action !== 'login' && !isLogged()) {
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

</head>

<body>
    <?php if (isLogged()): ?>
        <nav>
            <a href="?action=home" class="logo-link"><img src="public/img/logo.svg" alt="Aller à l'accueil"
                    class="logo"></a>
            <div class="nav-logo-burger">
                <!-- <button class="burger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button> -->
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
            $courses = getCourses();
            require 'app/view/dashboard.php';
            break;
        case 'calendar':
            require 'app/view/calendar.php';
            break;
        case 'grades':
            $grades = getGrades($_SESSION['user_id']);
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
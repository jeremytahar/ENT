<?php
session_start();
require 'app/model/model.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'checkLogin':
        $login = $_POST['login'];
        $password = $_POST['password'];
        login($login, $password);
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
<?php if (isLogged()):?>
    <nav>
        <a href="?action=home" class="logo-link"><img src="public/img/logo.svg" alt="Aller à l'accueil"
                class="logo"></a>
        <div class="nav-logo-burger">
            <button class="burger-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>
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


    if ($action !== 'login' && !isLogged()) {
        header('Location: ?action=login');
        exit;
    }

    // var_dump($_SESSION);
    // echo 'Bonjour ' .
    //     (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '') . ' ' .
    //     (isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] : '');


    switch ($action) {
        case 'home':
            require 'app/view/home.php';
            break;
        case 'dashboard':
            require 'app/view/dashboard.php';
            break;
        case 'calendar':
            require 'app/view/calendar.php';
            break;
        case 'grades':
            require 'app/view/grades.php';
            break;
        case 'directory':
            require 'app/view/directory.php';
            break;
        case 'profile':
            require 'app/view/profile.php';
            break;
        default:
            require 'app/view/login.php';
            break;
    }

    ?>

    <script src="public/scripts/script.js"></script>
</body>

</html>
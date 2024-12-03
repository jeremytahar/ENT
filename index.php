<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENT</title>
    <link rel="stylesheet" href="public/styles/style.css">
</head>

<body>
    <nav>
        <div class="nav-logo-burger">
            <a href="?action=home" class="logo-link"><img src="public/img/logo.svg" alt="Aller à l'accueil" class="logo"></a>
            <button class="burger-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a href="?action=profile" class="profile-link"><img src="public/img/profile.svg" alt="" class="profile-pic"></a>
        </div>
        <div class="nav-links">
            <a href="?action=home">Accueil</a>
            <a href="?action=dashboard">Tableau de bord</a>
            <a href="?action=calendar">Emploi du temps</a>
            <a href="?action=grades">Notes</a>
            <a href="?action=directory">Annuaire</a>
        </div>
    </nav>

    <?php 
    
    switch ($_GET['action'] ?? 'login') {
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
            require 'app/view/LogIn.php';
            break;
    }
    
    ?>

    <script src="public/scripts/script.js"></script>
</body>

</html>
<?php 

function dbConnect() {
    try {
        $db = new PDO('mysql:host=localhost;dbname=ent;charset=utf8', 'root', '');
        return $db;
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}

function isLogged() {
    return isset($_SESSION['user_id']);
}

function login($login, $password) {
    $db = dbConnect();

    // Vérifier dans la table `etudiant`
    $query = $db->prepare('SELECT id_etudiant AS id, nom AS name, prenom AS firstname, login AS login, password AS password, role FROM etudiant WHERE login = :login');
    $query->execute(['login' => $login]);
    $user = $query->fetch();

    // Si l'utilisateur n'est pas trouvé dans `etudiant`, vérifier dans `professeur`
    if (!$user) {
        $query = $db->prepare('SELECT id_professeur AS id, nom AS name, prenom AS firstname, login AS login, password AS password, role FROM professeur WHERE login = :login');
        $query->execute(['login' => $login]);
        $user = $query->fetch();
    }

    // Vérification du mot de passe et initialisation de la session si succès
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_firstname'] = $user['firstname'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }

    return false; // Échec de connexion
}


function logout() {
    session_unset();
    session_destroy();
}



?>
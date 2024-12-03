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

    $query = $db->prepare('SELECT * FROM students WHERE login = :login');
    $query->execute(['login' => $login]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_firstname'] = $user['firstname'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    } else {
        return false;
    }    
}

?>
<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\User;
use Service\Session;

$auth = new UserAuthentication();

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getUser();
} catch (NotLoggedException $e) {
    header('Location: /login.php');
    die();
}

Session::start();
$_POST['edit_user_id'] = $_SESSION['edit_user_id'];
unset($_SESSION["edit_ser_id"]);

if (isset($_POST['edit_submit'])) {

    $user = User::finById((int)$_POST['edit_user_id']);
    $user->save($_POST['edit_password']);

}

header('Location: /users.php');

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
unset($_SESSION["edit_user_id"]);

if (isset($_POST['edit_submit'])) {

    try {
        $user = User::finById((int)$_POST['edit_user_id']);

        if (isset($_POST['edit_admincheck'])) {
            $user->setAdmin(1);
        } else {
            $user->setAdmin(0);
        }

        $user->save($_POST['edit_password']);

    } catch (UserNotFoundException) {
        header('Location: /login.php');
        die();
    }

}

header('Location: /users.php');

<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\User;

$auth = new UserAuthentication();

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getUser();
} catch (NotLoggedException $e) {
    header('Location: /login.php');
    die();
}

if (isset($_POST['delete-user']) && ctype_digit($_POST['delete-user'])) {

    $userId = (int)$_POST['delete-user'];

    if ($userId == 1) {
        header('Location: /users.php');
        die();
        return;
    }

    User::delete((int)$_POST['delete-user']);

}

header('Location: /users.php');

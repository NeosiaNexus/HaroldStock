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

    User::delete((int)$_POST['delete-user']);

}

header('Location: /users.php');

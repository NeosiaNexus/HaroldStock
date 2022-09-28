<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\Exception\UserNotFoundException;

$auth = new UserAuthentication();

try {

    $auth->getUserFromAuth();

    header('Location: /index.php');

} catch (NotLoggedException|UserNotFoundException $e) {

    header('Location: /login.php');

}

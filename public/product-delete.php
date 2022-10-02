<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\Exception\UserNotFoundException;
use Entity\Product;

$auth = new UserAuthentication();

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getFreshUser();
} catch (NotLoggedException|UserNotFoundException $e) {
    header('Location: /login.php');
    die();
}

if (isset($_POST['product_delete']) && ctype_digit($_POST['product_delete'])) {

    Product::delete((int)$_POST['product_delete']);

}

header('Location: /products.php');

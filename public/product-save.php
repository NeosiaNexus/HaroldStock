<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\Exception\ProductNotFoundException;
use Entity\Exception\UserNotFoundException;
use Entity\Product;
use Service\Session;

$auth = new UserAuthentication();

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getFreshUser();
} catch (UserNotFoundException|NotLoggedException $e) {
    header('Location: /login.php');
    die();
}

Session::start();
$_POST['edit_product_id'] = $_SESSION['edit_product_id'];
unset($_SESSION["edit_product_id"]);

if (isset($_POST['edit_product_id'])) {

    try {

        $product = Product::findById((int)$_POST['edit_product_id']);

        $name = "Indéfini";
        $quantity = 0;
        $ptype = 0;
        $desc = "Aucune description.";
        $critical = 0;

        if (isset($_POST['edit_name']) && !empty($_POST['edit_name'])) {
            $name = $_POST['edit_name'];
        }

        if (isset($_POST['edit_quantity']) && ctype_digit($_POST['edit_quantity'])) {
            $quantity = (int)$_POST['edit_quantity'];
        }

        if (isset($_POST['edit_product_selector']) && ctype_digit($_POST['edit_product_selector'])) {
            $ptype = (int)$_POST['edit_product_selector'];
        }

        if (isset($_POST['edit_description']) && !empty($_POST['edit_description'])) {
            $desc = $_POST['edit_description'];
        }

        if (isset($_POST['edit_critical']) && ctype_digit($_POST['edit_critical'])) {
            $critical = (int)$_POST['edit_critical'];
        }

        $product->setName($name)
            ->setQuantity($quantity)
            ->setProductType($ptype)
            ->setDescription($desc)
            ->setCritical($critical);

        $product->save();

    } catch (ProductNotFoundException $e) {
        header('Location: /products.php');
    }

}

header('Location: /products.php');

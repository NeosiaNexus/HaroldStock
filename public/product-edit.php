<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Edition\ProductEdit;
use Entity\Exception\ProductNotFoundException;
use Entity\Exception\UserNotFoundException;
use Html\WebPage;
use Service\Exception\SessionException;

$auth = new UserAuthentication();

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getFreshUser();
} catch (UserNotFoundException|NotLoggedException $e) {
    header('Location: /login.php');
    die();
}

$page = new WebPage();

$page->setTitle("Harold - Edition produit");

$page->appendCssUrl('css/general.css');
$page->appendCssUrl('css/edit.css');

if (isset($_POST['product_edit']) && ctype_digit($_POST['product_edit'])) {

    try {

        $editProductId = (int)$_POST['product_edit'];

        // Left side
        $page->appendContent(<<<HTML
    <div class="left__side">
        <div class="logo">
            <a href="index.php"><img src="img/logo_png.png" alt=""></a>
            <p>
                <div class="white-spacer">
                </div>
            </p>
        </div>
        <div class="navigation">
            <ul>
                <li><a href="index.php"><i class='bx bxs-home-alt-2'></i> Accueil</a></li>
                <li><a href="products.php"><i class='bx bxs-baguette'></i> Produits</a></li>
                <li><a href="logs.php"><i class='bx bxs-edit'></i> Logs</a></li>
                <li><a href="users.php"><i class='bx bxs-user-account'></i> Utilisateurs</a></li>
                <li><a href="settings.php"><i class='bx bxs-cog'></i>Options</a></li>
            </ul>
        </div>
        <div class="bottom">
            <form action="index.php" method="post">
                <input type="submit" value="Se déconnecter" name="logout">
            </form>
        </div>
    </div>

HTML
        );

        // Right side
        $page->appendContent(<<<HTML
    <div class="right__side">
        <div class="header">
            <h2 data-aos="fade-down" data-aos-delay="300"><i class='bx bxs-user-account'></i> Produit</h2>
        </div>
        <div class="content">
            <div class="edit__box">
                <div class="edit__box__head">
                    <h2>Edition produit:</h2>
                    <p class="spacer">
                        <div class="black-spacer"></div>
                    </p>
                </div>
                
HTML
        );

        $page->appendContent(ProductEdit::editForm($editProductId, "product-save.php"));

        $page->appendContent(<<<HTML
        </div>
        </div>
HTML
        );

        $page->appendContent(<<<HTML
        <div class="footer" >
            <p > Site développé avec ❤️ par Mathéo OLSEN </p >
        </div >
        </div>

HTML
        );

        echo $page->toHTML();

    } catch (ProductNotFoundException|UserNotFoundException $e) {
        header('Location: /products.php');
    } catch (SessionException $e) {
        header('Location: /login.php');
    }
}

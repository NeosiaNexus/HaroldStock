<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\Exception\UserNotFoundException;
use Entity\Product;
use Html\WebPage;
use Products\ProductAdd;

$auth = new UserAuthentication();

$user = null;

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getFreshUser();
} catch (NotLoggedException $e) {
    header('Location: /login.php');
    die();
} catch (UserNotFoundException $e) {
    header('Location: /login.php');
}

if (isset($_POST['add_product_submit'])) {

    $desc = "Aucune description.";
    $critical = 1;

    if (isset($_POST['add_product_description']) && !empty($_POST['add_product_description'])) {

        $desc = $_POST['add_product_description'];

    }

    if (isset($_POST['add_product_critical']) && ctype_digit($_POST['add_product_critical'])) {

        $critical = (int)$_POST['add_product_critical'];

    }

    Product::add($_POST['add_product_name'],
        (int)$_POST['add_product_quantity'],
        (int)$_POST['add_product_selector'],
        $desc,
        $critical);

}

$page = new WebPage();

$page->setTitle("Harold - Produits");

$page->appendCssUrl('css/general.css');
$page->appendCssUrl('css/product.css');

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

HTML
);

if ($user->isAdmin()) {
    $page->appendContent(<<<HTML
                <li><a href="users.php"><i class='bx bxs-user-account'></i> Utilisateurs</a></li>
                <li><a href="settings.php"><i class='bx bxs-cog'></i>Options</a></li>

HTML
    );
}

$page->appendContent(<<<HTML
            </ul>
        </div>
        <div class="bottom">
            <form action="index.php" method="post">
                <input type="submit" value="Déconnexion" name="logout" class="logout_button">
            </form>
        </div>
    </div>

HTML
);

// Right side
$page->appendContent(<<<HTML
        <div class="right__side">
            <div class="header">
                <h2 data-aos="fade-down" data-aos-delay="300"><i class='bx bx-barcode'></i> Produits</h2>
            </div>
            <div class="content">

HTML
);

// PRODUCT BOX
$page->appendContent(<<<HTML
                <div class="product__box">

HTML
);

// LEFT
$page->appendContent(<<<HTML
                    <div class="left">

HTML
);

// PRODUCT HEAD
$page->appendContent(<<<HTML
                        <div class="product__addform">

HTML
);

$page->appendContent(<<<HTML
                            <div class="product__add__head">
                                <h2 data-aos="fade-left" data-aos-duration="1000" data-aos-delay="700">Ajouter un produit</h2>
                                <p>
                                    <div class="black-spacer" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="800"></div>
                                </p>
                            </div>

HTML
);

$page->appendContent(ProductAdd::addFrom($_SERVER['PHP_SELF']));

// END PRODUCT HEAD
$page->appendContent(<<<HTML
                        </div>

HTML
);

$page->appendContent(<<<HTML
                        <div class="categories__box">
                            <a href="#">
                                <div class="categories__content" data-aos="zoom-out" data-aos-duration="800" data-aos-delay="500">
                                    <i class='bx bxs-category icon' data-aos="flip-left" data-aos-duration="400" data-aos-delay="1000"></i>
                                    <p class="desc">Voir, éditer & supprimer les catégories.</p>
                                </div>
                            </a>
                        </div>

HTML
);

$products = Product::findAll();

// END LEFT
if (count($products) == 0) {
    $page->appendContent(<<<HTML
                    </div>
                <div class="right" data-aos="zoom-out" data-aos-duration="1000" data-aos-delay="1000">

HTML
    );
} else {
    $page->appendContent(<<<HTML
                    </div>
                <div class="right overflow" data-aos="zoom-out" data-aos-duration="1000" data-aos-delay="1000">

HTML
    );
}

if (count($products) == 0) {

    $page->appendContent(<<<HTML
        <h3>Il n'y a aucun produit enregistré.</h3>
HTML
    );

} else {

    foreach ($products as $product) {

        $style = $product->getQuantity() <= $product->getCritical() ? 'style="border: 5px solid red;"' : 'style="background-color: var(--white)"';

        $page->appendContent(<<<HTML
                        <div class="product__item" $style>
                            <img src="img/no-image.png">
                            <p class="product__item__title">{$product->getName()}</p>
                            <p class="product__item__quantity">Quantité: {$product->getQuantity()}</p>
                            <p class="product__item__quantity">Quantité critique: {$product->getCritical()}</p>
                            <div class="product__buttons">
                                <form action="product-edit.php" method="post">
                                    <button type="submit" name="product_edit" value="{$product->getId()}">
                                        Editer
                                    </button>
                                </form>
                                <form action="product-delete.php" method="post">
                                    <button type="submit" name="product_delete" value="{$product->getId()}">
                                        Supprimer
                                    </button>
                                </form>
                                <form action="product-delete.php" method="post">
                                    <button type="submit" name="product_delete" value="{$product->getId()}">
                                        +/-
                                    </button>
                                </form>
                            </div>
                        </div>

HTML
        );
    }

}

// END PRODUCT BOX
$page->appendContent(<<<HTML
                    </div>
                </div>

HTML
);

$page->appendContent(<<<HTML
            </div>
            <div class="footer">
                <p>Site développé avec ❤️ par Mathéo OLSEN</p>
            </div>
        </div>
HTML
);

echo $page->toHTML();


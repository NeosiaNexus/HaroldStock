<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Html\WebPage;
use Service\Exception\SessionException;

$auth = new UserAuthentication();

try {
    if ($auth->logoutIfRequested()) {
        header('Location: /login.php');
        die();
    }
} catch (SessionException $e) {
}

$user = null;

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getUser();
} catch (NotLoggedException $e) {
    header('Location: /login.php');
    die();
}

$page = new WebPage();

$page->setTitle("Harold - Accueil");

$page->appendCssUrl('css/general.css');
$page->appendCssUrl('css/index.css');

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
            <h2 data-aos="fade-down" data-aos-delay="300"><i class='bx bx-barcode'></i> Gestion des stocks</h2>
        </div>
        <div class="content">
            <div class="remarque" data-aos="zoom-in" data-aos-duration="1500">
                <h2><i class='bx bxs-error bx-tada'></i> Remarque(s) :</h2>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Fugit, sint quasi eum eaque explicabo voluptas dolorum fuga sit tempora provident, perspiciatis eveniet doloremque nemo recusandae reiciendis eius nesciunt sequi consequatur.</p>
            </div>
            <div class="produits">
                <a href="#">
                    <div class="ajouter" data-aos="zoom-out" data-aos-duration="1000" data-aos-delay="500">
                        <i class='bx bxs-edit-alt icon'></i>
                        <p class="desc">Cliquez ici pour ajouter, éditer ou supprimer un produit.</p>
                    </div>
                </a>
                <a href="#">
                    <div class="consulter" data-aos="zoom-out" data-aos-duration="1000" data-aos-delay="1000">
                        <i class='bx bx-list-plus icon'></i>
                        <p class="desc">Cliquez ici pour voir le stock et le mettre à jour.</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="footer">
            <p>Site développé avec ❤️ par Mathéo OLSEN</p>
        </div>
    </div>
HTML
);

echo $page->toHTML();


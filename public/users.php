<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Entity\Exception\UserNotFoundException;
use Entity\User;
use Html\WebPage;

$auth = new UserAuthentication();

$user = null;

// Tentative de récupération de l'utilisateur
try {

    $user = $auth->getUser();

} catch (UserNotFoundException|NotLoggedException $e) {

    header('Location: /login.php');

    die();

}

if (!$user->isAdmin()) {

    header('Location: /index.php');

    die();

}

if (isset($_POST['add_user_submit'])) {

    $admin = 0;

    if (isset($_POST['add_useradmin'])) {
        $admin = 1;
    }

    User::add($_POST['add_user_login'], $_POST['add_user_password'], $admin);

}

$page = new WebPage();

$page->setTitle("Harold - Utilisateurs");

$page->appendCssUrl('css/general.css');
$page->appendCssUrl('css/users.css');

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
            <h2 data-aos="fade-down" data-aos-delay="300"><i class='bx bxs-user-account'></i> Utilisateurs</h2>
        </div>
        <div class="content">
            <div class="user__box">
                <div class="user__add__box" data-aos="zoom-out" data-aos-duration="1000" data-aos-delay="500">
                    <div class="user__add__head">
                        <h2 data-aos="fade-left" data-aos-duration="1000" data-aos-delay="700">Ajouter un utilisateur</h2>
                        <p>
                            <div class="black-spacer" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="800">
                            </div>
                        </p>
                    </div>
                    <form action="{$_SERVER['PHP_SELF']}" method="post">
                        <input type="text" name="add_user_login" placeholder="Identifiant" required  data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1000">
                        <input type="password" name="add_user_password" placeholder="Mot de passe" required  data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1100">
                        <div data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1200">
                            <input type="checkbox" name="add_useradmin">
                            <label style="color: red">Administrateur</label>
                        </div>
                        <input type="submit" name="add_user_submit" value="Ajouter"  data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1300">
                    </form>
                </div>
            <div class="user__list__box"  data-aos="zoom-out" data-aos-duration="1000" data-aos-delay="1000">
                
HTML
);

$users = User::findAll();

if (count($users) > 0) {

    $page->appendContent(<<<HTML
            <table>
                <tr><th><i class='bx bxs-lock-alt'></i> Login</th><th><i class='bx bx-toggle-right'></i> Action</th></tr>
HTML
    );

    foreach ($users as $iterUser) {

        $you = "";
        $style = "";

        if ($iterUser->isAdmin()) {
            $style = "style=color:red;";
        }

        if ($iterUser->getLogin() == $user->getLogin()) {
            $you .= " (vous)";
        }

        $page->appendContent(<<<HTML
                    <tr class="user__line">
                        <td $style>{$iterUser->getLogin()} $you</td>
                        <td class="edit__part">
                            <form action="user-edit.php" method="post">
                                <button type="submit" name="edit-user" value="{$iterUser->getId()}">
                                    <i class='bx bxs-edit-alt'>
                                    </i>
                                    Editer
                                </button>
                            </form>
HTML
        );

        if ($iterUser->getId() != $user->getId()) {
            $page->appendContent(<<<HTML
                            <form action="user-delete.php" method="post">
                                <button class="delete" type="submit" name="delete-user" value="{$iterUser->getId()}">
                                    <i class='bx bxs-user-minus'>
                                    </i>
                                    Supprimer
                                </button>
                            </form>
HTML
            );
        } else {
            $page->appendContent(<<<HTML
                        </td>
                    </tr>
    HTML
            );
        }

    }

    $page->appendContent(<<<HTML
            </table>
HTML
    );
} else {


}

$page->appendContent(<<<HTML
            </div>
        </div>
    </div>

HTML
);

$page->appendContent(<<<HTML
        <div class="footer" >
            <p > Site développé avec ❤️ par Mathéo OLSEN </p >
        </div >
    </div >
HTML
);

echo $page->toHTML();


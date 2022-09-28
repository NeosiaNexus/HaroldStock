<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedException;
use Authentication\UserAuthentication;
use Html\WebPage;

$auth = new UserAuthentication();

// Tentative de récupération de l'utilisateur
try {
    $user = $auth->getUser();
    header('Location: /index.php');
} catch (NotLoggedException $e) {

    $page = new WebPage();

    $page->setTitle("Harold - Login");

    $page->appendCssUrl("css/login.css");

// Ouverture form__box
    $page->appendContent(
        <<<HTML
        <div class="form__box">

HTML
    );

    $page->appendContent(<<<HTML
            <div class="img__box">

HTML
    );

    $page->appendContent(
        <<<HTML
                <img src="img/logo_png.png" alt="Logo de Harold le Restaurant">

HTML
    );

    $page->appendContent(<<<HTML
            </div>
            
HTML
    );

// Ouverture input__box
    $page->appendContent(<<<HTML
<div class="input__box">

HTML
    );

// Ajout du formulaire d'authentification
    $page->appendContent($auth->loginForm("auth.php"));

// Fermeture input__box
    $page->appendContent(
        <<<HTML
            </div>

HTML
    );

// Fermeture form__box
    $page->appendContent(
        <<<HTML
        </div>
HTML
    );

    echo $page->toHTML();


}
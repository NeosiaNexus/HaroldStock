<?php

namespace Edition;

use Entity\Exception\UserNotFoundException;
use Entity\User;
use Service\Exception\SessionException;
use Service\Session;

class UserEdit
{
    /**
     * @throws UserNotFoundException
     * @throws SessionException
     */
    public static function editForm(int $userId, string $action): string
    {

        $user = User::finById($userId);

        Session::start();
        $_SESSION['edit_user_id'] = $user->getId();

        return <<<HTML
        <form action="$action" method="post" class="edit__form">
            <input type="text" name="edit_login" value="{$user->getLogin()}" readonly>
            <input type="password" name="edit_password" placeholder="Nouveau mot de passe" required>
            <input type="submit" name="edit_submit" value="Enregistrer">
        </form>

HTML;
    }
}
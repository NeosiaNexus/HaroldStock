<?php

namespace Authentication;

use Authentication\Exception\NotLoggedException;
use Entity\Exception\UserNotFoundException;
use Entity\User;
use Service\Exception\SessionException;
use Service\Session;

class UserAuthentication
{

    private const LOGIN_INPUT_NAME = "login";
    private const PASSWORD_INPUT_NAME = "password";
    private const LOGIN_INPUT_TEXT = "Identifiant";
    private const PASSWORD_INPUT_TEXT = "Mot de passe";
    private const LOGOUT_NAME = "logout";

    private const SESSION_KEY = '__HaroldAuth__';
    private const USER_KEY = 'user';

    private ?User $user = null;


    public function __construct()
    {
        try {
            $this->setUser($this->getUserFromSession());
        } catch (NotLoggedException|SessionException) {
        }

    }

    /**
     * @throws SessionException|NotLoggedException
     */
    protected function getUserFromSession(): User
    {
        Session::start();

        if (isset($_SESSION[self::SESSION_KEY][self::USER_KEY]) && $_SESSION[self::SESSION_KEY][self::USER_KEY] instanceof User) {

            return $_SESSION[self::SESSION_KEY][self::USER_KEY];

        } else {

            throw new NotLoggedException("Aucune session trouvée");

        }
    }

    public function loginForm(string $action, string $submittext = "Connexion"): string
    {

        $login_name = self::LOGIN_INPUT_NAME;
        $password_name = self::PASSWORD_INPUT_NAME;
        $login_text = self::LOGIN_INPUT_TEXT;
        $password_text = self::PASSWORD_INPUT_TEXT;

        return <<<HTML
                <form action="$action" method="post">
                    <input type="text" id="$login_name" name="$login_name" placeholder="$login_text" required>
                    <input type="password" id="$password_name" name="$password_name" placeholder="$password_text" required>
                    <input type="submit" name="submit" value="$submittext">
                </form>

HTML;


    }

    /**
     * @throws SessionException
     */
    public function logoutIfRequested(): bool
    {
        if (isset($_POST[self::LOGOUT_NAME])) {
            Session::start();
            unset($_SESSION[self::SESSION_KEY][self::USER_KEY]);
            $this->user = null;
            return true;
        }
        return false;
    }

    /**
     * @throws NotLoggedException
     */
    public function getUser(): User
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            throw new NotLoggedException("Aucun utilsateur défini");
        }
    }

    public function setUser(?User $user): void
    {
        try {
            Session::start();
            $this->user = $user;
            $_SESSION[self::SESSION_KEY] = [self::USER_KEY => $user];
        } catch (SessionException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @throws UserNotFoundException
     * @throws NotLoggedException
     */
    public function getUserFromAuth(): User
    {
        if (isset($_SESSION[self::SESSION_KEY][self::USER_KEY]) && $_SESSION[self::SESSION_KEY][self::USER_KEY] instanceof User) {

            return $_SESSION[self::SESSION_KEY][self::USER_KEY];

        } else if (isset($_POST[self::LOGIN_INPUT_NAME]) && isset($_POST[self::PASSWORD_INPUT_NAME])) {

            $this->setUser(User::findByCredentials($_POST[self::LOGIN_INPUT_NAME], $_POST[self::PASSWORD_INPUT_NAME]));

            return User::findByCredentials($_POST[self::LOGIN_INPUT_NAME], $_POST[self::PASSWORD_INPUT_NAME]);

        }

        throw new NotLoggedException("Erreur de connexion");
    }
}
<?php

namespace Authentication;

class UserAuthentication
{

    private const LOGIN_INPUT_NAME = "login";
    private const PASSWORD_INPUT_NAME = "password";
    private const LOGIN_INPUT_TEXT = "Identifiant";
    private const PASSWORD_INPUT_TEXT = "Mot de passe";

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
                    <input type="submit" value="$submittext">
                </form>

HTML;


    }

}
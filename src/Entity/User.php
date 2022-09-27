<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\UserNotFoundException;
use Html\StringEscaper;
use PDO;

class User
{
    private int $id;
    private string $login;


    /**
     * Permet de récupérer un utilisateur grâce à ses données d'authentification
     *
     * @throws UserNotFoundException
     */
    public static function findByCredentials(string $login, string $password): self
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
            SELECT id,login
            FROM users
            WHERE login = :login AND password = :password
SQL
        );

        $stmt->execute(["login" => StringEscaper::escapeString($login), "password" => hash('sha512', StringEscaper::escapeString($password))]);

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        $retour = $stmt->fetch();

        if (!$retour) {

            throw new UserNotFoundException("Aucun utilisateur correspondant aux données entrées n'a été trouvé");

        }

        return $retour;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }


}
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
     * @return User[]
     */
    public static function findAll(): array
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT id, login
                FROM users
SQL
        );

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        return $stmt->fetchAll();
    }

    public static function delete(int $id)
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                DELETE FROM users
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => $id]);

    }

    /**
     * @throws UserNotFoundException
     */
    public static function finById(int $id): self
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
            SELECT id,login
            FROM users
            WHERE id = :id
SQL
        );

        $stmt->execute(["id" => StringEscaper::escapeString($id)]);

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        $retour = $stmt->fetch();

        if (!$retour) {

            throw new UserNotFoundException("Aucun utilisateur correspondant aux données entrées n'a été trouvé");

        }

        return $retour;
    }


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

    public static function add(string $login, string $password)
    {

        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                INSERT INTO users (login,password)
                VALUES (:login, :password)
SQL
        );

        $stmt->execute(["login" => StringEscaper::escapeString($login), "password" => hash('sha512', StringEscaper::escapeString($password))]);

    }

    public function save(string $password): void
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                UPDATE users
                SET password = :password
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => StringEscaper::escapeString($this->getId()), "password" => hash('sha512', StringEscaper::escapeString($password))]);

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
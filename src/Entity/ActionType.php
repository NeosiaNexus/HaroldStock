<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\ActionTypeNotFoundException;
use PDO;

class ActionType
{

    private int $id;
    private string $name;

    /**
     * Permet de récupérer tous les types d'action
     *
     * @return ActionType[]
     */
    public static function findAll(): array
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT *
                FROM action_type
SQL
        );

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        return $stmt->fetchAll();
    }

    /**
     * Permet de récupérer un type d'action grâce à son id
     *
     * @throws ActionTypeNotFoundException
     */
    public static function findById(int $id): self
    {

        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT *
                FROM action_type
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        $response = $stmt->fetch();

        if (!$response) {

            throw new ActionTypeNotFoundException("L'id du type d'action renseigné n'existe pas.");

        }

        return $response;

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
    public function getName(): string
    {
        return $this->name;
    }


}
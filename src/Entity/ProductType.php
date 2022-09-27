<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\ProductTypeNotFoundException;
use PDO;

class ProductType
{

    private string $id;
    private string $name;

    /**
     * Permet de récupérer tous les types de produit
     *
     * @return ProductType[]
     */
    public static function findAll(): array
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT *
                FROM product_type
SQL
        );

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        return $stmt->fetchAll();

    }

    /**
     * Permet de récupérer un type de produit grâce à son id
     *
     * @throws ProductTypeNotFoundException
     */
    public static function findById(int $id): self
    {

        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT *
                FROM product_type
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        $response = $stmt->fetch();

        if (!$response) {

            throw new ProductTypeNotFoundException("L'id du type de produit renseigné n'existe pas.");

        }

        return $response;

    }

    /**
     * @return string
     */
    public function getId(): string
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
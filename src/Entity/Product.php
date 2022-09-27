<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\ProductNotFoundException;
use PDO;

class Product
{

    private int $id;
    private string $name;
    private int $quantity;
    private int $product_type;
    private string $description;


    /**
     * Permet de récupérer tous les produits
     *
     * @return Product[]
     */
    public static function findAll(): array
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT *
                FROM products
SQL
        );

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        return $stmt->fetchAll();
    }

    /**
     * Permet de récupérer un produit grâce à son id
     *
     * @throws ProductNotFoundException
     */
    public static function findById(int $id): self
    {

        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                SELECT *
                FROM products
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        $response = $stmt->fetch();

        if (!$response) {

            throw new ProductNotFoundException("L'id du type d'action renseigné n'existe pas.");

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

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getProductType(): int
    {
        return $this->product_type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


}
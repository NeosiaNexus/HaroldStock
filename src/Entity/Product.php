<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\ProductNotFoundException;
use Html\StringEscaper;
use PDO;

class Product
{

    private int $id;
    private string $name;
    private int $quantity;
    private int $product_type;
    private string $description;
    private int $critical;


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
                ORDER BY critical DESC
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

    public static function add(string $name, int $quantity, int $product_type, string $description, int $critical): void
    {

        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                INSERT INTO products (name,quantity,product_type,description,critical)
                VALUES (:name, :quantity, :product_type,:description,:critical)
SQL
        );

        $stmt->execute(
            ["name" => StringEscaper::escapeString($name),
                "quantity" => (int)StringEscaper::escapeString($quantity),
                "product_type" => (int)StringEscaper::escapeString($product_type),
                "description" => StringEscaper::escapeString($description),
                "critical" => (int)StringEscaper::escapeString($critical)]);

    }

    public static function delete(int $id)
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                DELETE FROM products
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => $id]);

    }

    public function save(): void
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
                UPDATE products
                SET name = :name, quantity = :quantity, product_type = :product_type, description = :description, critical = :critical
                WHERE id = :id
SQL
        );

        $stmt->execute(["id" => StringEscaper::escapeString($this->getId()),
            "name" => StringEscaper::escapeString($this->getName()),
            "quantity" => $this->getQuantity(),
            "product_type" => $this->getProductType(),
            "description" => StringEscaper::escapeString($this->getDescription()),
            "critical" => $this->getCritical()]);

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
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductType(): int
    {
        return $this->product_type;
    }

    /**
     * @param int $product_type
     * @return Product
     */
    public function setProductType(int $product_type): Product
    {
        $this->product_type = $product_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getCritical(): int
    {
        return $this->critical;
    }

    /**
     * @param int $critical
     * @return Product
     */
    public function setCritical(int $critical): Product
    {
        $this->critical = $critical;
        return $this;
    }


}
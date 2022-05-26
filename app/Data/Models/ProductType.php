<?php

declare(strict_types=1);

namespace App\Data\Models;

use App\Data\Model;
use PDO;

final class ProductType extends Model
{
    protected int $id;
    protected string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Get all product type records from the database
     *
     * @return ProductType[]
     */
    public static function all()
    {
        $stmt = self::$db->prepare("SELECT * FROM ProductType");

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_FUNC, [__CLASS__, "createType"]);

        return $results;
    }

    /**
     * Get a product type record from the database by its ID
     *
     */
    public static function findByID(int $id): ?ProductType
    {
        $stmt = self::$db->prepare("SELECT * FROM ProductType WHERE id = ?");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return is_bool($result) ? null : self::createType($result->id, $result->type);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private static function createType(string $id, string $name)
    {
        return new self((int)$id, $name);
    }
}

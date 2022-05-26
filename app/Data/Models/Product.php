<?php

declare(strict_types=1);

namespace App\Data\Models;

use App\Data\Model;
use PDO;

final class Product extends Model
{
    protected int $id;
    protected string $name;
    protected string $sku;
    protected float $price;
    protected int $typeID;
    protected ?string $typeName = null;
    protected ?float $size = null;
    protected ?float $weight = null;
    protected ?float $height = null;
    protected ?float $width = null;
    protected ?float $length = null;

    public function __construct(
        int $id,
        string $name,
        string $sku,
        float $price,
        int $typeID,
        float $size = null,
        float $weight = null,
        float $height = null,
        float $width = null,
        float $length = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->sku = $sku;
        $this->price = $price;
        $this->typeID = $typeID;
        $this->size = $size;
        $this->weight = $weight;
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    /**
     * @return Product[]
     */
    public static function all()
    {
        $stmt = self::$db->prepare("SELECT * FROM Product");

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_FUNC, [__CLASS__, "createType"]);

        return self::readable($results);
    }

    private static function createType(
        string $id,
        string $name,
        string $sku,
        string $price,
        string $typeID,
        string $size = null,
        string $weight = null,
        string $height = null,
        string $width = null,
        string $length = null
    ) {
        return new self(
            intval($id),
            $name,
            $sku,
            floatval($price),
            intval($typeID),
            floatval($size),
            floatval($weight),
            floatval($height),
            floatval($width),
            floatval($length)
        );
    }

    private static function readable(array $products): array
    {
        $types = ProductType::all();

        return array_map(function (Product $product) use ($types) {
            $filteredType = array_filter($types, function (ProductType $type) use ($product) {
                return $type->getId() === $product->typeID;
            });

            $product->typeName = [...$filteredType][0]->getName();
            return $product;
        }, $products);
    }
}

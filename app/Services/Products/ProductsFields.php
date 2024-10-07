<?php

namespace App\Services\Products;

class ProductsFields
{
//NR2IfXbpmOCs
//'name',
//'description',
//'price',
//'quantity',
//'discount',
//'color',
//'size',
//'weight',
//'seller_id',
//'supplier_id',
//

    const TABLE_PRODUCT = "Product";
    const TABLE_PRODUCT_ATTRIBUTES = "ProductAttributes";


    private function fields()
    {
        return [
            [
                "db_field" => "price",
                "db_type" => "integer",
                "db_table" => static::TABLE_PRODUCT,
                "id" => 1,
                "name" => "Цена",
            ],
            [
                "db_field" => "name",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT,
                "id" => 2,
                "name" => "Название",
            ],
            [
                "db_field" => "quantity",
                "db_type" => "integer",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 3,
                "name" => "Количество",
            ],
            [
                "db_field" => "description",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 4,
                "name" => "Описание",
            ],
            [
                "db_field" => "external_id",
                "db_type" => "integer",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 5,
                "name" => "Внешний ID",
            ],
            [
                "db_field" => "attributes",
                "db_type" => "json",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 6,
                "name" => "Наименование",
            ],
            [
                "db_field" => "attributes",
                "db_type" => "json",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 7,
                "name" => "Внешний ID",
            ],
            [
                "db_field" => "product_code",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 9,
                "name" => "Артикул товара"
            ],
            [
                "db_field" => "product_code",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 10,
                "name" => "Артикул товара"
            ],
            [
                "db_field" => "brand",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 11,
                "name" => "Бренд",
            ],
            [
                "db_field" => "length",
                "db_type" => "integer",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 14,
                "name" => "Высота",
            ],
            [
                "db_field" => "width",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 15,
                "name" => "Ширина",
            ],
            [
                "db_field" => "weight",
                "db_type" => "integer",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 16,
                "name" => "Вес",
            ],
            [
                "db_field" => "color",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 17,
                "name" => "Цвет",
            ],
            [
                "db_field" => "suppliers",
                "db_type" => "string",
                "db_table" => static::TABLE_PRODUCT_ATTRIBUTES,
                "id" => 18,
                "name" => "Supplier",
            ],
        ];
    }

    public function getFieldId($id = 0)
    {
    }

    public function getFieldName($id)
    {
        foreach ($this->fields() as $row) {
            if ($row['id'] == $id) {
                return $row;
            }
        }
    }
}

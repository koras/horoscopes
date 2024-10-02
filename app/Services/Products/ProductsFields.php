<?php

namespace App\Services\Products;

class ProductsFields
{
    private $titleName = [
        [
            "id" => 1,
            "name" => "Выберите категорию",
        ],
        [
            "id" => 2,
            "name" => "Название",
        ],
        [
            "id" => 3,
            "name" => "Количество",
        ],
        [
            "id" => 4,
            "name" => "Название",
        ],
        [
            "id" => 5,
            "name" => "Внешний ID",
        ],
        [
            "id" => 6,
            "name" => "Наименование",
        ],
        [
            "id" => 7,
            "name" => "Описание",
        ],
        [
            "id" => 8,
            "name" => "Стоимость",
        ],
        8 => [
            "id" => 9,
            "name" => "Количество",
        ],
        [
            "id" => 10,
            "name" => "Артикул"
        ],
        [
            "id" => 11,
            "name" => "Бренд",
        ],
        [
            "id" => 12,
            "name" => "Бренд",
        ],
        [
            "id" => 13,
            "name" => "Описание",
        ],
        [
            "id" => 14,
            "name" => "Высота",
        ],
        [
            "id" => 15,
            "name" => "Ширина",
        ],
        [
            "id" => 16,
            "name" => "ВЕС",
        ],
        [
            "id" => 17,
            "name" => "ЦВЕТ",
        ],
        [
            "id" => 18,
            "name" => "Поставщик",
        ],
        [
            "id" => 19,
            "name" => "Id",
        ],
    ];

    public function getFieldId($id = 0)
    {
    }

    public function getFieldName($id)
    {
        foreach ($this->titleName as $row) {
            if($row['id']==$id){
                return $row;
            }
        }
    }
}

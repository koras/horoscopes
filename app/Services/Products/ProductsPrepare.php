<?php

namespace App\Services\Products;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsPrepare implements ToModel
{

    const EXTERNAL_ID = "Внешний ID";
    const PRODUCT_NAME = "Наименование";
    const DESCRIPTION = "Описание";
    const COST = "Стоимость";
    const QUANTITY = "Количество";
    const ARTICLE = "Артикул";
    const BRAND = "Бренд";
    const CATEGORIES = "Бренд";
    const SIZE = "Описание";
    const HEIGHT = "Высота";
    const WIDTH = "Ширина";
    const WEIGHT = "ВЕС";
    const COLOR = "ЦВЕТ";
    const SUPPLIER = "Поставщик";


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $collums = [];
        foreach ($row as $key => $col) {

            if($this->getCost($col)){
                $collums[$key] = "Стоимость";
            }

            if($this->getQuantity($col)){
                $collums[$key] = "Количество";
            }
            if($this->getID($col)){
                $collums[$key] = "Id";
            }
            if($this->getName($col)){
                $collums[$key] = "Название";
            }
        }
        return $collums;

    }


    private function getQuantity($col)
    {
        return match ($col) {
            'количество' => true,
            'кол-во' => true,
            "Количество" => true,
            'кол.' => true,
            default => false,
        };
    }


    private function getCost($col)
    {
        return match ($col) {
            'цена' => true,
            'cost' => true,
            "Цена" => true,
            'стоимость' => true,
            default => false,
        };
    }


    private function getName($col)
    {
        return match ($col) {
            'Название' => true,
            'Имя' => true,
            "title" => true,
            default => false,
        };
    }

    private function getID($col)
    {
        return match ($col) {
            'Id' => true,
            'id' => true,
            'Номер' => true,
            'Уникальный идентификатор' => true,
            'Идентификатор' => true,
            default => false,
        };
    }


}

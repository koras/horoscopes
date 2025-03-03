<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Services\Products\ProductsFields;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;


class ProductsPrepare implements ToCollection
{
    private $productsFields;

    public function __construct()
    {
        $this->productsFields = new ProductsFields();
    }

    private $titleName = [
        0 => "Выберите категорию",
        1 => "Название",
        2 => "Количество",
        3 => "Название",
        4 => "Внешний ID",
        5 => "Наименование",
        6 => "Описание",
        7 => "Стоимость",
        8 => "Количество",
        9 => "Артикул",
        10 => "Бренд",
        11 => "Бренд",
        12 => "Описание",
        13 => "Высота",
        14 => "Ширина",
        15 => "ВЕС",
        16 => "ЦВЕТ",
        17 => "Поставщик",
        18 => "Id",
    ];

    private function getCategoties()
    {
        return $this->titleName;
    }

    private function getTitle($id)
    {
        $result = [];
        $data = $this->productsFields->getFieldName($id);
        if(isset($data["id"])){
            $result["id"] = $data["id"];
        }
        if(isset($data["name"])){
            $result["name"] = $data["name"];
        }

        return $result;
    }

    public $firstFiveRows = [];

    public function collection(Collection $rows)
    {
        $result = [];
        // Получаем первые 5 строк из коллекции
        $this->firstFiveRows = $rows->take(5);

        $result["default"] = $this->getSelect();
        $result["items"] = $this->getItems();
        $result["categories"] = $this->getCategoties();

        $this->firstFiveRows = $result;
    }


    private function getItems()
    {
        $result = [];
        foreach ($this->firstFiveRows as $key => $rows) {
            foreach ($rows as $collum) {
                $result[$key][] = $collum;
            }
        }
        return $result;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private function getSelect()
    {
        // Останавливаем импорт, если обработано больше 5 строк
        $collums = [];
        foreach ($this->firstFiveRows[0] as $key) {
            $collums[] = $this->getTitle(1);
        }
        foreach ($this->firstFiveRows as $key => $rows) {
            foreach ($rows as $collum => $item) {
                if ($this->getCost($item)) {
                    $collums[$collum] = $this->getTitle(4);// "Количество"
                }
                if ($this->getQuantity($item)) {
                    $collums[$collum] = $this->getTitle(3); // ID
                }
                if ($this->getID($item)) {
                    $collums[$collum] = $this->getTitle(19); // ID
                }
                if ($this->getName($item)) {
                    $collums[$collum] = $this->getTitle(2); //"Название";
                }
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

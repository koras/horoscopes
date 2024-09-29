<?php

namespace App\Services\Products;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;

class ProductsPrepare implements ToCollection
{
    private $titleName = [
        0 => "Выберите категорию",
        18 => "Id",
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
    ];

    private function getCategoties()
    {
        return $this->titleName;
    }

    private function getTitle($num)
    {
        return $this->titleName[$num] ?? "";
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
//        dd( $this->firstFiveRows);
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
            $collums[] = ["id" => 0, "name" => $this->getTitle(0)];
        }
        foreach ($this->firstFiveRows as $key => $rows) {
            foreach ($rows as $collum => $item) {
                if ($this->getCost($item)) {
                    $collums[$collum] = ["id" => 3, "name" => $this->getTitle(3)];// "Количество"
                }
                if ($this->getQuantity($item)) {
                    $collums[$collum] = ["id" => 2, "name" => $this->getTitle(2)]; // ID
                }
                if ($this->getID($item)) {
                    $collums[$collum] = ["id" => 18, "name" => $this->getTitle(18)]; // ID
                }
                if ($this->getName($item)) {
                    $collums[$collum] = ["id" => 1, "name" => $this->getTitle(1)]; //"Название";
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

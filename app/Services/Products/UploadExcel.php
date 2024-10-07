<?php

namespace App\Services\Products;

use App\Models\ProductFileShops;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Products\ProductsFields;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductAttributes;

class UploadExcel
{
    public function __construct(public ProductFileShops $fileShops, private ProductsFields $productsFields)
    {
    }

    public function parserExcel($request)
    {
        $uploadId = $request->input("upload_id");
        $columns = $request->input("col");
        $fileRecord = $this->fileShops->getFile($uploadId);
        if ($fileRecord) {
            $filePath = $fileRecord->path;
            $data = $this->getData($filePath);
            // Вернем данные

            $this->prepareProduct($columns, $data);
            return $data;
        }
    }


    /**
     * Обрабатывает данные из экселя
     * @param $columns
     * @param $data
     * @return void
     */
    private function prepareProduct($columns = [], $datas = [])
    {
        $product = new Product();
        $productAttributes = new ProductAttributes();
       // unset($datas[0]);
        $datas = $datas[0];
        unset($datas[0]);
     //   dd($datas);

        foreach ($datas as $key => $row) {


//            0 => 92
//          1 => "3400.0000"
//          2 => "Настольный футбол Football Champions"
//          3 => 900010
//          4 => null
//          5 => 1
          //  foreach ($rows as $key => $row) {
            //    dd($rows);
                if(!isset($columns[$key])){
                    dd($columns, $key,$row);
                    continue;
                }
                $fieldId = $columns[$key];
                $field = $this->productsFields->getFieldName($fieldId);

                $attributes = [];
                if(is_null($field)){
                    dd($fieldId,$field);
                }
                $column = $field["db_field"];
                if(!isset($field[ "db_type"])){
                    dd($field);
                }
                $converter = $field[ "db_type"];

            //   dd($field);
                switch ($field["db_table"]) {
                    case "ProductAttributes":
                        {
                            if($converter =="integer"){
                                $productAttributes->{$column} = (int) $row;
                            }elseif($converter =="string")
                            {
                                $productAttributes->{$column} = (string) $row;
                            }else{
                                $productAttributes->{$column} = (string) $row;
                            }
                        }
                        break;
                    case "Product":
                        {
                            if ($column == "attributes") {
                                $keyName = $row["key"];
                                $attributes[$keyName] = $row["name"];
                            } else {
                                $product->{$column} = $row;
                            }
                        }
                        break;
                }

                //$productsFields
                //  echo $row;
                // echo $columns[$key];
                // $columns[$key];
           // }
            $product->save();
          //  dd($product);
            $productAttributes->product_attributes_product_id = $product->product_id;
            $productAttributes->attributes = json_encode($attributes);
            $productAttributes->save();
        }
    }


    /**
     * Получаем данные из файла
     * @param $filePath
     * @return array
     */
    private function getData($filePath)
    {
        // Проверяем, существует ли файл
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        $file = Storage::disk('public')->path($filePath);
        return Excel::toArray([], $file);
    }

}

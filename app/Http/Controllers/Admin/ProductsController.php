<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductsPrepare;
use Illuminate\Http\Request;
use App\Services\Products;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductsExcelRequest;
use App\Services\Products\UploadExcel;
use Maatwebsite\Excel\Facades\Excel;


use App\Models\ProductFileShops;


class ProductsController extends Controller
{

    /**
     * Готовим эксель для сохранения
     * @param Request $request
     * @return void
     */
    public function preparationExcel(ProductsExcelRequest $request)
    {
        $service = new ProductsPrepare();
        Excel::import($service, $request->file('file'));
        $file = $request->file('file');
        $hashedName = $file->hashName();
        $path = $file->storeAs('uploads', $hashedName, 'public');
        // Сохраняем информацию о файле в базу данных
        $fileRecord = new ProductFileShops();
        $fileRecord->name = $file->getClientOriginalName(); // Оригинальное имя файла
        $fileRecord->path = $path; // Путь к файлу
        $fileRecord->hash_name = $hashedName; // Хэшированное имя файла
        $fileRecord->save();
        $result = $service->firstFiveRows;

        $result["file"] = [
            "id" => $fileRecord->id,
            "name" => $file->getClientOriginalName(),
        ];

        return $result;
    }

    public function uploadExcel(ProductsExcelRequest $request, UploadExcel $service)
    {
      return  $service->parserExcel($request);

    }


    /**
     * Сохраняем эксель
     * @param Request $request
     * @return void
     */
    public function saveExcel(Request $request)
    {
    }


}
